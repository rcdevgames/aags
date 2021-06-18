<?php
	$menu_data		= [];
	$raw_menu_data	= [];
	$menu_actions	= [];
	$url_allowed	= false;

	function generate_menu_data() {
		global $menu_data, $menu_actions, $raw_menu_data;

		$categories	= MenuCategory::all(['cache' => true]);

		if($_SESSION['player_id']) {
			$instance	= Player::find($_SESSION['player_id']);
		} else {
			$instance	= false;
		}

		foreach($categories as $category) {
			$item	= array(
				'id'	=> $category->id,
				'name'	=> $category->name,
				'menus'	=> array()
			);

			$raw_menu_data[$category->id]	= $item;

			if(!is_menu_accessible($category, $instance)) {
				continue;
			}

			$menus						= $category->menus();
			$menu_data[$category->id]	= $item;

			foreach($menus as $menu) {
				$sub_item	= array(
					'id'		=> $menu->id,
					'name'		=> $menu->name,
					'href'		=> $menu->href,
					'hidden'	=> $menu->hidden
				);

				if(!is_menu_accessible($menu, $instance)) {
					continue;
				}

				$menu_actions[]	= make_url($menu->href, array(), true);

				if($menu->hidden) {
					continue;
				}

				$menu_data[$category->id]['menus'][]		= $sub_item;
				$raw_menu_data[$category->id]['menus'][]	= $sub_item;
			}
		}

		$actions	= Menu::find('menu_category_id=0', array('cache' => false));

		foreach ($actions as $action) {
			if(!is_menu_accessible($action, $instance)) {
				continue;
			}

			$menu_actions[]	= make_url($action->href, array(), true);
		}
	}

	function is_menu_accessible($menu, $player) {
		$ok	= true;

		if($menu->h_loggedin == 1 && !$_SESSION['loggedin']) $ok	= false;
		if($menu->h_loggedin == 2 && $_SESSION['loggedin']) $ok		= false;

		if($menu->h_player == 1 && !$player) $ok	= false;
		if($menu->h_player == 2 && $player) $ok	= false;

		if($menu->h_next_level == 1 && !$player) {
			$ok	= false;
		} else {
			if($menu->h_next_level == 1) {
				if(!$player || ($player && !$player->is_next_level())) {
					$ok	= false;
				}
			}

			if($menu->h_next_level == 2) {
				if($player && $player->is_next_level()) {
					$ok	= false;
				}
			}
		}

		if($menu->h_training_technique == 1) {
			if(!$player || ($player && !$player->technique_training_id)) {
				$ok	= false;
			}
		} elseif($menu->h_training_technique == 2) {
			if($player && $player->technique_training_id) {
				$ok	= false;
			}
		}

		if ($menu->h_battle_npc) {
			if ($menu->h_battle_npc == 1) {
				if (!$player || ($player && !$player->battle_npc_id)) {
					$ok	= false;
				}
			} elseif($menu->h_battle_npc == 2) {
				if($player && $player->battle_npc_id) {
					$ok	= false;
				}
			}
		}

		if ($menu->h_battle_pvp) {
			if ($menu->h_battle_pvp == 1) {
				if (!$player || ($player && !$player->battle_pvp_id)) {
					$ok	= false;
				}
			} elseif($menu->h_battle_pvp == 2) {
				if($player && $player->battle_pvp_id) {
					$ok	= false;
				}
			}
		}

		return $ok;
	}

	function validate_current_curl() {
		global $menu_actions, $framework_force_denied, $controller, $action;

		$captcha		= strpos($_SERVER['PATH_INFO'], 'captcha');
		$url_allowed	= false;

		if($captcha !== false && $captcha == 1) {
			return;
		}

		$real_url	= $controller . '/' . $action;

		foreach ($menu_actions as $menu_action) {
			if (strpos($menu_action, '/') === false) {
				$menu_action	.=	'/index';
			}

			if(!$menu_action && !$real_url) {
				$url_allowed	= true;
				break;
			} else {
				if($menu_action) {
					$pos	= strpos($real_url, $menu_action);
					if($pos !== false && $pos == 0) {
						$url_allowed	= true;
						break;
					}
				}
			}
		}

		if (!$url_allowed) {
			$framework_force_denied	= true;
		}
	}

	generate_menu_data();

	if(!IS_MAINTENANCE) {
		validate_current_curl();
	} else {
		if(!($controller == MAINTENANCE_CONTROLLER && $action == MAINTENANCE_ACTION)) {
			validate_current_curl();
		}
	}