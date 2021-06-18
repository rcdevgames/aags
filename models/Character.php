<?php
	class Character extends Relation {
		static $always_cached	= true;

		function description() {
			return CharacterDescription::find_first('character_id=' . $this->id . ' AND language_id=' . $_SESSION['language_id'], array('cache' => true));
		}

		function anime() {
			return Anime::find($this->anime_id, array('cache' => true));
		}

		function themes() {
			return CharacterTheme::find('character_id=' . $this->id, array('cache' => true));
		}

		function profile_image($path_only = false) {
			$theme	= $this->default_theme();
			$path	= 'profile/' . $this->id . '/' . ($theme ? $theme->theme_code : 'X') . '/1.jpg';

			if($path_only) {
				return $path;
			} else {
				return '<img src="' . image_url($path) . '" alt="' . $this->description()->name . '" />';
			}
		}

		function small_image($path_only = false) {
			$theme	= $this->default_theme();
			$path	= 'criacao/' . $this->id . '/' . ($theme ? $theme->theme_code : 'X') . '/1.jpg';

			if($path_only) {
				return $path;
			} else {
				return '<img src="' . image_url($path) . '" alt="' . $this->description()->name . '" />';
			}
		}

		function default_theme() {
			return CharacterTheme::find_first('is_default=1 AND character_id=' . $this->id, array('cache' => true));
		}

		function tree() {
			$items	= Recordset::query('
				SELECT
					a.item_id

				FROM
					item_descriptions a JOIN
					items b ON b.id=a.item_id

				WHERE
					anime_id=' . $this->anime_id . ' AND
					b.item_type_id = 2 AND
					language_id=' . $_SESSION['language_id'], true);
			$result	= array();

			foreach ($items->result_array() as $item) {
				$result[]	= Item::find($item['item_id']);
			}

			return $result;
		}

		function consumables() {
			$result		= array();
			$items		= Item::find('item_type_id=5', array('cache' => true));
			$anime_id	= $this->anime_id;

			foreach ($items as $instance) {
				$instance->set_anime($anime_id);
				$result[]	= $instance;
			}

			return $result;
		}
	}