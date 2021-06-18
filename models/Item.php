<?php
	class Item extends Relation {
		static	$always_cached			= true;
		private	$_character_theme_id	= null;
		private	$_anime_id		= null;
		private	$_variant_id	= null;
		private	$_formula		= null;
		private	$_player		= null;
		private $_player_item	= null;

		public	$req_for_stamina	= 0;
		public	$req_for_mana		= 0;

		function after_assign() {
			$this->formula();
			$this->_make_learn_requirements();
		}

		private function _make_learn_requirements() {
			$this->req_for_stamina	= $this->req_graduation_id;
			$this->req_for_mana		= $this->formula()->consume_mana * 2;
		}

		function set_player(&$instance) {
			$this->_player	=& $instance;
		}

		function set_player_item(&$instance) {
			$this->_player_item	=& $instance;
		}

		function formula($regen = false) {
			if(!$regen && $this->_formula) {
				return $this->_formula;
			}

			$formula						= new stdClass();
			$formula->damage				= 0;
			$formula->defense				= 0;
			$formula->consume_mana			= 0;
			$formula->cooldown				= $this->cooldown;
			$formula->for_inc_crit			= 0;
			$formula->hit_chance			= 0;
			$formula->is_player_item		= $this->_player_item ? true : false;

			if($this->_player && $formula->cooldown) {
				$formula->cooldown	-= $this->_player->attributes()->sum_bonus_cooldown;
			}

			if($formula->cooldown < 0) {
				$formula->cooldown	= 0;
			}

			$level_bonuses					= new stdClass();
			$level_bonuses->for_inc_crit	= 0;
			$level_bonuses->for_mana		= 0;
			$level_bonuses->for_atk			= 0;
			$level_bonuses->for_def			= 0;
			$level_bonuses->for_hit_chance	= 0;
			$level_bonuses->cooldown		= 0;

			if($this->_player) {
				$formula->hit_chance	= $this->_player->for_hit();
			}

			// Level bonus calculations
			if($this->_player_item && $this->_player_item->level > 1) {
				$stats	= $this->_player_item->stats();

				for($f = 2; $f <= $this->_player_item->level; $f++) {
					$where		= ' AND is_generic=' . $this->is_generic; //($this->_character_theme_id ? '0' : '1');
					$where		.= ' AND is_defensive=' . $this->is_defensive;
					$where		.= ' AND is_buff=' . $this->is_buff;
					$where		.= ' AND req_graduation_id=' . $this->req_graduation_id;
					$where		.= ' AND req_player_item_level=' . $f;

					$levels	= ItemLevel::find('1=1 ' . $where, array('cache' => true));

					foreach($levels as $level) {
						extract($level->parse($stats, $this->_player));

						if($ok) {
							if($level->for_inc_crit) {
								$level_bonuses->for_inc_crit	+= $level->for_inc_crit;
							}

							if($level->for_mana) {
								$level_bonuses->for_mana	+= $level->for_mana;
							}

							if($level->for_atk) {
								$level_bonuses->for_atk	+= $level->for_atk;
							}

							if($level->for_def) {
								$level_bonuses->for_def	+= $level->for_def;
							}

							if($level->for_hit_chance) {
								$level_bonuses->for_hit_chance	+= $level->for_hit_chance;
							}

							if($level->cooldown) {
								$level_bonuses->cooldown	+= $level->cooldown;
							}
						}
					}
				}
			}

			$value			= $this->req_level * 3 + $this->cooldown * 2;
			$consume_mana	= $value * 1.5;

			if($this->is_defensive) {
				$formula->defense	= floor($value);
			} else {
				$formula->damage	= floor($value);	
			}

			if($this->item_type_id == 3 || $this->item_type_id == 4) { // Ability and speciality don't use mana
				$formula->consume_mana	= 0;
			} else {
				$formula->consume_mana	= floor($consume_mana);

				if($this->_player) {
					$formula->consume_mana	-= percent($this->_player->attributes()->sum_bonus_mana_consume, $formula->consume_mana);
				}
			}

			$formula->base	= clone $formula;
			$formula->level	= $level_bonuses;

			$formula->damage			+= floor($level_bonuses->for_atk);
			$formula->defense			+= floor($level_bonuses->for_def);
			$formula->hit_chance		+= floor($level_bonuses->for_hit_chance);
			$formula->cooldown			-= floor($level_bonuses->cooldown);
			$formula->consume_mana		-= floor($level_bonuses->for_mana);
			$formula->for_inc_crit		-= floor($level_bonuses->for_inc_crit);

			$this->_formula	= $formula;
			return $this->_formula;
		}

		function set_character_theme($theme) {
			$this->_character_theme_id	= $theme->id;
			$this->set_anime($theme->character()->anime_id);
		}

		function set_anime($id) {
			$this->_anime_id	= $id;
		}

		function anime() {
			if(!$this->_anime_id) {
				return false;
			}

			return Anime::find($this->_anime_id);
		}

		function apply_variant($variant_type_id) {
			$variant	= ItemVariant::find_first('item_variant_type_id=' . $variant_type_id . ' AND item_id=' . $this->id, array('cache' => true));
			$ignore		= array('id', 'item_variant_type_id', 'item_id', 'sorting');

			foreach($variant->get_fields() as $field) {
				if(in_array($field, $ignore)) {
					continue;
				}

				$this->{$field}	= $variant->$field;
			}

			$this->formula(true);
			$this->_variant_id	= $variant_type_id;
		}

		function description($anime_id = null, $language_id = null) {
			/*$anime_id	= $anime_id ? $anime_id : $this->_anime_id;

			if(($this->is_generic && (!$anime_id || in_array($this->item_type_id, [5, 7]))) || in_array($this->id, [112, 113])) {
				return ItemDescription::find_first('item_id=' . $this->id . ' AND anime_id=0 AND language_id=' . $_SESSION['language_id'], array('cache' => true));
			}

			if(!$this->_character_theme_id) {
				if(!$anime_id) {
					throw new Exception("Anime not specified to get ", 1);				
				}

				return ItemDescription::find_first('item_id=' . $this->id . ' AND anime_id=' . $anime_id . ' AND language_id=' . $_SESSION['language_id'], array('cache' => true));
			} else {
				return CharacterThemeItem::find_first('character_theme_id=' . $this->_character_theme_id . ' AND item_id=' . $this->id .' AND language_id=' . $_SESSION['language_id'], array('cache' => true));
			}*/
			return ItemDescription::find_first('item_id=' . $this->id . ' AND language_id=' . $_SESSION['language_id'], ['cache' => true]);
		}

		function descriptions($language_id) {
			return CharacterDescription::find('item_id=' . $this->id . ' AND language_id=' . $_SESSION['language_id'], array('cache' => false));
		}

		function image($path_only = false) {
			$description	= $this->description();
			$base			= 'items';

			if($this->item_type_id == 6) {
				$base	= 'talents';
			}

			if($this->_character_theme_id) {
				$path	= $base . '/' . $this->_anime_id . '/' . $this->_character_theme_id .'/' . $description->image;
			} else {
				if($this->is_generic && in_array($this->item_type_id, [5, 7])) {
					$path	= $base . '/' . $description->image;
				} else {
					$path	= $base . '/' . $this->_anime_id . '/' . $description->image;
				}
			}

			if($path_only) {
				return $path;
			} else {
				return '<img src="' . image_url($path) . '" name="' . $description->name . '" title="' . $description->name . '" />';
			}
		}

		function has_requirement($player) {
			$ok				= true;
			$log			= '<ul class="requirement-list">';
			$error			= '<li class="error"><span class="glyphicon glyphicon-remove"></span> %result</li>';
			$success		= '<li class="success"><span class="glyphicon glyphicon-ok"></span> %result</li>';

			if($this->req_level) {
				$ok		= $this->req_level > $player->level ? false : $ok;
				$log	.= str_replace('%result', t('items.requirements.level', array('level' => $this->req_level)), $this->req_level > $player->level ? $error : $success);
			}

			if($this->req_graduation_id) {
				$player_graduation	= Graduation::find($player->graduation_id);
				$req_graduation		= Graduation::find($this->req_graduation_id);

				$ok		= $req_graduation->sorting > $player_graduation->sorting ? false : $ok;
				$log	.= str_replace('%result', t('items.requirements.graduation', array('graduation' => $req_graduation->description()->name)), $req_graduation->sorting > $player_graduation->sorting ? $error : $success);
			}

			if($this->req_at_for) {
				$ok		= $this->req_at_for > $player->at_for() ? false : $ok;
				$log	.= str_replace('%result', t('items.requirements.at_for', array('count' => $this->req_at_for)), $this->req_at_for > $player->at_for() ? $error : $success);
			}

			if($this->req_at_int) {
				$ok		= $this->req_at_int > $player->at_int() ? false : $ok;
				$log	.= str_replace('%result', t('items.requirements.at_int', array('count' => $this->req_at_int)), $this->req_at_int > $player->at_int() ? $error : $success);
			}

			if(!in_array($this->item_type_id, [5, 6, 7])) {
				if($this->req_for_stamina) {
					$ok		= $this->req_for_stamina > $player->for_stamina() ? false : $ok;
					$log	.= str_replace('%result', t('items.requirements.for_stamina', array('total' => $this->req_for_stamina)), $this->req_for_stamina > $player->for_stamina() ? $error : $success);
				}

				if($this->req_for_mana) {
					$ok		= $this->req_for_mana > $player->for_mana() ? false : $ok;
					$log	.= str_replace('%result', t('items.requirements.for_mana',
							array(
								'total' => $this->req_for_mana,
								'mana'	=> t('formula.for_mana.' . $player->character()->anime_id)
							)
						),
						$this->req_for_mana > $player->for_mana() ? $error : $success
					);
				}
			}

			if($this->item_type_id == 3) {
				$variant	= ItemVariantType::find($this->_variant_id);
				$ok			= $this->_variant_id != $player->ability_variant_type_id ? false : $ok;
				$log		.= str_replace('%result', t('items.requirements.ability', array('name' => $variant->description()->name)), $this->_variant_id != $player->ability_variant_type_id ? $error : $success);
			}

			if($this->item_type_id == 4) {
				$variant	= ItemVariantType::find($this->_variant_id);
				$ok			= $this->_variant_id != $player->ability_variant_type_id ? false : $ok;
				$log		.= str_replace('%result', t('items.requirements.speciality', array('name' => $variant->description()->name)), $this->_variant_id != $player->ability_variant_type_id ? $error : $success);
			}

			return array('has_requirement' => $ok, 'requirement_log' => $log);
		}

		function technique_tooltip() {
			if($this->_character_theme_id) {
				$unique			= t('techniques.types.unique');
				$unique_class	= 'unique';
			} else {
				$unique			= t('techniques.types.generic');
				$unique_class	= 'generic';
			}

			if($this->item_type_id == 3) {
				$type		= t('techniques.types.ability');
				$type_class	= 'ability';
			} elseif($this->item_type_id == 4) {
				$type		= t('techniques.types.speciality');
				$type_class	= 'speciality';
			} elseif($this->item_type_id == 7) {
				$type			= t('techniques.types.weapons');
				$type_class		= 'buff';

				$unique_class	= 'unique';
				$unique			= t('techniques.types.unique');
			} else {
				if($this->is_buff) {
					$type		= t('techniques.types.buff');
					$type_class	= 'buff';
				} else {
					if($this->formula()->defense) {
						$type		= t('techniques.types.defense');
						$type_class	= 'defense';
					} else {
						$type		= t('techniques.types.attack');
						$type_class	= 'attack';
					}
				}
			}

			$assigns	= array(
				'item'			=> $this,
				'description'	=> $this->description(),
				'type'			=> $type,
				'type_class'	=> $type_class,
				'unique'		=> $unique,
				'unique_class'	=> $unique_class,
				'formula'		=> $this->formula()
			);

			return partial('shared/technique_tooltip', $assigns);
		}

		function consumable_tooltip() {
			$assigns	= array(
				'item'			=> $this,
				'description'	=> $this->description()
			);

			return partial('shared/consumable_tooltip', $assigns);
		}

		function technique_level_tooltip() {
			$ok				= true;
			$stats			= $this->_player_item->stats();
			$player_item	= $this->_player_item;
			$bonuses		= array();
			$tooltip		= array();

			if($player_item->level > 1) {
				if($player_item->level > 2) {
					$last_bonus	= $player_item->level - 1;
				} else {
					$last_bonus	= false;
				}

				$where		= ' AND is_generic=' . $this->is_generic; //($this->_character_theme_id ? '0' : '1');
				$where		.= ' AND is_defensive=' . $this->is_defensive;
				$where		.= ' AND is_buff=' . $this->is_buff;
				$where		.= ' AND req_graduation_id=' . $this->req_graduation_id;

				$levels	= ItemLevel::find('1=1 ' . $where, array('cache' => true));

				foreach($levels as $level) {
					if (!isset($bonuses[$level->sorting])) {
						$bonuses[$level->sorting]	= array();
					}

					extract($level->parse($stats, $this->_player));
					$bonus		= '';
					$counter	= 0;
					$need		= 0;
					$have		= 0;

					// Bonuses -->
						if($level->for_inc_crit) {
							$bonus	.= t('techniques.tooltip.level_req.for_inc_crit', array('count' => $level->for_inc_crit));
						}

						if($level->for_mana) {
							$bonus	.= t('techniques.tooltip.level_req.for_mana', array(
								'count' => $level->for_mana,
								'mana'	=> t('formula.for_mana.' . $this->_player->character()->anime_id)
							));
						}

						if($level->for_atk) {
							$bonus	.= t('techniques.tooltip.level_req.for_atk', array('count' => $level->for_atk));
						}

						if($level->for_def) {
							$bonus	.= t('techniques.tooltip.level_req.for_def', array('count' => $level->for_def));
						}

						if($level->for_hit_chance) {
							$bonus	.= t('techniques.tooltip.level_req.for_hit_chance', array('count' => $level->for_hit_chance));
						}

						if($level->cooldown) {
							$bonus	.= t('techniques.tooltip.level_req.cooldown', array('count' => $level->cooldown));
						}
					// <--

					$bonuses[$level->sorting][$level->req_player_item_level]	= array(
						'req'	=> $req,
						'bonus'	=> $bonus,
						'ok'	=> $ok,
						'have'	=> $have,
						'need'	=> $need
					);
				}

				foreach($bonuses as $slot => $bonus) {
					if(!isset($tooltip[$slot])) {
						$tooltip[$slot]	= array();
					}

					$tooltip[$slot]['current']	= $bonus[2];
					$has_last					= false;

					foreach($bonus as $level => $data) {
						if($last_bonus == $level && $data['ok']) {
							$tooltip[$slot]['last']	= $data;
							$has_last				= true;
						}

						if($level > $last_bonus && $has_last) {
							$tooltip[$slot]['current']	= $data;
						}
					}
				}

				//print_r($last_bonus);
				//print_r($tooltip);
			}

			return partial('shared/technique_level_tooltip', array(
				'item'			=> $this,
				'player_item'	=> $player_item,
				'stats'			=> $stats,
				'tooltip'		=> $tooltip
			));
		}

		function talent_tooltip() {
			$assigns	= [
				'item'			=> $this,
				'description'	=> $this->description(),
				'ats'			=> [
					'at_for',
					'at_int',
					'at_res',
					'at_agi',
					'at_dex',
					'at_vit'
				],
				'formulas'		=> [
					'for_atk',
					'for_def',
					'for_hit',
					'for_init',
					'for_crit',
					'for_inc_crit',
					'for_abs',
					'for_inc_abs',
					'for_prec',
					'for_inti',
					'for_conv'
				]
			];

			return partial('shared/talent_tooltip', $assigns);
		}

		function weapon_tooltip() {
			return $this->technique_tooltip();
		}

		function tooltip() {
			if($this->item_type_id == 5) {
				return $this->consumable_tooltip();
			} elseif($this->item_type_id == 6) {
				return $this->talent_tooltip();
			} elseif($this->item_type_id == 7) {
				return $this->weapon_tooltip();
			} else {
				return $this->technique_tooltip();
			}
		}

		function exp_needed_for_level() {
			$rates	= array(
				2	=> array(2400, 3000, 3600, 4200, 4800),
				3	=> array(3400, 4200, 5000, 5800, 6600),
				4	=> array(4400, 5400, 6400, 7400, 8400),
				5	=> array(5400, 6600, 7800, 9000, 10200)
			);

			return $rates[$this->_player_item->level + 1][$this->req_graduation_id - 1];
		}

		static function specialities() {
			return ItemVariantType::find('item_type_id=4', array('cache' => true));
		}

		static function abilities() {
			return ItemVariantType::find('item_type_id=3', array('cache' => true));
		}
	}