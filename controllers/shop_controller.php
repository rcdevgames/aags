<?php
	class ShopController extends Controller {
		function food() {
			$player	= Player::get_instance();

			$this->assign('discount', $player->attributes()->sum_bonus_food_discount);
			$this->assign('player', $player);
			$this->assign('items', $player->character()->consumables());
		}

		function weapons() {
			$player	= Player::get_instance();

			$this->assign('discount', $player->attributes()->sum_bonus_weapon_discount);
			$this->assign('player', $player);
			$this->assign('items', $player->character_theme()->weapons());
		}

		function buy() {
			$this->layout			= false;
			$this->as_json			= true;
			$this->render			= false;
			$this->json->success	= false;
			$player					= Player::get_instance();
			$user					= User::get_instance();
			$errors					= array();

			if(isset($_POST['item']) && is_numeric($_POST['item']) && isset($_POST['method']) && is_numeric($_POST['method']) && isset($_POST['quantity']) && $_POST['quantity'] >= 1) {
				$item			= Item::find($_POST['item']);
				$discount		= $item->item_type_id == 5 ? $player->attributes()->sum_bonus_food_discount : $player->attributes()->sum_bonus_weapon_discount;
				$price_currency	= $item->price_currency - percent($discount, $item->price_currency);

				if(!$item || ($item && !in_array($item->item_type_id, [5, 7]))) {
					$errors[]	= t('shop.errors.invalid');
				} else {
					$methods	= array();

					if($item->price_currency) { $methods[]	= 1; }
					if($item->price_vip) { $methods[]	= 2; }

					if(!in_array($_POST['method'], $methods)) {
						$errors[]	= t('shop.errors.method');
					} else {
						if($_POST['method'] == 1 && ($price_currency * $_POST['quantity']) > $player->currency) {
							$errors[]	= t('shop.errors.enough_currency');
						}

						if($_POST['method'] == 2 && ($item->price_vip * $_POST['quantity']) > $user->credits) {
							$errors[]	= t('shop.errors.enough_credits');
						}
					}
				}
			} else {
				$errors[]	= t('shop.errors.invalid');
			}

			if(!sizeof($errors)) {
				$player_item			= $player->add_consumable($item, $_POST['quantity']);

				$this->json->success	= true;
				$this->json->quantity	= $player_item->quantity;
				$this->json->message	= t('shop.bought');

				if($item->price_vip) {
					$user->spend($item->price_vip * $_POST['quantity']);
				}

				if($item->price_currency) {
					$player->spend($price_currency * $_POST['quantity']);
				}

				$this->json->currency	= $player->currency;
				$this->json->credits	= $user->credits;
			} else {
				$this->json->errors		= $errors;
			}
		}
	}