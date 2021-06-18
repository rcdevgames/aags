<?php
	class UserMailer extends Mailer {
		public $host		= 'mail.animeallstarsgame.com';
		public $port		= 587;
		public $username	= 'contato@animeallstarsgame.com.br';
		public $password	= 'YbcwN9321v68710';
		public $from		= 'contato@animeallstarsgame.com.br';
		public $from_name	= 'Anime All Stars Game';

		function send_join($user) {
			$this->deliver(t('emails.join.subject'), $user->email, render_mailer('user_mailer', 'send_join', array('user' => $user)));
		}

		function send_join_beta($user) {
			$this->deliver(t('emails.join.subject_beta'), $user->email, render_mailer('user_mailer', 'send_join_beta', array('user' => $user)));
		}	

		function password_change($user) {
			$this->deliver(t('emails.password_change.subject'), $user->email, render_mailer('user_mailer', 'password_change', array('user' => $user)));
		}	

		function password_changed($user) {
			$this->deliver(t('emails.password_changed.subject'), $user->email, render_mailer('user_mailer', 'password_changed', array('user' => $user)));
		}

		function ip_lock($user) {
			$this->deliver(t('emails.ip_lock.subject'), $user->email, render_mailer('user_mailer', 'ip_lock', array('user' => $user)));
		}
	}