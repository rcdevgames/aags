<?php
	class CharacterMailer extends Mailer {
		public $host		= 'mail.animeallstarsgame.com';
		public $port		= 587;
		public $username	= 'contato@animeallstarsgame.com.br';
		public $password	= 'YbcwN9321v68710';
		public $from		= 'contato@animeallstarsgame.com.br';
		public $from_name	= 'Anime All Stars Game';

		function character_deleted($user, $character) {
			$this->deliver(t('emails.character_deleted.subject'), $user->email, render_mailer('character_mailer', 'character_deleted', ['user' => $user, 'character' => $character]));
		}
	}