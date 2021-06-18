<?php
	class Anime extends Relation {
		static	$always_cached	= true;

		public function description() {
			return AnimeDescription::find_first('anime_id=' . $this->id . ' AND language_id=' . $_SESSION['language_id'], array('cache' => true));
		}

		public function characters() {
			return Character::find('anime_id=' . $this->id, array('cache' => true));
		}
	}