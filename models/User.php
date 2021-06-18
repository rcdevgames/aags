<?php
	class User extends Relation {
		static			$paranoid	= true;
		static			$password_field	= 'password';
		private static	$instance	= null;

		function players() {
			return Player::find('user_id=' . $this->id);
		}

		function spend($amount) {
			$this->credits	-= $amount;
			$this->save();
		}

		function earn($amount) {
			$this->credits	+= $amount;
			$this->save();
		}

		function is_theme_bought($theme_id) {
			return UserCharacterTheme::find_first('user_id=' . $this->id . ' AND character_theme_id=' . $theme_id) ? true : false;
		}

		function is_theme_image_bought($theme_image_id) {
			return UserCharacterThemeImage::find_first('user_id=' . $this->id . ' AND character_theme_image_id=' . $theme_image_id) ? true : false;
		}

		static function set_instance($user) {
			User::$instance	= $user;
		}

		static function &get_instance() {
			return User::$instance;
		}
	}
