<?php
	if(isset($_SESSION['user_id']) && $_SESSION['user_id']) {
		User::set_instance(User::find($_SESSION['user_id']));
	}