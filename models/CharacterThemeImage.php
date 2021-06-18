<?php
	class CharacterThemeImage extends Relation {
		static	$always_cached	= true;

		function character_theme() {
			return CHaracterTheme::find($this->character_theme_id, array('cache' => true));
		}

		function profile_image($path_only = false) {
			$theme		= $this->character_theme();
			$character	= $theme->character();
			$path		= 'profile/' . $character->id . '/' . $theme->theme_code . '/' . $this->image . '.jpg';

			if($path_only) {
				return $path;
			} else {
				return '<img src="' . image_url($path) . '" alt="' . $character->description()->name . '" />';
			}
		}
	}