<?php
	class LuckController extends Controller {
		private	$default_week		= array('1' => false, '2' => false, '3' => false, '4' => false, '5' => false, '6' => false, '7' => false);

		private	$daily_currency		= 2000;
		private	$daily_vip			= 1;

		private	$weekly_currency	= 6000;
		private	$weekly_vip			= 3;

		function __construct() {
			parent::__construct();

			$player					= Player::get_instance();
			$this->daily_currency	-= percent($player->attributes()->sum_bonus_luck_discount, $this->daily_currency);
			$this->weekly_currency	-= percent($player->attributes()->sum_bonus_luck_discount, $this->weekly_currency);
		}

		function index() {
			$player	= Player::get_instance();
			$stats	= $player->stats();

			$this->assign('daily_vip', $this->daily_vip);
			$this->assign('daily_currency', $this->daily_currency);

			$this->assign('weekly_vip', $this->weekly_vip);
			$this->assign('weekly_currency', $this->weekly_currency);

			$this->assign('player', $player);
			$this->assign('week_data', @unserialize($stats->luck_week_data));

			$this->assign('reward_list', Recordset::query('
				SELECT
					a.*,
					COUNT(b.id) AS total

				FROM
					luck_rewards a LEFT JOIN player_luck_logs b ON b.luck_reward_id=a.id AND b.player_id=' . $player->id . '

					
				GROUP BY a.id
			'));
		}

		function roll() {
			$this->layout			= false;
			$this->as_json			= true;
			$this->render			= false;
			$this->json->success	= false;
			$errors					= array();
			$player					= Player::get_instance();
			$stats					= $player->stats();
			$attributes				= $player->attributes();
			$user					= User::get_instance();
			$week_full				= true;
			$week_data				= @unserialize($stats->luck_week_data);

			if(!is_array($week_data)) {
				$week_data	= $this->default_week;
			}

			if(isset($_POST['type']) && isset($_POST['currency']) && is_numeric($_POST['currency'])) {
				if($_POST['type'] == 'daily') {
					$is_weekly			= false;
					$needed_currency	= $this->daily_currency;
					$needed_vip			= $this->daily_vip;

					if($player->luck_used) {
						$errors[]	= t('luck.errors.already');
					}
				} elseif($_POST['type'] == 'weekly') {
					$is_weekly			= true;
					$needed_currency	= $this->weekly_currency;
					$needed_vip			= $this->weekly_vip;

					foreach($week_data as $day => $used) {
						if(!$used) {
							$week_full	= false;
						}
					}

					if(!$week_full) {
						$errors[]	= t('luck.errors.week_empty');
					}
				}

				if($_POST['currency'] == 1 && $player->currency < $needed_currency) {
					$errors[]	= t('luck.errors.currency', array('currency' => t('currencies.' . $player->character()->anime_id)));
				}

				if($_POST['currency'] != 1 && $user->credits < $needed_vip) {
					$errors[]	= t('luck.errors.currency', array('currency' => t('currencies.vip')));
				}
			}

			if(!sizeof($errors)) {
				$rewards		= LuckReward::find('1=1' . ($is_weekly ? ' AND weekly=1' : ''), array('reorder' => 'RAND()'));
				$log			= new PlayerLuckLog();
				$choosen_reward	= false;

				if($_POST['currency'] == 1) {
					$player->spend($needed_currency);
					$log->currency	= $needed_currency;
				} else {
					$user->spend($needed_vip);
					$log->vip	= $needed_vip;
				}

				foreach($rewards as $reward) {
					if(rand(1, 100) <= $reward->chance) {
						$choosen_reward	= $reward;
						
						break;
					}
				}

				if($is_weekly) {
					$week_data				= $this->default_week;
				} else {
					$week_data[date('N')]	= true;
				}

				$stats->luck_week_data	= serialize($week_data);
				$stats->save();

				$this->json->success	= true;
				$this->json->slot		= array($choosen_reward->slot1, $choosen_reward->slot2, $choosen_reward->slot3, $choosen_reward->slot4);
				$this->json->today		= date('N');

				$message	= '';

				if($choosen_reward->currency) {
					$message	.= $choosen_reward->currency . ' ' . t('currencies.' . $player->character()->anime_id);

					$player->earn($choosen_reward->currency);
				}

				if($choosen_reward->vip) {
					$message	.= $choosen_reward->vip . ' ' . t('currencies.vip');
					$user->earn($choosen_reward->vip);
				}

				if($choosen_reward->item_id) {
					$item		= Item::find_first($choosen_reward->item_id);
					$message	.= $item->name . ' x' . $choosen_reward->quantity;
				}

				$ats	= array(
					'at_for'	=> t('at.at_for'),
					'at_int'	=> t('at.at_int'),
					'at_res'	=> t('at.at_res'),
					'at_agi'	=> t('at.at_agi'),
					'at_dex'	=> t('at.at_dex'),
					'at_vit'	=> t('at.at_vit')
				);

				foreach ($ats as $key => $value) {
					if($choosen_reward->$key) {
						$attributes->$key	+= $choosen_reward->$key;

						$message	.= t('luck.index.messages.point', array('count' => $choosen_reward->$key, 'attribute' => $value));
					}
				}

				if($choosen_reward->traning_total) {
					$message	.= t('luck.index.messages.training_total', array('count' => $choosen_reward->traning_total));

					$player->traning_total	+= $choosen_reward->traning_total;
				}

				if($choosen_reward->weekly_points_spent) {
					$message	.= t('luck.index.messages.weekly_points_spent', array('count' => $choosen_reward->weekly_points_spent));

					$player->weekly_points_spent	-= $choosen_reward->weekly_points_spent;
				}

				$log->player_id			= $player->id;
				$log->luck_reward_id	= $choosen_reward->id;
				$log->save();

				if(!$is_weekly) {
					$player->luck_used	= 1;
				}

				$player->save();
				$attributes->save();

				$this->json->message	= t('luck.index.won', array('prize' => $message));
				$this->json->currency	= $player->currency;
				$this->json->credits	= $user->credits;
			} else {
				$this->json->errors	= $errors;
			}
		}
	}