<?php
	class CharactersController extends Controller {
		function create() {
			if($_POST) {
				$this->layout			= false;
				$this->as_json			= true;
				$this->render			= false;
				$this->json->success	= false;
				$errors					= array();

				if(!isset($_POST['name']) || (isset($_POST['name']) && !preg_match('/^[\dA-Z]+$/i', $_POST['name']))) {
					$errors[]	= t('characters.create.errors.invalid_name');
				} else {
					if(strlen($_POST['name']) > 14) {
						$errors[]	= t('characters.create.errors.name_length_max');
					}

					if(strlen($_POST['name']) < 6) {
						$errors[]	= t('characters.create.errors.name_length_min');
					}

					if(Player::find('name="' . addslashes($_POST['name']) . '"')) {
						$errors[]	= t('characters.create.errors.existent');
					}
				}

				if(!isset($_POST['character_id']) || (isset($_POST['character_id']) && !Character::includes($_POST['character_id']))) {
					$errors[]	= t('characters.create.errors.invalid_character');
				}

				if(!sizeof($errors)) {
					$this->json->success	= true;
					$theme					= Character::find($_POST['character_id'])->default_theme();

					$player								= new Player();
					$player->user_id					= $_SESSION['user_id'];
					$player->name						= $_POST['name'];
					$player->character_id				= $_POST['character_id'];
					$player->character_theme_id			= $theme->id;
					$player->character_theme_image_id	= $theme->images()[0]->id;
					$player->save();
				} else {
					$this->json->errors	= $errors;
				}
			} else {
				$animes	= Anime::all();

				$this->assign('animes', $animes);
				$this->assign('attributes', array(
					'at_for'	=> t('at.at_for'),
					'at_int'	=> t('at.at_int'),
					'at_res'	=> t('at.at_res'),
					'at_agi'	=> t('at.at_agi'),
					'at_dex'	=> t('at.at_dex'),
					'at_vit'	=> t('at.at_vit')
				));
			}
		}

		function select() {
			if($_POST) {
				$this->layout			= false;
				$this->as_json			= true;
				$this->render			= false;
				$this->json->success	= false;
				$errors					= array();

				if(!isset($_POST['id']) || (isset($_POST['id']) && !is_numeric($_POST['id']))) {
					$errors[]	= t('characters.select.errors.invalid');
				} else {
					$player	= Player::find($_POST['id']);

					if($player->user_id != $_SESSION['user_id']) {
						$errors[]	= t('characters.select.errors.user_match');
					}
				}

				if(!sizeof($errors)) {
					$this->json->success	= true;
					$_SESSION['player_id']	= $player->id;

					// Clear all session key data
					clear_keys();
					$_SESSION['base_key']	= uniqid(uniqid(), true);
				} else {
					$this->json->errors	= $errors;
				}
			} else {
				$this->assign('players', Player::find('user_id=' . $_SESSION['user_id']));				
			}
		}

		function remove($id	= null, $key = null) {
			if(is_numeric($id) && $key) {
				$player	= Player::find_first('id = ' . $id . ' AND remove_key="' . addslashes($key) . '"');
				$errors	= [];

				if(!$player) {
					$errors[]	= t('characters.remove.not_found');
				} else {
					if($player->user_id != $_SESSION['user_id']) {
						$errors[]	= t('characters.remove.same_user');
					}

					if($player->id == $_SESSION['player_id']) {
						$errors[]	= t('characters.remove.same_player');
					}
				}

				if(!sizeof($errors)) {
					$player->destroy();

					redirect_to('characters#select?deleted_ok');
				} else {
					$messages	= [];

					foreach ($errors as $error) {
						$messages[]	= '<li>' . $error . '</li>';
					}

					$this->assign('messages', '<ul>' . implode('', $messages) . '</ul>');
					$this->render	= 'remove_error';
				}
			} else {
				$this->layout			= false;
				$this->as_json			= true;
				$this->render			= false;
				$this->json->success	= false;
				$errors					= array();

				if(isset($_POST['id']) && is_numeric($_POST['id'])) {
					$player	= Player::find($_POST['id']);

					if(!$player) {
						$errors[]	= t('characters.remove.not_found');
					} else {
						if($player->user_id != $_SESSION['user_id']) {
							$errors[]	= t('characters.remove.same_user');
						}

						if($player->id == $_SESSION['player_id']) {
							$errors[]	= t('characters.remove.same_player');
						}
					}
				} else {
					$errors[]	= t('characters.remove.invalid');				
				}

				if(!sizeof($errors)) {
					$this->json->success	= true;
					$player->remove_key		= uniqid();
					$player->save();

					CharacterMailer::dispatch('character_deleted', [User::get_instance(), $player]);
				} else {
					$this->json->errors	= $errors;
				}
			}
		}

		function status() {
			$player		= Player::get_instance();
			$formulas	= array(
				'for_atk'	=> t('formula.for_atk'),
				'for_def'	=> t('formula.for_def'),
				'for_crit'	=> t('formula.for_crit'),
				'for_abs'	=> t('formula.for_abs'),
				'for_prec'	=> t('formula.for_prec'),
				'for_inti'	=> t('formula.for_inti'),
				'for_conv'	=> t('formula.for_conv'),
				'for_init'	=> t('formula.for_init')
			);

			$attributes	= array(
				'at_for'	=> t('at.at_for'),
				'at_int'	=> t('at.at_int'),
				'at_res'	=> t('at.at_res'),
				'at_agi'	=> t('at.at_agi'),
				'at_dex'	=> t('at.at_dex'),
				'at_vit'	=> t('at.at_vit')
			);

			$this->assign('player', $player);
			$this->assign('attributes', $attributes);

			$this->assign('formulas', $formulas);

			$max	= 0;
			$max2	= 0;

			foreach ($formulas as $_ => $formula) {
				$value	= $player->{$_}();

				if($value > $max) {
					$max	= $value;
				}
			}

			foreach ($attributes as $_ => $attribute) {
				$value	= $player->{$_}();

				if($value > $max2) {
					$max2	= $value;
				}
			}

			$this->assign('max', $max);
			$this->assign('max2', $max2);
		}

		function list_images() {
			$this->layout	= false;
			$player			= Player::get_instance();

			if($_POST) {
				$this->as_json			= true;
				$this->render			= false;
				$this->json->success	= false;
				$errors					= array();

				if(is_numeric($_POST['id'])) {
					$image	= CharacterThemeImage::find($_POST['id']);

					if(!$image) {
						$errors[]	= t('character.status.change_image.errors.invalid');
					} else {
						if($image->character_theme_id != $player->character_theme_id) {
							$errors[]	= t('character.status.change_image.errors.theme');
						}

						if($image->character_theme()->character_id != $player->character_id) {
							$errors[]	= t('character.status.change_image.errors.belongs');
						}
					}
				} else {
					$errors[]	= t('character.status.change_image.errors.invalid');
				}

				if(!sizeof($errors)) {
					$this->json->success				= true;
					$player->character_theme_image_id	= $_POST['id'];
					$player->save();
				} else {
					$this->json->errors	= $errors;
				}
			} else {
				$this->assign('images', $player->character_theme()->images());
			}
		}

		function list_themes() {
			$this->layout	= false;
			$player			= Player::get_instance();
			$user			= User::get_instance();

			if($_POST) {
				$this->as_json			= true;
				$this->render			= false;
				$this->json->success	= false;
				$errors					= array();

				if(isset($_POST['theme']) && is_numeric($_POST['theme'])) {
					$theme	= CharacterTheme::find($_POST['theme']);

					if(!$theme) {
						$errors[]	= t('characters.themes.errors.invalid');
					} else {
						if($theme->character()->id != $player->character()->id) {
							$errors[]	= t('characters.themes.errors.character');
						}

						if(isset($_POST['buy'])) {
							if($theme->price_vip || $theme->price_currency) {
								if($theme->price_vip && $theme->price_vip > $user->credits) {
									$errors[]	= t('characters.themes.errors.enough_credits');
								}

								if($theme->price_currency && $theme->price_currency > $player->currency) {
									$errors[]	= t('characters.themes.errors.enough_currency', array('currency' => t('currencies.' . $player->character()->anime_id)));
								}
							}
						} elseif($_POST['use']) {
							if(!$theme->is_default && !$user->is_theme_bought($_POST['theme'])) {
								$errors[]	= t('characters.themes.errors.not_bought');
							}
						} else {
							$errors[]	= t('characters.themes.errors.operation');
						}
					}
				} else {
					$errors[]	= t('characters.themes.errors.invalid');
				}					

				if(!sizeof($errors)) {
					$this->json->success	= true;

					if(isset($_POST['buy'])) {
						$user_theme						= new UserCharacterTheme();
						$user_theme->user_id			= $user->id;
						$user_theme->character_theme_id	= $_POST['theme'];
						$user_theme->price_vip			= $theme->price_vip;
						$user_theme->price_currency		= $theme->price_currency;						
						$user_theme->save();

						$image								= $theme->first_image();
						$player->character_theme_id			= $theme->id;
						$player->character_theme_image_id	= $image->id;
						$player->save();

						if($theme->price_vip) {
							$user->spend($theme->price_vip);
						}

						if($theme->price_currency) {
							$player->spend($theme->price_currency);
						}
					} elseif($_POST['use']) {
						$image								= $theme->first_image();
						$player->character_theme_id			= $theme->id;
						$player->character_theme_image_id	= $image->id;
						$player->save();
					}
				} else {
					$this->json->errors	= $errors;
				}
			} else {
				$this->assign('user', $user);

				if(isset($_GET['show_only'])) {
					if(isset($_GET['character']) && is_numeric($_GET['character'])) {
						$this->assign('player', false);
						$this->assign('themes', CharacterTheme::find('character_id=' . $_GET['character']));
						$this->assign('character', Character::find($_GET['character']));
					} else {
						$this->denied	= true;
					}
				} else {
					$this->assign('player', $player);
					$this->assign('character', $player->character());
					$this->assign('themes', CharacterTheme::find('character_id=' . $player->character_id));
				}
			}			
		}

		function talents() {
			$items	= Item::find("item_type_id=6 ORDER BY req_level ASC");
			$player	= Player::get_instance();
			$list	= [];

			if($_POST) {
				$this->as_json			= true;
				$this->json->success	= false;
				$errors					= [];

				if($player->has_item($_POST['item_id'])) {
					$errors[]	= t('characters.talents.errors.already');
				} else {
					if(!is_numeric($_POST['item_id'])) {
						$errors[]	= t('characters.talents.errors.invalid');
					} else {
						$item	= Item::find($_POST['item_id']);

						if($item->item_type_id != 6) {
							$errors[]	= t('characters.talents.errors.invalid');
						} else {
							$levels_learned	= [];

							foreach ($player->learned_talents() as $talent) {
								$levels_learned[$talent->item()->req_level]	= true;
							}

							if(isset($levels_learned[$item->req_level])) {
								$errors[]	= t('characters.talents.errors.tree_level');
							}

							$reqs	= $item->has_requirement($player);

							if(!$reqs['has_requirement']) {
								$errors[]	= t('characters.talents.errors.requirements');
							}
						}
					}
				}

				if (!sizeof($errors)) {
					$this->json->success	= true;

					$player->add_talent($item);
				} else {
					$this->json->messages	= $errors;
				}
				
			} else {
				foreach($items as $item) {
					if(!isset($list[$item->req_level])) {
						$list[$item->req_level]	= [];
					}

					$item->set_anime($player->character()->anime_id);

					$list[$item->req_level][]	= $item;
				}

				$this->assign('list', $list);
				$this->assign('player', $player);
			}
		}

		function next_level() {
			$player	=& Player::get_instance();

			if($_POST) {
				$player->exp				-= $player->level_exp();
				$player->level_screen_seen	= 1;
				$player->level++;
				$player->save();

				redirect_to('characters#status');
			} else {
				$this->assign('player', $player);
			}
		}

		function inventory() {
			$ids			= [5, 7];
			$consumables	= [5];
			$player			=& Player::get_instance();
			$items			= [];
			$errors			= [];
			$results		= Recordset::query('
				SELECT
					a.id

				FROM
					player_items a JOIN items b ON b.id=a.item_id

				WHERE
					b.item_type_id IN(' . implode(',', $ids) . ')
					AND a.player_id=' . $player->id);

			foreach ($results->result_array() as $result) {
				$items[]	= PlayerItem::find($result['id']);
			}

			if($_POST) {
				$this->as_json	= true;

				if(!isset($_POST['item']) || (isset($_POST['item']) && !is_numeric($_POST['item']))) {
					$errors[]	= t('characters.inventory.errors.invalid');
				} else {
					if($player->has_item($_POST['item'])) {
						$player_item	= $player->get_item($_POST['item']);
						$item			= $player_item->item();

						if(!in_array($item->item_type_id, $consumables)) {
							$errors[]	= t('characters.inventory.errors.allowed');
						}
					} else {
						$errors[]	= t('characters.inventory.errors.existent');
					}
				}

				if(!sizeof($errors)) {
					switch($item->item_type_id) {
						case 5:
							$player->less_life				-= $item->for_life;
							$player->less_mana				-= $item->for_mana;
							$player->less_stamina			-= $item->for_stamina;

							if($player->less_life <= 0) {
								$player->less_life	= 0;
							}

							if($player->less_mana <= 0) {
								$player->less_mana	= 0;
							}

							if($player->less_stamina <= 0) {
								$player->less_stamina	= 0;
							}

							$player->save();

							break;
					}

					$this->json->life			= $player->for_life();
					$this->json->max_life		= $player->for_life(true);
					$this->json->mana			= $player->for_mana();
					$this->json->max_mana		= $player->for_mana(true);
					$this->json->stamina		= $player->for_stamina();
					$this->json->max_stamina	= $player->for_stamina(true);

					if($player_item->quantity - 1 <= 0) {
						$player_item->destroy();

						$this->json->delete	= true;
					} else {
						$player_item->quantity--;
						$player_item->save();

						$this->json->quantity	= $player_item->quantity;
					}

					$this->json->success	= true;
				} else {
					$this->json->messages	= $errors;
				}
			} else {
				$this->layout	= false;

				$this->assign('player', $player);
				$this->assign('player_items', $items);
				$this->assign('consumables', $consumables);
			}
		}
	}