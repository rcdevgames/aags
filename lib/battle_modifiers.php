<?php
	trait BattleModifiers {
		private	$_modifiers	= null;

		function modifiers() {
			$at	= new stdClass();

			if($this->_modifiers) {
				return $this->_modifiers;
			}

			$at->at_for			= 0;
			$at->at_int			= 0;
			$at->at_res			= 0;
			$at->at_agi			= 0;
			$at->at_dex			= 0;
			$at->at_vit			= 0;

			$at->for_life		= 0;
			$at->for_mana		= 0;
			$at->for_stamina	= 0;
			$at->for_atk		= 0;
			$at->for_def		= 0;
			$at->for_hit		= 0;
			$at->for_init		= 0;
			$at->for_crit		= 0;
			$at->for_inc_crit	= 0;
			$at->for_abs		= 0;
			$at->for_inc_abs	= 0;
			$at->for_prec		= 0;
			$at->for_inti		= 0;
			$at->for_conv		= 0;

			foreach($this->get_modifiers() as $mod) {
				$i					= $mod['instance'];

				if ($i->buff_direction == 'friend') {
					$at->at_for			+= $i->at_for;
					$at->at_int			+= $i->at_int;
					$at->at_res			+= $i->at_res;
					$at->at_agi			+= $i->at_agi;
					$at->at_dex			+= $i->at_dex;
					$at->at_vit			+= $i->at_vit;

					$at->for_life		+= $i->for_life;
					$at->for_mana		+= $i->for_mana;
					$at->for_stamina	+= $i->for_stamina;
					$at->for_atk		+= $i->for_atk;
					$at->for_def		+= $i->for_def;
					$at->for_hit		+= $i->for_hit;
					$at->for_init		+= $i->for_init;
					$at->for_crit		+= $i->for_crit;
					$at->for_inc_crit	+= $i->for_inc_crit;
					$at->for_abs		+= $i->for_abs;
					$at->for_inc_abs	+= $i->for_inc_abs;
					$at->for_prec		+= $i->for_prec;
					$at->for_inti		+= $i->for_inti;
					$at->for_conv		+= $i->for_conv;
				} else {
					$at->at_for			-= $i->at_for;
					$at->at_int			-= $i->at_int;
					$at->at_res			-= $i->at_res;
					$at->at_agi			-= $i->at_agi;
					$at->at_dex			-= $i->at_dex;
					$at->at_vit			-= $i->at_vit;

					$at->for_life		-= $i->for_life;
					$at->for_mana		-= $i->for_mana;
					$at->for_stamina	-= $i->for_stamina;
					$at->for_atk		-= $i->for_atk;
					$at->for_def		-= $i->for_def;
					$at->for_hit		-= $i->for_hit;
					$at->for_init		-= $i->for_init;
					$at->for_crit		-= $i->for_crit;
					$at->for_inc_crit	-= $i->for_inc_crit;
					$at->for_abs		-= $i->for_abs;
					$at->for_inc_abs	-= $i->for_inc_abs;
					$at->for_prec		-= $i->for_prec;
					$at->for_inti		-= $i->for_inti;
					$at->for_conv		-= $i->for_conv;
				}
			}

			$this->_modifiers	= null;

			return $at;
		}

		function get_modifiers() {
			return SharedStore::G($this->build_modifiers_uid(), []);
		}

		function add_modifier($instance) {
			$this->_modifiers			= null;
			$modifiers					= SharedStore::G($this->build_modifiers_uid(), []);
			$modifiers[$instance->id]	= [
				'turns'		=> $instance->cooldown,
				'infinity'	=> false,
				'instance'	=> $instance
			];

			SharedStore::S($this->build_modifiers_uid(), $modifiers);
		}

		function has_modifier($id) {
			$modifiers	= SharedStore::G($this->build_modifiers_uid(), []);

			return in_array($id, array_keys($modifiers));;
		}

		function rotate_modifiers() {
			$this->_modifiers	= null;
			$modifiers			= SharedStore::G($this->build_modifiers_uid(), []);
			$new_modifiers		= [];

			foreach($modifiers as $key => $modifier) {
				if(!$modifier['infinity']) {
					$modifier['turns']--;
				}

				if($modifier['turns'] > 0) {
					$new_modifiers[$key]	= $modifier;
				}
			}

			SharedStore::S($this->build_modifiers_uid(), $new_modifiers);
		}

		function clear_modifiers() {
			$this->_modifiers	= null;
			SharedStore::S($this->build_modifiers_uid(), []);
		}
	}