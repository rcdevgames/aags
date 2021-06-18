<?php
	class PlayerItem extends Relation {
		private	$_player	= null;

		function after_create() {
			$stat					= new PlayerItemStat();
			$stat->player_item_id	= $this->id;
			$stat->save();
		}

		function player() {
			if(!$this->_player) {
				$instance	= Player::get_instance();

				if($this->player_id == $instance->id) {
					$this->_player	=& $instance;
				} else {
					$instance		= Player::find($this->player_id);
					$this->_player	=& $instance;
				}

				return $instance;				
			}

			return $this->_player;
		}

		function item() {
			$this->player();

			$item	= Item::find($this->item_id);
			$item->set_player($this->_player);
			$item->set_player_item($this);
			$item->formula(true);

			if($item->is_generic || in_array($item->item_type_id, [5, 7])) {
				$item->set_anime($this->player()->character()->anime_id);
			} else {
				$item->set_character_theme($this->player()->character_theme());
			}

			return $item;
		}

		function stats() {
			return PlayerItemStat::find_first('player_item_id=' . $this->id);
		}
	}