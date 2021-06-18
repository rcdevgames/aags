<?php
	ini_set('display_errors', 'on');
	error_reporting(E_ALL ^ E_STRICT);
	session_start();

	date_default_timezone_set('America/Sao_Paulo');
	define('ROOT', dirname(__FILE__));

	define('IS_MAINTENANCE', FALSE);
	define('MAINTENANCE_CONTROLLER', 'internal');
	define('MAINTENANCE_ACTION', 'maintenance');

	if(!isset($_SESSION['universal'])) {
		$_SESSION['universal']	= false;
	}

	if(!defined('FW_ENV')) {
		define('FV_ENV', 'development');
	}

	if(isset($_GET['_fw_rw_path'])) {
		$_SERVER['PATH_INFO']	= str_replace('/dev', '', $_GET['_fw_rw_path']);
	}

	define('DB_LOGGING', true);
	define('BACKTRACE_SELECTS', true);
	define('BACKTRACE_UPDATES', true);
	define('BACKTRACE_DELETES', true);

	define('RECORDSET_CACHE_OFF_FORCE', true);

	$___clear_cache_key				= 'v7OxcEaMALGzdbYVwXBvPaqzLHVE7eRuhvOdIJnPNbUak1knbusrqhXcvqqIjhBIetDDPvzOPGuwwTuPrpTGCCTTiCGvnDehblfb';
	$___start						= microtime(true);
	$___memory						= array();
	$___memory['before_includes']	= memory_get_usage();

	require 'includes/controller.php';
	require 'includes/inflector.php';
	require 'includes/mailer.php';
	require 'includes/shared_store.php';
	require 'includes/relation.php';
	require 'includes/recordset.php';
	require 'includes/renderer.php';
	require 'includes/url_helper.php';
	require 'includes/flash_helper.php';

	require 'config.php';
	require 'includes/db.php';

	if(isset($_GET['__clear_the_damn_cache']) && $_GET['__clear_the_damn_cache'] == $___clear_cache_key) {
		foreach(glob('/dev/shm/RECSET_' . Recordset::$key_prefix . '*') as $cache_file) {
			@unlink($cache_file);
		}
	}

	$___memory['after_includes']	= memory_get_usage();

	if(isset($_SERVER['ORIG_PATH_INFO'])) {
		$_SERVER['PATH_INFO']	= $_SERVER['ORIG_PATH_INFO'];
	}

	if(!isset($_SERVER['PATH_INFO'])) {
		$_SERVER['PATH_INFO']	= '';
	} else {
		if($_SERVER['PATH_INFO'] == '/') {
			$_SERVER['PATH_INFO']	= '';
		}
	}

	$params		= array();

	if(!$_SERVER['PATH_INFO']) {
		$home	= explode('#', $home);

		$controller	= $home[0];
		$action		= $home[1];		
	} else {
		$parts		= explode('/' , $_SERVER['PATH_INFO']);
		$parts		= array_splice($parts, 1);

		$controller	= $parts[0];
		$action		= sizeof($parts) > 1 ? $parts[1] : null;

		if(sizeof($parts) > 2) {
			$params		= array_splice($parts, 2);			
		}

		if(!$action) {
			$action	= 'index';
		}
	}

	if(IS_MAINTENANCE) {
		if(isset($_GET['is_admin'])) {
			$_SESSION['skip_maintenance']	= true;
		}

		if(!isset($_SESSION['skip_maintenance'])) {
			if(!(($controller == 'users' && (preg_match('/beta|join_complete|beta_activ/', $action))) || $controller == 'captcha')) {
				$controller	= MAINTENANCE_CONTROLLER;
				$action		= MAINTENANCE_ACTION;
			} 
		}
	}

	// newrelic
	if(extension_loaded('newrelic')) {
		newrelic_set_appname('Anime AllStars Game');
		newrelic_name_transaction($controller . '#' . $action);
	}

	$___memory['before_libs']	= memory_get_usage();

	if(is_dir('lib')) {
		foreach(glob('lib/*.php') as $helper) {
			require $helper;
		}
	}

	$___memory['after_libs']		= memory_get_usage();
	$___memory['before_models']	= memory_get_usage();

	if(is_dir('models')) {
		foreach(glob('models/*.php') as $model) {
			require $model;

			$class		= substr(basename($model), 0, strpos(basename($model), '.'));
			$table_name	= '';
			
			for($_i = 0; $_i < strlen($class); $_i++) {
				if($_i > 0 && ctype_upper($class[$_i])) {
					$table_name	.= '_' . strtolower($class[$_i]);
				} else {
					$table_name	.= strtolower($class[$_i]);					
				}
			}

			$class::initialize(Inflector::pluralize($table_name));
		}
	}

	$___memory['after_models']		= memory_get_usage();
	$___memory['before_locales']	= memory_get_usage();

	require 'includes/locale.php';

	$___memory['after_locales']		= memory_get_usage();
	$___memory['before_helpers']	= memory_get_usage();

	if(is_dir('helpers')) {
		foreach(glob('helpers/*.php') as $helper) {
			require $helper;
		}
	}

	$___memory['after_helpers']	= memory_get_usage();
	$___memory['before_mailers']	= memory_get_usage();

	if(is_dir('mailers')) {
		foreach(glob('mailers/*.php') as $mailer) {
			require $mailer;
		}
	}

	$___memory['after_mailers']	= memory_get_usage();

	$controller_file	= 'controllers/' . $controller . '_controller.php';
	$controller_class	= '';
	$_ignore			= false;

	for($_i = 0; $_i < strlen($controller); $_i++) {
		if($controller[$_i] == '_') {
			$controller_class	.= strtoupper($controller[$_i + 1]);
			$_ignore			= true;
		} else {
			if(!$_ignore) {
				if($_i == 0) {
					$controller_class	.= strtoupper($controller[$_i]);
				} else {
					$controller_class	.= $controller[$_i];
				}
			} else {
				$_ignore	= false;				
			}
		}
	}

	$controller_class	.= 'Controller';
	$denied				= function (&$instance) {
		require 'controllers/internal_controller.php';

		$instance	= new InternalController();
		$instance->denied();			
	};

	if(isset($framework_force_denied) && $framework_force_denied) {
		$instance	= null;
		$denied($instance);
	} else {
		if(!file_exists($controller_file)) {
			require 'controllers/internal_controller.php';

			$instance	= new InternalController();
			$instance->not_found();
		} else {
			require $controller_file;
		
			$instance	= new $controller_class();

			if(!method_exists($instance, $action)) {
				require 'controllers/internal_controller.php';

				$instance	= new InternalController();
				$instance->not_found();
			} else {
				if(isset($instance->denied) && $instance->denied) {
					$denied($instance);
				} else {
					call_user_func_array(array(&$instance, $action), $params);

					if(isset($instance->denied) && $instance->denied) {
						$denied($instance);
					}
				}
			}
		}		
	}

	if(isset($instance->render)) {
		if(is_a($instance, 'InternalController')) {
			$view_file	= 'views/' . $instance->render . '.php';
		} else {
			if($instance->render !== false) {
				$view_file	= 'views/' . $controller . '/' . $instance->render . '.php';			
			} else {
				$view_file	= false;
			}
		}
	} else {
		$view_file	= 'views/' . $controller . '/' . $action . '.php';
	}

	$can_render_layout	= true;
	$layout_file		= 'views/application.php';

	if(isset($instance->layout)) {
		if($instance->layout === false) {
			$can_render_layout	= false;
		} else {
			$layout_file	= 'views/' . $instance->layout . '.php';
		}
	}

	if($can_render_layout) {
		if($instance->as_json) {
			$layout	= '';
		} else {
			$layout	= render_file($layout_file, $instance->get_assignments());			
		}
	} else {
		$layout	= '';
	}

	if($view_file && !$instance->as_json) {
		$view	= render_file($view_file, $instance->get_assignments());
	} else {
		$view	= '';
	}

	if($layout) {
		if(FW_ENV == 'development' && $_SESSION['universal']) {
			$view	.= render_file('views/debug.php', array());
		}

		echo str_replace('@yield', $view, $layout);
	} else {
		if($instance->as_json) {
			header('Content-Type: application/json');

			$json		= $instance->json;
			#$json->view	= $view;

			echo json_encode($json);
		} else {
			echo $view;
		}
	}