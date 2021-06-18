<?php
	class Locale {
		private	static $strings	= array();
		private static $parsed	= array();

		static function initialize() {
			$path		= dirname(__FILE__);
			$cache_root	= ROOT . '/cache/';
			$cache_path	= ROOT . '/cache/yaml/';

			if(!is_dir($cache_path)) {
				if(is_writable(realpath($cache_root))) {
					mkdir($cache_path);
				} else {
					throw new Exception("Cache path isn't writable, change the permissions", 1);
				}
			}

			if(file_exists($path . '/../locales')) {
				$files				= glob($path . '/../locales/*.yml');
				$cache_write_data	= array();

				foreach ($files as $file) {
					$is_cached	= true;
					$cache_file	= $cache_path . md5(basename($file)) . '.data';
					$cache_date	= $cache_path . md5(basename($file)) . '.ts';

					if(file_exists($cache_file)) {
						if(file_get_contents($cache_date) != filemtime($file)) {
							$is_cached	= false;
						}
					} else {
						$is_cached	= false;
					}

					if(!$is_cached) {
						$data	= @yaml_parse_file($file);
	 
						if($data === false) {
							echo "Error found when parsing translation file:\n\n";
							yaml_parse_file($file);
							die();
						}

						$header	= key($data);
						$lid	= Language::find_first('header="' . $header . '"', array('cache' => true));

						if(!isset(Locale::$strings[$lid->id])) {
							Locale::$strings[$lid->id]	= array();
							Locale::$parsed[$lid->id]	= array();
						}

						Locale::$strings[$lid->id]	= array_merge(Locale::$strings[$lid->id], $data[$header]);

						$parsed			= array();
						
						if(!function_exists('_locale_cb')) {
							function _locale_cb($items, &$parsed, $level = '') {
								foreach($items as $_ => $item) {
									if(is_array($item)) {
										_locale_cb($item, $parsed, $level . $_ . '.');
									} else {
										$parsed[$level . $_]	= $item;
									}
								}
							};
						}
						
						_locale_cb(Locale::$strings[$lid->id], $parsed);

						Locale::$parsed[$lid->id]	= array_merge(Locale::$parsed[$lid->id], $parsed);

						file_put_contents($cache_date, filemtime($file));
						file_put_contents($cache_file, serialize(Locale::$parsed));

						$json	= new stdClass();

						foreach (Locale::$parsed as $tkey => $translations) {
							$loc			= Language::find($tkey)->header;
							$json->$loc	= new stdClass();

							foreach ($translations as $key => $translation) {
								$json->$loc->$key	= $translation;
							}
						}

						$js	= file_get_contents(ROOT . '/includes/core_assets/i18n.js');
						$js	.= "\n\nI18n.translations = " . json_encode($json) . "\n\nI18n.default_locale=\"" .  Language::find($_SESSION['language_id'])->header . "\";";

						file_put_contents(ROOT . '/assets/js/i18n.js', $js);
					} else {
						$cache_read_data	= unserialize(file_get_contents($cache_file));

						foreach($cache_read_data as $lid_key => $parsed_data) {
							if(!isset(Locale::$parsed[$lid_key])) {
								Locale::$parsed[$lid_key]	= [];
							}

							Locale::$parsed[$lid_key]	= array_merge(Locale::$parsed[$lid_key], $parsed_data);
						}
					}
				}
			}
		}

		static function translate($path, $assigns = array(), $lid = null) {
			if(is_null($lid)) {
				$lid	= $_SESSION['language_id'];
			}

			$parsed	= Locale::$parsed[$lid];

			if(isset($parsed[$path])) {
				foreach($assigns as $key => $value) {
					$parsed[$path]	= str_replace('#{' . $key . '}', $value, $parsed[$path]);
				}

				return $parsed[$path];
			} else {
				return false;
			}
		}
	}

	Locale::initialize();

	function t($path, $assigns = array(), $lid = null) {
		$translation	= Locale::translate($path, $assigns, $lid);

		return $translation === false ? '-- TRANSLATION MISSING: ' . $path . ' --' : $translation;
	}