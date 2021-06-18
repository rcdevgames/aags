<?php
	class Controller {
		private $assigns	= array();
		public	$as_json	= false;
		public	$json		= null;
		public	$view		= null;
		public	$layout		= null;

		function __construct() {
			$this->json	= new stdClass();
		}

		function assign($key, $value) {
			$this->assigns[$key]	= $value;
		}

		function get_assignments() {
			return $this->assigns;
		}
	}