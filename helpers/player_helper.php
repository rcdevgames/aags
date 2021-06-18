<?php
	function __check_heal() {
		if($_SESSION['player_id']) {
			Player::set_instance(Player::find($_SESSION['player_id']));

			$instance	=& Player::get_instance();
			$now		= new DateTime();

			if(!$instance->last_healed_at) {
				$instance->last_healed_at	= date('Y-m-d H:i:s');
				$instance->save();

				$last_heal	= $now;
			} else {
				$last_heal	= new DateTime($instance->last_healed_at);
			}

			$heal_diff	= $now->diff($last_heal);
			$num_runs	= floor((($heal_diff->d * (24 * 60)) + ($heal_diff->h * 60) + $heal_diff->i) / 2);

			if((!$instance->battle_npc_id && !$instance->battle_pvp_id) && ($instance->less_life > 0 || $instance->less_mana > 0 || $instance->less_stamina > 0) && $num_runs) {
				$current_runs	= 0;

				$max_life		= $instance->for_life(true);
				$max_mana		= $instance->for_mana(true);
				$life_heal		= percent(10, $max_life);
				$mana_heal		= percent(10, $max_mana);
				$stamina_heal	= 2 + $instance->attributes()->sum_bonus_stamina_heal;

				while($current_runs++ < $num_runs) {
					if ($instance->less_life > 0) {
						$instance->less_life	-= $life_heal;
					}

					if ($instance->less_mana > 0) {
						$instance->less_mana	-= $mana_heal;
					}

					if ($instance->less_stamina > 0) {
						$instance->less_stamina	-= $stamina_heal;
					}

					if ($instance->less_life < 0) {
						$instance->less_life	= 0;
					}

					if ($instance->less_mana < 0) {
						$instance->less_mana	= 0;
					}

					if ($instance->less_stamina < 0) {
						$instance->less_stamina	= 0;
					}

					if($instance->less_life == 0 && $instance->less_mana == 0) {
						break;
					}
				}
			}

			$instance->last_healed_at	= date('Y-m-d H:i:s');
			$instance->save();
		}
	}

	__check_heal();