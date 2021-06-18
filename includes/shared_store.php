<?php
	class SharedStore {
		static	$key_prefix = "";

		private static function get_store_path() {
			if(substr(PHP_OS, 0, 3) == 'WIN') {
				$path	= sys_get_temp_dir() . DIRECTORY_SEPARATOR . SharedStore::$key_prefix . DIRECTORY_SEPARATOR;
				
				if(!is_dir($path)) {
					mkdir($path);
				}
				
				return $path;
			} else {
				return '/dev/shm/';
			}
		}
		
		static function S($key, $v = NULL) {
			$file	= SharedStore::get_store_path() . SharedStore::$key_prefix . md5($key);

			file_put_contents($file, serialize($v));
			@chmod($file, 0777);
		}
		
		static function G($key, $default = NULL) {
			$mem = @file_get_contents(SharedStore::get_store_path() . SharedStore::$key_prefix . md5($key));
			
			return $mem != NULL ? unserialize($mem) : $default;
		}
		
		static function D($key, $t = 10) {
			@unlink(SharedStore::get_store_path() . SharedStore::$key_prefix . md5($key));
		}
	}
