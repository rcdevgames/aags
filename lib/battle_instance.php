<?php
	class BattleInstance {
		public	$log			= [];
		private	$player			= null;
		private	$player_item	= null;
		private	$enemy			= null;
		private	$enemy_item		= null;

		function set_player(&$player) {
			$this->player	=& $player;
		}

		function set_player_item(&$item) {
			$this->player_item	=& $item;
		}

		function set_enemy($enemy) {
			$this->enemy	=& $enemy;
		}

		function set_enemy_item(&$item) {
			$this->enemy_item	=& $item;
		}

		function run() {
			$this->log			= [];
			$log				= [];
			$entry				= '';

			$critical_image		= '<img src="' . image_url('icons/for_crit.png') . '" align="absmiddle" />';
			$absorb_image		= '<img src="' . image_url('icons/for_abs.png') . '" align="absmiddle" />';
			$precision_image	= '<img src="' . image_url('icons/for_prec.png') . '" align="absmiddle" />';

			$player_attack			= $this->player->for_atk() + $this->player_item->formula()->damage;
			$player_defense			= $this->player->for_def() + $this->player_item->formula()->defense;
			$player_is_critical		= rand(1, 100) <= $this->player->for_crit();
			$player_is_absorb		= rand(1, 100) <= $this->player->for_abs();
			$player_is_precision	= rand(1, 100) <= $this->player->for_prec();

			if($player_is_critical) {
				$player_attack	+= percent($player->for_crit_inc(), $player_attack);				
			}

			if($player_is_absorb) {
				$player_defense	+= percent($player->for_crit_inc(), $player_defense);
			}

			$enemy_attack			= $this->enemy->for_atk() + $this->enemy_item->formula()->damage;
			$enemy_defense			= $this->enemy->for_def() + $this->enemy_item->formula()->defense;
			$enemy_is_critical		= rand(1, 100) <= $this->enemy->for_crit();
			$enemy_is_absorb		= rand(1, 100) <= $this->enemy->for_abs();
			$enemy_is_precision		= rand(1, 100) <= $this->enemy->for_prec();

			if($enemy_is_critical) {
				$enemy_attack	+= percent($enemy->for_crit_inc(), $enemy_attack);				
			}

			if($enemy_is_absorb) {
				$enemy_defense	+= percent($enemy->for_crit_inc(), $enemy_defense);
			}

			// Precision should be processed here since we'll null a defensive value -->
				if ($player_is_precision) {
					$enemy_defense	= 0;
				}

				if ($enemy_is_precision) {
					$player_defense	= 0;
				}
			// <--

			$player_damage		= $player_attack - $enemy_defense;
			$enemy_damage		= $enemy_attack - $player_defense;

			for ($i=0; $i <= 1; $i++) { 
				$item			= $i ? $this->enemy_item : $this->player_item;
				$player			= $i ? $this->enemy : $this->player;
				$enemy			= $i ? $this->player : $this->enemy;
				$tooltip_id		= 'bi-' . uniqid(uniqid(), true);
				$is_critical	= $i ? $enemy_is_critical : $player_is_critical;
				$is_absorb		= $i ? $enemy_is_absorb : $player_is_absorb;
				$is_precision	= $i ? $enemy_is_precision : $player_is_precision;
				$effect			= '';

				if($is_critical) {
					$effect	.= $critical_image;
				}

				if($is_absorb) {
					$effect	.= $absorb_image;
				}

				if($is_precision) {
					$effect	.= $precision_image;
				}

				$damage	= $i ? $enemy_damage : $player_damage;
				$entry	.= t('battles.attack_text', [
					'player'	=> $player->name,
					'enemy'		=> $enemy->name,
					'item'		=> $effect . $item->description()->name,
					'tooltip'	=> $tooltip_id
				]);

				if(!$item->is_defensive) {
					if($damage == 0) {
						$entry	.= t('battles.defense_text');
					} elseif($damage > 0) {
						$entry				.= t('battles.damage_text', ['damage' => $damage]);
						$enemy->less_life	+= $damage;
					} elseif($damage < 0) {
						$entry				.= t('battles.counter_text', ['damage' => abs($damage)]);
						$player->less_life	+= -$damage;
					}
				}

				$entry	.= partial('shared/battle_item', ['player' => $player, 'item' => $item, 'id' => $tooltip_id]);
				
				if(!$i) {
					$entry	.= "<br /><br />";
				}
			}

			$log[]	= $entry;

			$this->player->less_mana	+= $this->player_item->formula()->consume_mana;
			$this->enemy->less_mana		+= $this->enemy_item->formula()->consume_mana;

			$this->log	= $log;
		}
	}