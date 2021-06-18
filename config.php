<?php
	define('FW_ENV', 'development');

	$home				= 'home#index';
	//$site_url			= 'http://192.95.32.43/~anime/dev/';
	$site_url			= 'http://localhost';
	$rewrite_enabled	= true;
	$password_salt		= '$2y$B2w223G4kb4m6rlS5c2Vo2';

	$database			= [
		'host'			=> '127.0.0.1',
		'username'		=> 'root',
		'password'		=> '',
		'database'		=> 'aasg_db',
		'connection'	=> 'primary',
		'cache_mode'	=> RECORDSET_SHM,
		'cache_id'		=> 'ALLSTAR_DEV'
	];

	if(!isset($_SESSION['language_id'])) {
		$_SESSION['language_id']	= 1;
	}

	if(!isset($_SESSION['player_id'])) {
		$_SESSION['player_id']	= null;
	}

	if(!isset($_SESSION['loggedin'])) {
		$_SESSION['loggedin']	= false;
	}

	SharedStore::$key_prefix	= 'ALLSTAR_DEV_STORE';
