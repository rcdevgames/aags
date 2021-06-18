<?php
	trait AttributeManager {
		function at_for() {
			return $this->character()->at_for + $this->at_for + $this->attributes()->sum_at_for + $this->attributes()->at_for + $this->modifiers()->at_for;
		}
		
		function at_int() {
			return $this->character()->at_int + $this->at_int + $this->attributes()->sum_at_int + $this->attributes()->at_int + $this->modifiers()->at_int;
		}
		
		function at_res() {
			return $this->character()->at_res + $this->at_res + $this->attributes()->sum_at_res + $this->attributes()->at_res + $this->modifiers()->at_res;
		}
		
		function at_dex() {
			return $this->character()->at_dex + $this->at_dex + $this->attributes()->sum_at_dex + $this->attributes()->at_dex + $this->modifiers()->at_dex;
		}
		
		function at_agi() {
			return $this->character()->at_agi + $this->at_agi + $this->attributes()->sum_at_agi + $this->attributes()->at_agi + $this->modifiers()->at_agi;
		}
		
		function at_vit() {
			return $this->character()->at_vit + $this->at_vit + $this->attributes()->sum_at_vit + $this->attributes()->at_vit + $this->modifiers()->at_vit;
		}

		function for_life($max = false) {
			$total	= round(pow($this->at_vit(), .9) * 20);
			
			//Incremento por Level
			$total += (20 * $this->level) + $this->attributes()->sum_for_life + $this->modifiers()->for_life;

			if ($max) {
				return $total;
			} else {
				return $total - $this->less_life;
			}
		}

		function for_mana($max = false) {
			$total	= round(pow($this->at_int(), .8) * 30 + pow($this->at_vit(), .8) * 10);
			
			//Incremento por Level
			$total += (1000 * $this->level) + $this->attributes()->sum_for_mana + $this->modifiers()->for_mana;
			
			if ($max) {
				return $total;
			} else {
				return $total - $this->less_mana;
			}
		}

		function for_stamina($max = false) {
			$total	= 100 + $this->attributes()->sum_for_stamina + $this->attributes()->sum_bonus_stamina_max + $this->modifiers()->for_stamina;

			if($max) {
				return $total;
			} else {
				return $total - $this->less_stamina;
			}
		}

		function for_atk() {
			return round(pow($this->at_for(), .8) * 2 + pow($this->at_agi(), .8)) + $this->attributes()->sum_for_atk + $this->modifiers()->for_atk;
		}

		function for_def() {
			return round(pow($this->at_res(), .8) * 1.5 + pow($this->at_dex(), .8)) + $this->attributes()->sum_for_def + $this->modifiers()->for_def;
		}

		function for_hit() {
			return round(pow($this->at_dex(), .8) * 1.5) + $this->attributes()->sum_for_hit + $this->modifiers()->for_hit;
		}

		function for_crit() {
			return $this->attributes()->sum_for_crit + $this->modifiers()->for_crit;
		}

		function for_crit_inc() {
			return  $this->attributes()->sum_for_inc_crit + $this->modifiers()->for_inc_crit;
		}

		function for_abs() {
			return $this->attributes()->sum_for_abs + $this->modifiers()->for_abs;
		}

		function for_abs_inc() {
			return $this->attributes()->sum_for_inc_abs + $this->modifiers()->for_inc_abs;
		}

		function for_prec() {
			return round(pow($this->at_int(), .6), 2) + $this->attributes()->sum_for_prec + $this->modifiers()->for_prec;
		}

		function for_inti() {
			return round(pow($this->at_for(), .6), 2) + $this->attributes()->sum_for_inti + $this->modifiers()->for_inti;
		}

		function for_conv() {
			return round(pow($this->at_res(), .6), 2) + $this->attributes()->sum_for_conv + $this->modifiers()->for_conv;
		}

		function for_init() {
			return round(pow($this->at_agi(), .7), 2) + $this->attributes()->sum_for_init + $this->modifiers()->for_init;
		}
	}