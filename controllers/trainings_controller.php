<?php
	class TrainingsController extends Controller {
		private	$consume_mana		= 0;
		private	$consume_stamina	= 2;

		function __construct() {
			parent::__construct();

			$player	= Player::get_instance();

			$this->consume_mana		= 25 + (3 * $player->level);
			$this->consume_stamina	-= $player->attributes()->sum_bonus_stamina_consume;

			if($player->attributes()->sum_bonus_attribute_training_cost) {
				$this->consume_mana	-= percent($player->attributes()->sum_bonus_attribute_training_cost, $this->consume_mana);
			}
		}

		function attributes() {
			$player	= Player::get_instance();

			$this->assign('player', $player);
			$this->assign('consume_mana', $this->consume_mana);
			$this->assign('consume_stamina', $this->consume_stamina);
		}

		function train_attribute() {
			$this->layout			= false;
			$this->as_json			= true;
			$this->render			= false;
			$this->json->success	= false;
			$player					= Player::get_instance();
			$errors					= array();

			if(isset($_POST['quantity']) && is_numeric($_POST['quantity']) && $_POST['quantity'] >= 5) {
				$consume_mana		= $this->consume_mana * $_POST['quantity'];
				$consume_stamina	= $this->consume_stamina * ceil($_POST['quantity'] / 5);
				$points				= 30 * $_POST['quantity'];
				$points				+= percent($player->attributes()->sum_bonus_training_earn, $points);

				if($consume_mana > $player->for_mana()) {
					$errors[]	= t('attributes.attributes.errors.mana', array('name' => t('formula.for_mana.' . $player->character()->anime_id)));
				}

				if($consume_stamina > $player->for_stamina()) {
					$errors[]	= t('attributes.attributes.errors.stamina');
				}

				if($player->weekly_points_spent == $player->max_attribute_training()) {
					$errors[]	= t('attributes.attributes.errors.limit');
				}
			} else {
				$errors[]	= t('attributes.attributes.errors.quantity');
			}

			if(!sizeof($errors)) {
				$exp_multiplier	= 30 - ($player->level / 10);
				$exp			= $exp_multiplier > 0 ? $exp_multiplier * $_POST['quantity'] : 0;
				$exp			-= percent($player->attributes()->sum_bonus_training_exp, $points);

				$player->less_mana				+= $consume_mana;
				$player->less_stamina			+= $consume_stamina;
				$player->exp					+= $exp;

				if($player->weekly_points_spent + $points > $player->max_attribute_training()) {
					$real_points					= $player->max_attribute_training() - $player->weekly_points_spent;
					$player->training_total			+= $real_points;
					$player->weekly_points_spent	=  $player->max_attribute_training();
				} else {
					$real_points					= $points;
					$player->training_total			+= $points;
					$player->weekly_points_spent	+= $points;
				}

				$player->save();

				$this->json->success			= true;
				$this->json->exp				= $exp;
				$this->json->points				= $real_points;

				$this->json->level				= $player->level;
				$this->json->exp_player			= $player->exp;
				$this->json->level_exp			= $player->level_exp();

				$this->json->mana				= $player->for_mana();
				$this->json->max_mana			= $player->for_mana(true);
				$this->json->stamina			= $player->for_stamina();
				$this->json->max_stamina		= $player->for_stamina(true);
				$this->json->view				= partial('traning_limit', [
					'player'		=> $player, 
					'spent_mana'	=> $consume_mana,
					'spent_stamina'	=> $consume_stamina,
					'earn_points'	=> $real_points,
					'earn_exp'		=> $exp
				]);
			} else {
				$this->json->errors	= $errors;
			}
		}

		function distribute_attribute() {
			$this->as_json		= true;
			$player				= Player::get_instance();
			$max				= 0;
			$avail				= $player->available_training_points();
			$allowed_attributes	= array('at_for', 'at_int', 'at_res', 'at_agi', 'at_dex', 'at_vit');
			$errors				= array();

			// Normal point distribution
			if(isset($_POST['attribute']) && in_array($_POST['attribute'], $allowed_attributes) && isset($_POST['quantity']) && is_numeric($_POST['quantity'])) {
				if($_POST['quantity'] < 1) {
					$errors[]	= t('attributes.distribute.errors.invalid');
				}

				if($_POST['quantity'] > $avail) {
					$errors[]	= t('attributes.distribute.errors.enough');
				}

				if(!sizeof($errors)) {
					$player->{$_POST['attribute']}	+= $_POST['quantity'];
					$player->training_points_spent	+= $_POST['quantity'];
					$player->save();
				}
			}

			// General point distribution
			if(isset($_POST['general']) && isset($_POST['data']) && is_array($_POST['data'])) {
				$total	= 0;
				$update	= array();

				foreach($_POST['data'] as $data) {
					if(isset($data['attribute']) && isset($data['quantity']) && in_array($data['attribute'], $allowed_attributes) && is_numeric($data['quantity'])) {
						$update[]	= $data;
						$total		+= $data['quantity'];
					}
				}

				if($total <= $avail) {
					foreach ($update as $attribute) {
						$player->{$attribute['attribute']}	+= $attribute['quantity'];
						$player->training_points_spent		+= $attribute['quantity'];
					}

					$player->save();
				}
			}

			$attributes	= array(
				'at_for'	=> t('at.at_for'),
				'at_int'	=> t('at.at_int'),
				'at_res'	=> t('at.at_res'),
				'at_agi'	=> t('at.at_agi'),
				'at_dex'	=> t('at.at_dex'),
				'at_vit'	=> t('at.at_vit')
			);

			foreach ($attributes as $_ => $attribute) {
				$value	= $player->{$_}();

				if($value > $max) {
					$max	= $value;
				}
			}

			$this->json->mana			= $player->for_mana();
			$this->json->max_mana		= $player->for_mana(true);
			$this->json->stamina		= $player->for_stamina();
			$this->json->max_stamina	= $player->for_stamina(true);
			$this->json->view			= partial('distribute_attribute', [
				'max'			=> $max,
				'player'		=> $player,
				'current_exp'	=> $player->training_to_next_point(true),
				'point_exp'		=> $player->training_to_next_point(),
				'points'		=> $player->available_training_points(),
				'attributes'	=> $attributes,
				'errors'		=> $errors
			]);
		}

		function techniques() {
			$player				= Player::get_instance();
			$max_training		= $player->max_technique_training();
			$can_train			= $player->technique_training_spent < $max_training;
			$learned_techniques	= $player->learned_techniques();

			if($_POST) {
				$this->layout			= false;
				$this->as_json			= true;
				$this->render			= false;
				$this->json->success	= false;
				$errors					= array();

				if((!isset($_POST['item']) || (isset($_POST['item']) && !is_numeric($_POST['item']))) || (!isset($_POST['duration']) || (isset($_POST['duration']) && !is_numeric($_POST['duration'])))) {
					$errors[]	= t('techniques.training.errors.invalid_data');
				} else {
					if(!between($_POST['duration'], 1, 3)) {
						$errors[]	= t('techniques.training.errors.invalid_duration');
					} else {
						$found	= false;

						foreach($learned_techniques as $technique) {
							if($technique->id == $_POST['item']) {
								$found = $technique;
								break;
							}
						}

						if(!$found) {
							$errors[]	= t('techniques.training.errors.invalid_technique');
						}
					}
				}
	
				if(!sizeof($errors)) {
					$player->technique_training_id			= $_POST['item'];
					$player->technique_training_complete_at	= date('Y-m-d H:i:s', strtotime('+' . (30 * $_POST['duration']) . ' minute'));
					$player->technique_training_duration	= $_POST['duration'];
					$player->save();

					$this->json->success	= true;
				} else {
					$this->json->errors	= $errors;
				}
			} else {
				$this->assign('player', $player);
				$this->assign('techniques', $learned_techniques);
				$this->assign('max_training', $max_training);
				$this->assign('can_train', $can_train);
			}
		}

		function technique_wait() {
			$player		= Player::get_instance();
			$diff		= get_time_difference(now(), $player->technique_training_complete_at);
			$technique	= PlayerItem::find($player->technique_training_id);
			$item		= $technique->item();
			$finished	= now() > strtotime($player->technique_training_complete_at);

			if($_POST) {
				$this->layout			= false;
				$this->as_json			= true;
				$this->render			= false;
				$this->json->success	= false;
				$errors					= array();

				if(isset($_POST['finish']) && $_POST['finish']) {
					if(!$finished) {
						$errors[]	= t('techniques.training.wait.errors.non_finished');
					}
				}

				if(!sizeof($errors)) {
					if(isset($_POST['finish']) && $_POST['finish']) {
						$stat								= $technique->stats();
						$stat->exp							+= $player->technique_training_duration * 1000;
						$player->technique_training_spent	+= $player->technique_training_duration * 1000;

						// Level up?
						if($stat->exp > $item->exp_needed_for_level()) {
							$stat->exp			-= $item->exp_needed_for_level();

							$technique->level	+= 1;
							$technique->save();
						}

						$stat->save();
					}

					$player->technique_training_id			= 0;
					$player->technique_training_complete_at	= null;
					$player->technique_training_duration	= 0;
					$player->save();

					$this->json->success	= true;
				} else {
					$this->json->errors	= $errors;
				}
			} else {
				$this->assign('player', $player);
				$this->assign('diff', $diff);
				$this->assign('technique', $technique);
				$this->assign('finished', $finished);
			}
		}
	}