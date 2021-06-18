<?php
	class CharacterTheme extends Relation {
		static	$always_cached	= true;

		function anime() {
			return $this->character()->anime();
		}

		function character() {
			return Character::find($this->character_id, array('cache' => true));
		}

		function description() {
			return CharacterThemeDescription::find_first('character_theme_id=' . $this->id, array('cache' => true));
		}

		function background_image($path_only = false) {
			$character	= $this->character();
			$path		= 'backgrounds/' . $character->id . '/' . $this->theme_code . '/1.jpg';

			if($path_only) {
				return $path;
			} else {
				return '<img src="' . image_url($path) . '" alt="' . $character->description()->name . '" />';
			}
		}

		function header_image($path_only = false) {
			$character	= $this->character();
			$path		= 'headers/' . $character->id . '/' . $this->theme_code . '/1.jpg';

			if($path_only) {
				return $path;
			} else {
				return '<img src="' . image_url($path) . '" alt="' . $character->description()->name . '" />';
			}
		}

		function profile_image($path_only = false) {
			$character	= $this->character();
			$path		= 'profile/' . $character->id . '/' . $this->theme_code . '/1.jpg';

			if($path_only) {
				return $path;
			} else {
				return '<img src="' . image_url($path) . '" alt="' . $character->description()->name . '" />';
			}
		}

		function images() {
			return CharacterThemeImage::find('character_theme_id=' . $this->id, array('cache' => true));
		}

		function first_image() {
			return $this->images()[0];
		}

		function attacks($unique = false) {
			$anime_id	= $this->character()->anime_id;
			$items		= Recordset::query('
				SELECT
					a.item_id

				FROM
					character_theme_items a JOIN
					items b ON b.id=a.item_id

				WHERE
					character_theme_id=' . $this->id . ' AND
					b.item_type_id = 1 AND
					language_id=' . $_SESSION['language_id'] . '

				ORDER BY b.req_level ASC
				', true);
			$result	= array();

			foreach ($items->result_array() as $item) {
				$instance	= Item::find($item['item_id'], array('cache' => true));
				$instance->set_character_theme($this);

				$result[]	= $instance;
			}

			if (!$unique) {
				$items	= Recordset::query('
					SELECT
						a.item_id

					FROM
						item_descriptions a JOIN
						items b ON b.id=a.item_id

					WHERE
						a.anime_id=' . $anime_id . ' AND
						b.item_type_id = 1 AND
						b.is_generic = 1 AND
						language_id=' . $_SESSION['language_id'], true);

				foreach ($items->result_array() as $item) {
					$instance	= Item::find($item['item_id'], array('cache' => true));
					$instance->set_anime($anime_id);

					$result[]	= $instance;
				}
			}

			$final	= array();
			$inc	= 0.001;

			foreach($result as $item) {
				$index			= $item->req_level + $inc;
				$final[$index]	= $item;

				$inc	+= 0.001;
			}

			ksort($final);

			return $final;
		}

		private function ability_or_speciality($type) {
			$items	= Recordset::query('
				SELECT
					a.item_id

				FROM
					character_theme_items a JOIN
					items b ON b.id=a.item_id

				WHERE
					character_theme_id=' . $this->id . ' AND
					b.item_type_id = ' . $type . ' AND
					language_id=' . $_SESSION['language_id'], true);
			$result	= array();

			foreach ($items->result_array() as $item) {
				$instance	= Item::find($item['item_id'], array('cache' => true));
				$instance->set_character_theme($this);

				$result[]	= $instance;
			}

			return $result;
		}

		function abilities() {
			return $this->ability_or_speciality(3);
		}

		function specialities() {
			return $this->ability_or_speciality(4);
		}

		function weapons() {
			$result		= array();
			$items		= Item::find('item_type_id=7', array('cache' => true));
			$anime_id	= $this->character()->anime_id;

			foreach ($items as $instance) {
				$instance->set_anime($anime_id);
				$result[]	= $instance;
			}

			return $result;
		}
	}