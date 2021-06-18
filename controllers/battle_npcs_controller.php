<?php
	class BattleNpcsController extends Controller {
		function index() {
			$player	= Player::get_instance();
			$npc	= new NpcInstance($player->level);

			$player->clear_technique_locks();
			$player->clear_modifiers();
			$player->save_npc($npc);

			$this->assign('player', $player);
			$this->assign('npc', $npc);
		}

		function accept() {
			$player					= Player::get_instance();
			$battle					= new BattleNpc();
			$battle->player_id		= $player->id;
			$battle->battle_type_id	= 1;
			$battle->save();

			$player->battle_npc_id	= $battle->id;
			$player->save();

			$this->as_json			= true;
			$this->json->success	= true;
		}

		function fight() {
			$player	= Player::get_instance();
			$npc	= $player->get_npc();

			$this->assign('player', $player);
			$this->assign('npc', $npc);
			$this->assign('techniques', $player->get_techniques());
			$this->assign('target_url', make_url('battle_npcs'));
			$this->assign('log', @unserialize($player->battle_npc()->battle_log));
		}

		function attack() {
			$player			= Player::get_instance();
			$npc			= $player->get_npc();
			$battle			= $player->battle_npc();
			$log			= @unserialize($battle->battle_log);
			$errors			= [];
			$this->as_json	= true;

			if(!is_array($log)) {
				$log	= [];
			}

			if(!isset($_POST['item']) || (isset($_POST['item']) && !is_numeric($_POST['item']))) {
				$errors[]	= t('battles.errors.invalid');
			} else {
				$player_item	= $player->get_technique($_POST['item']);
				$item			= $player_item->item();

				if($item->item_type_id != 1) {
					$errors[]	= t('battles.errors.invalid');
				} else {
					$can_run_action	= true;

					if($item->formula()->consume_mana > $player->for_mana()) {
						$can_run_action	= false;
						$errors[]	= t('battles.errors.no_mana', ['mana' => t('formula.for_mana.' . $item->anime()->id)]);
					}

					if($item->is_buff) {
						$can_run_action	= false;
						$errors[]	= t('battles.errors.no_buff');
					}

					if($player->has_technique_lock($item->id)) {
						$can_run_action	= false;
						$errors[]	= t('battles.errors.locked');
					}

					if($can_run_action) {
						$player->add_technique_lock($item);

						$battle_instance		= new BattleInstance();
						$battle_instance->battle_npc_id	= $player->battle_npc_id;
						$battle_instance->set_player($player);
						$battle_instance->set_player_item($item);

						$npc->choose_modifier($battle);

						$battle_instance->set_enemy($npc);
						$battle_instance->set_enemy_item($npc->choose_technique());
						$battle_instance->run();

						$npc->rotate_modifiers();
						$npc->rotate_technique_locks();

						$player->rotate_modifiers();
						$player->rotate_technique_locks();

						$player->save_npc($npc);
						$battle->battle_log	= serialize(array_merge($log, $battle_instance->log));

						if(($player->for_life() <= 0 || $player->for_mana() <= 10) && ($npc->for_life() <= 0 || $npc->for_mana() <= 10)) { // Tied
							$battle->finished		= 1;
							$battle->finished_at	= now(true);
							$battle->won			= 0;
							$player->battle_npc_id	= 0;

							$this->json->finished	= partial('shared/info', ['title' => 'battles.finished.tied_title', 'message' => t('battles.finished.tied_text')]);
						} else {
							if($player->for_life() <= 0 || $player->for_mana() <= 10) { // Loss
								$battle->finished_at	= now(true);
								$battle->won			= 0;
								$player->battle_npc_id	= 0;

								$this->json->finished	= partial('shared/info', ['title' => 'battles.finished.loss_title', 'message' => t('battles.finished.loss_text')]);
							} elseif($npc->for_life() <= 0 || $npc->for_mana() <= 10) { // Win
								$battle->finished_at	= now(true);
								$battle->won			= 1;
								$player->battle_npc_id	= 0;

								$this->json->finished	= partial('shared/info', ['title' => 'battles.finished.win_title', 'message' => t('battles.finished.win_text')]);
							}
						}

						$battle->save();						
						$player->save();

						$_SESSION['can_apply_buff']	= true;
					}
				}
			}

			$this->json->log		= unserialize($battle->battle_log);
			$this->json->messages	= $errors;
			$this->_stats_to_json($player, $npc);
		}

		function modifier() {
			$player			= Player::get_instance();
			$npc			= $player->get_npc();
			$battle			= $player->battle_npc();
			$log			= @unserialize($battle->battle_log);
			$errors			= [];
			$this->as_json	= true;

			if(!is_array($log)) {
				$log	= [];
			}

			if(!isset($_POST['item']) || (isset($_POST['item']) && !is_numeric($_POST['item']))) {
				$errors[]	= t('battles.errors.invalid');
			} else {
				$player_item	= $player->get_technique($_POST['item']);
				$item			= $player_item->item();

				if (!in_array($item->item_type_id, [1, 7])) {
					$errors[]	= t('battles.errors.invalid');
				} else {
					$can_run_action	= true;

					if($item->formula()->consume_mana > $player->for_mana()) {
						$can_run_action	= false;
						$errors[]	= t('battles.errors.no_mana', ['mana' => t('formula.for_mana.' . $item->anime()->id)]);
					}

					if(!$item->is_buff) {
						$can_run_action	= false;
						$errors[]	= t('battles.errors.must_buff');
					} else {
						if($npc->has_modifier($item->id)) {
							$can_run_action	= false;
							$errors[]	= t('battles.errors.buff_already');
						}

						if(isset($_SESSION['can_apply_buff']) && !$_SESSION['can_apply_buff']) {
							$can_run_action	= false;
							$errors[]	= t('battles.errors.buff_used');
						}

						if($player->has_technique_lock($item->id)) {
							$errors[]	= t('battles.errors.locked');
						}
					}

					if($can_run_action) {
						$player->less_mana	+= $item->formula()->consume_mana;
						$player->add_technique_lock($item);
						$player->save();

						if($item->buff_direction == 'friend') {
							$player->add_modifier($item);
						} else {
							$npc->add_modifier($item);
							$player->save_npc($npc);							
						}

						$_SESSION['can_apply_buff']	= false;
					}
				}
			}

			$this->json->log		= unserialize($battle->battle_log);
			$this->json->messages	= $errors;
			$this->_stats_to_json($player, $npc);
		}

		function ping() {
			$this->as_json	= true;
			$player			= Player::get_instance();
			$npc			= $player->get_npc();

			$this->_stats_to_json($player, $npc);
		}

		private function _stats_to_json($p, $e) {
			$this->json->player				= new stdClass();
			$this->json->player->life		= $p->for_life();
			$this->json->player->life_max	= $p->for_life(true);
			$this->json->player->mana		= $p->for_mana();
			$this->json->player->mana_max	= $p->for_mana(true);

			$status							= new stdClass();
			$status->atk					= $p->for_atk();
			$status->def					= $p->for_def();
			$status->crit					= $p->for_crit();
			$status->crit_inc				= $p->for_crit_inc();
			$status->abs					= $p->for_abs();
			$status->abs_inc				= $p->for_abs_inc();
			$status->prec					= $p->for_prec();
			$status->inti					= $p->for_inti();
			$status->conv					= $p->for_conv();
			$status->init					= $p->for_init();

			$this->json->player->status		= $status;

			$this->json->enemy				= new stdClass();
			$this->json->enemy->life		= $e->for_life();
			$this->json->enemy->life_max	= $e->for_life(true);
			$this->json->enemy->mana		= $e->for_mana();
			$this->json->enemy->mana_max	= $e->for_mana(true);

			$status							= new stdClass();
			$status->atk					= $e->for_atk();
			$status->def					= $e->for_def();
			$status->crit					= $e->for_crit();
			$status->crit_inc				= $e->for_crit_inc();
			$status->abs					= $e->for_abs();
			$status->abs_inc				= $e->for_abs_inc();
			$status->prec					= $e->for_prec();
			$status->inti					= $e->for_inti();
			$status->conv					= $e->for_conv();
			$status->init					= $e->for_init();

			$this->json->enemy->status		= $status;

			$pmods	= [];
			$emods	= [];
			$locks	= [];

			foreach ($p->get_modifiers() as $key => $modifier) {
				$mod			= new stdClass();
				$mod->image		= $modifier['instance']->image(true);
				$mod->tooltip	= $modifier['instance']->tooltip();
				$mod->remaining	= $modifier['turns'];

				$pmods[]		= $mod;
			}

			foreach ($e->get_modifiers() as $key => $modifier) {
				$mod			= new stdClass();
				$mod->image		= $modifier['instance']->image(true);
				$mod->tooltip	= $modifier['instance']->tooltip();
				$mod->remaining	= $modifier['turns'];

				$emods[]		= $mod;
			}

			foreach ($p->get_technique_locks() as $key => $lock) {
				$l				= new stdClass();
				$l->remaining	= $lock['turns'];
				$l->id			= $key;

				$locks[]		= $l;
			}

			$this->json->player->mods	= $pmods;
			$this->json->player->locks	= $locks;
			$this->json->enemy->mods	= $emods;
		}
	}