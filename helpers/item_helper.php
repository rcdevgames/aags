<?php
	function buff_properties() {
		$properties	= array();
		$ats	= array(
			'at_for',
			'at_int',
			'at_res',
			'at_agi',
			'at_dex',
			'at_vit'
		);

		$formulas	= array(
			'for_life',
			'for_mana',
			'for_stamina',
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
		);

		$images		= array(
			'for_hit'		=> 'for_prec',
			'for_inc_abs'	=> 'for_abs',
			'for_inc_crit'	=> 'for_crit'
		);

		$formatter	= function ($item, $prop) {
			$percent	= array(
				'for_crit',
				'for_inc_crit',
				'for_abs',
				'for_inc_abs',
				'for_life',
				'for_mana',
				'for_stamina'
			);

			if(in_array($prop->field, $percent)) {
				return $item->{$prop->field} . '%';
			} else {
				return $item->{$prop->field};
			}
		};

		foreach($ats as $at) {
			$object				= new stdClass();
			$object->name		= t('at.' . $at);
			$object->image		= 'icons/' . $at . '.png';
			$object->field		= $at;
			$object->formatter	= $formatter;

			$properties[]	= $object;
		}

		foreach($formulas as $formula) {
			$object				= new stdClass();
			$object->name		= t('formula.' . $formula . ($formula == 'for_mana' ? '.' . Player::get_instance()->character()->anime_id : ''));
			$object->image		= 'icons/' . (isset($images[$formula]) ? $images[$formula] : $formula) . '.png';
			$object->field		= $formula;
			$object->formatter	= $formatter;

			$properties[]	= $object;
		}

		return $properties;
	}