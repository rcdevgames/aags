<?php
	class FakePlayeritem {
		private $player;
		private	$item;

		public	$quantity	= 0;
		public	$level		= 1;

		function __construct($id, $player) {
			$this->id		= $id;
			$this->player	= $player;

			$item			= Item::find($id);
			$item->set_player($this->_player);
			$item->set_player_item($this);
			$item->formula(true);

			if($item->is_generic || in_array($item->item_type_id, [5, 7])) {
				$item->set_anime($player->character()->anime_id);
			} else {
				$item->set_character_theme($player->character_theme());
			}

			if(in_array($id, [112, 113])) {
				$item->req_level	= 0;
			}

			$this->item	= $item;
		}

		function item() {
			return $this->item;
		}

		function stats() {
			$stats							= new stdClass();
			$stats->exp						= 0;
			$stats->uses					= 0;
			$stats->use_with_precision		= 0;
			$stats->use_low_stat			= 0;
			$stats->kills					= 0;
			$stats->kills_with_crit			= 0;
			$stats->kills_with_precision	= 0;
			$stats->full_defenses			= 0;

			return $stats;
		}
	}