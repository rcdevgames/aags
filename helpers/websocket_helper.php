<?php
	function node_string_encrypt($data) {
		$key	= 'LB6CK2F23DXsNr5I';
		$iv		= '5yP010C616Kg2328';

		$blocksize	= 16;
		$pad		= $blocksize - (strlen($data) % $blocksize);
		$data		= $data . str_repeat(chr($pad), $pad);

		return bin2hex(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, $iv));
	}
