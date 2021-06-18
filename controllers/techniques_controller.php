<?php
	class TechniquesController extends Controller {
		function index() {
			$player	= Player::get_instance();

			$this->assign('player', $player);
			$this->assign('items', $player->character_theme()->attacks());
		}

		function learn() {
			$this->layout			= false;
			$this->as_json			= true;
			$this->json->success	= false;
			$player					= Player::get_instance();
			$errors					= array();

			if(isset($_POST['id']) && is_numeric($_POST['id'])) {
				$item	= Item::find($_POST['id']);

				if($item->is_generic) {
					$item->set_anime($player->character()->anime_id);
				} else {
					$item->set_character_theme($player->character_theme());
				}

				$this->assign('player', $player);
				$this->assign('item', $item);

				if($item->item_type_id == 1) {
					extract($item->has_requirement($player));

					if($has_requirement) {
						if($player->has_technique($item)) {
							$errors[]	= t('techniques.learn.learned');
						}
					} else {
						$errors[]	= t('techniques.learn.requirements');
					}
				} else {
					$errors[]	= t('techniques.learn.invalid');
				}
			} else {
				$errors[]	= t('techniques.learn.invalid');
			}

			if(!sizeof($errors)) {
				$this->json->success	= true;

				extract($player->add_technique($item));
				$this->assign('exp', $exp);

				$this->json->exp			= $player->exp;
				$this->json->max_exp		= $player->level_exp();
				$this->json->level			= $player->level;

				$this->json->mana			= $player->for_mana();
				$this->json->max_mana		= $player->for_mana(true);
				$this->json->stamina		= $player->for_stamina();
				$this->json->max_stamina	= $player->for_stamina(true);
			} else {
				$this->json->errors		= $errors;
			}
		}

		function abilities() {
			$this->_ability_or_speciality(true);
		}

		function specialities() {
			$this->_ability_or_speciality(false);
		}

		private function _ability_or_speciality($ability = false) {
			$this->render	= 'ability_or_speciality';
			$player			= Player::get_instance();

			if($ability) {
				$this->assign('types', Item::abilities());
				$this->assign('items', $player->character_theme()->abilities());
				$this->assign('checker_method', 'has_ability');
				$this->assign('checker_field', 'ability_variant_type_id');
				$this->assign('translate_key', 'abilities.');
			} else {
				$this->assign('types', Item::specialities());
				$this->assign('items', $player->character_theme()->specialities());
				$this->assign('checker_method', 'has_speciality');
				$this->assign('checker_field', 'speciality_variant_type_id');
				$this->assign('translate_key', 'specialities.');
			}

			$this->assign('ability', $ability);
			$this->assign('player', $player);
		}

		function train_ability() {
			$this->_train_ability_or_speciality(true);
		}

		function train_speciality() {
			$this->_train_ability_or_speciality();
		}

		private function _train_ability_or_speciality($ability = false) {
			$this->layout			= false;
			$this->as_json			= true;
			$this->json->success	= false;
			$this->render			= false;
			$player					= Player::get_instance();
			$errors					= array();
			$translate_key			= $ability ? 'abilities.learn.' :  'specialities.learn.';

			if(!is_numeric($_POST['id'])) {
				$errors[]	= t($translate_key . 'invalid');
			} else {
				if(!ItemVariantType::find_first('item_type_id=' . ($ability ? 3 : 4) . ' AND id=' . $_POST['id'], array('cache' => true))) {
					$errors[]	= t($translate_key . 'invalid');
				}

				if(($ability && $player->ability_variant_type_id) || ($ability && $player->speciality_variant_type_id)) {
					$errors[]	= t($translate_key . 'already_learned');
				}
			}

			if(!sizeof($errors)) {
				$this->json->success	= true;
				$this->json->message	= t($translate_key . 'success');

				if($ability) {
					$player->ability_variant_type_id	= $_POST['id'];
				} else {
					$player->speciality_variant_type_id	= $_POST['id'];
				}

				$player->save();
			} else {
				$this->json->errors		= $errors;
			}
		}

		function learn_ability() {
			$this->_learn_ability_or_speciality(true);
		}

		function learn_speciality() {
			$this->_learn_ability_or_speciality();
		}

		private function _learn_ability_or_speciality($ability = false) {
			$this->layout			= false;
			$this->as_json			= true;
			$this->json->success	= false;
			$this->render			= 'learn_ability_or_speciality';
			$player					= Player::get_instance();
			$errors					= array();
			$learn_method			= $ability ? 'add_ability' : 'add_speciality';
			$checker_method			= $ability ? 'has_ability' : 'has_speciality';
			$translate_key			= $ability ? 'abilities.learn.' :  'specialities.learn.';
			$type_check				= $ability ? 3 : 4;
			$checker_field			= $ability ? 'ability_variant_type_id' : 'speciality_variant_type_id';

			if(isset($_POST['id']) && is_numeric($_POST['id'])) {
				$item	= Item::find($_POST['id']);
				$item->set_character_theme($player->character_theme());
				$item->apply_variant($player->$checker_field);

				$this->assign('player', $player);
				$this->assign('item', $item);
				$this->assign('translate_key', $translate_key);

				if($item->item_type_id == $type_check) {
					extract($item->has_requirement($player));

					if($has_requirement) {
						if($player->$checker_method($item)) {
							$errors[]	= t($translate_key . 'learned2');
						}
					} else {
						$errors[]	= t($translate_key . 'requirements');
					}
				} else {
					$errors[]	= t($translate_key . 'invalid');
				}
			} else {
				$errors[]	= t($translate_key . 'invalid');
			}

			if(!sizeof($errors)) {
				$this->json->success	= true;

				$player->$learn_method($item);
			} else {
				$this->json->errors		= $errors;
			}
		}
	}