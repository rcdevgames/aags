<?php
	trait BattleTechniqueLocks {
		function get_technique_locks() {
			return SharedStore::G($this->build_technique_lock_uid(), []);
		}

		function add_technique_lock($instance) {
			$locks					= SharedStore::G($this->build_technique_lock_uid(), []);
			$locks[$instance->id]	= [
				'turns'		=> $instance->formula()->cooldown,
				'infinity'	=> false
			];

			SharedStore::S($this->build_technique_lock_uid(), $locks);
		}

		function has_technique_lock($id) {
			$locks	= SharedStore::G($this->build_technique_lock_uid(), []);

			return in_array($id, array_keys($locks));
		}

		function rotate_technique_locks() {
			$locks		= SharedStore::G($this->build_technique_lock_uid(), []);
			$new_locks	= [];

			foreach ($locks as $key => $lock) {
				if(!$lock['infinity']) {
					$lock['turns']--;
				}

				if($lock['turns'] > 0) {
					$new_locks[$key]	= $lock;
				}
			}

			SharedStore::S($this->build_technique_lock_uid(), $new_locks);
		}

		function clear_technique_locks() {
			SharedStore::S($this->build_technique_lock_uid(), []);
		}
	}