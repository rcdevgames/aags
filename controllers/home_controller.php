<?php
	class HomeController extends Controller {
		function index() { }

		function maintenance() {
			$this->layout	= 'maintenance';
		}
	}