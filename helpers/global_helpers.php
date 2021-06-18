<?php
	function between($value, $start, $end) {
		return $value >= $start && $value <= $end;
	}

	function enqueue_global_message() {

	}

	function enqueue_player_message() {
		
	}

	function clear_keys() {
		$_SESSION['keys']	= array();
	}

	function set_key($name, $value) {
		$_SESSION['keys'][$name]	= base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $_SESSION['base_key'], $v, MCRYPT_MODE_CBC, $_SESSION['base_key']));
	}

	function get_key($name) {
		return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $_SESSION['base_key'], base64_decode($v), MCRYPT_MODE_CBC, $_SESSION['base_key']), "\0");		
	}

	function percent($p, $v) {
		if($p == 0) return 0;
	
		return round($v * ($p / 100), 0, PHP_ROUND_HALF_UP);
	}

	function percentf($p, $v) {
		if($p == 0) return 0;
	
		return $v * ($p / 100);
	}

	function as_percent($base, $value) {
		return @($base * 100 / $value);
	}

	function now($mysql_format = false) {
		return $mysql_format ? date('Y-m-d H:i:s') : strtotime('+0 minute');
	}

	function get_time_difference( $start, $end ) {
		if(!is_numeric($start)) {
			$uts['start']	=	strtotime( $start );
		} else {
			$uts['start']	= $start;	
		}
		
		if(!is_numeric($end)) {
			$uts['end']	=	strtotime( $end );
		} else {
			$uts['end']	=	$end;
		}
		
		if($uts['start'] > $uts['end']) {
			return array(
				'days'		=> 0,
				'hours'		=> 0,
				'minutes'	=> 0,
				'seconds'	=> 0
			);
		}
		
		if( $uts['start']!==-1 && $uts['end']!==-1 ) {
			if( $uts['end'] >= $uts['start'] ) {
				$diff	=	$uts['end'] - $uts['start'];
				if( $days = intval((floor($diff/86400))) )
					$diff = $diff % 86400;
				if( $hours = intval((floor($diff/3600))) )
					$diff = $diff % 3600;
				if( $minutes = intval((floor($diff/60))) )
					$diff = $diff % 60;
				$diff	=	intval( $diff );			
				return( array('days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );
			} else {
				trigger_error( "Ending date/time is earlier than the start date/time", E_USER_WARNING );
			}
		} else {
			trigger_error( "Invalid date/time data detected", E_USER_WARNING );
		}
		return( false );
	}