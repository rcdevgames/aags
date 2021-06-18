<?php
	class Player extends Relation {
		use				BattleTechniqueLocks;
		use				BattleModifiers;
		use				AttributeManager;

		static			$paranoid		= true;
		private static	$instance		= null;
		private static	$has_item_cache	= [];
		private			$_attributes	= null;
		private			$training_base	= 300;
		private			$training_day_multipliers	= array(
			1	=> 6,
			2	=> 0,
			3	=> 1,
			4	=> 2,
			5	=> 3,
			6	=> 4,
			7	=> 5
		);

		protected function before_create() {
			$this->last_healed_at	= date('Y-m-d H:i:s');
		}

		protected function after_create() {
			$quest_counters				= new PlayerQuestCounter();
			$quest_counters->player_id	= $this->id;
			$quest_counters->save();

			$battle_counters			= new PlayerBattleCounter();
			$battle_counters->player_id	= $this->id;
			$battle_counters->save();

			$stats				= new PlayerStat();
			$stats->player_id	= $this->id;
			$stats->save();

			$attributes				= new PlayerAttribute();
			$attributes->player_id	= $this->id;
			$attributes->save();

			$this->graduation_id	= Graduation::find_first('anime_id=' . $this->character()->anime_id, array('cache' => true))->id;
			$this->save();
		}

		protected function after_destroy() {
			$this->removed_at	= date('Y-m-d H:i:s');
			$this->save();
		}

		protected function after_assign() {
			if(!$this->stats()) {
				$stats				= new PlayerStat();
				$stats->player_id	= $this->id;
				$stats->save();				
			}

			if(!$this->attributes()) {
				$attributes				= new PlayerAttribute();
				$attributes->player_id	= $this->id;
				$attributes->save();
			}

			if(!$this->_attributes) {
				$this->_attributes	=& $this->attributes();
			}
		}

		protected function before_update() {
			if($this->level_screen_seen) {
				if($this->is_next_level()) {
					$this->level++;
					$this->exp	-= $this->level_exp();
				}
			}
		}

		function &attributes() {
			if($this->_attributes) {
				return $this->_attributes;
			} else {
				$attributes	= PlayerAttribute::find_first('player_id=' . $this->id);
				return $attributes;
			}
		}

		function battle_npc() {
			return BattleNPC::find($this->battle_npc_id);
		}

		function character() {
			return Character::find($this->character_id, array('cache' => true));
		}

		function character_theme() {
			return CharacterTheme::find($this->character_theme_id, array('cache' => true));
		}

		function character_theme_image() {
			return CharacterThemeImage::find($this->character_theme_image_id, array('cache' => true));
		}

		function map() {
			return Map::find($this->map_id, array('cache' => true));
		}

		function user() {
			return User::find($this->user_id);
		}

		function graduation() {
			return Graduation::find($this->graduation_id, array('cache' => true));
		}

		function stats() {
			return PlayerStat::find_first('player_id=' . $this->id);
		}

		function small_image($path_only = false) {
			$theme	= $this->character_theme();
			$path	= 'criacao/' . $this->character_id . '/' . $theme->theme_code . '/1.jpg';

			if($path_only) {
				return $path;
			} else {
				return '<img src="' . image_url($path) . '" alt="' . $this->name . '" />';
			}
		}

		function profile_image($path_only = false) {
			$theme	= $this->character_theme();
			$path	= 'profile/' . $this->character_id . '/' . $theme->theme_code . '/' . CharacterThemeImage::find($this->character_theme_image_id, array('cache' => true))->image . '.jpg';

			if($path_only) {
				return $path;
			} else {
				return '<img src="' . image_url($path) . '" alt="' . $this->name . '" />';
			}
		}

		function quest_counters() {
			return PlayerQuestCounter::find_first('player_id=' . $this->id);
		}

		function battle_counters() {
			return PlayerBattleCounter::find_first('player_id=' . $this->id);
		}

		function at_for_trained() { return $this->at_for; }
		function at_int_trained() { return $this->at_int; }
		function at_res_trained() { return $this->at_res; }
		function at_dex_trained() { return $this->at_dex; }
		function at_agi_trained() { return $this->at_agi; }
		function at_vit_trained() { return $this->at_vit; }

		function level_exp() {
			return (1500 + $this->level / 5 * 130) * $this->level;
		}

		function is_next_level() {
			return $this->exp >= $this->level_exp();
		}

		function spend($amount) {
			$this->currency	-= $amount;
			$this->save();
		}

		function earn($amount) {
			$this->currency	+= $amount;
			$this->save();
		}

		function has_technique($technique) {
			return PlayerItem::find('player_id=' . $this->id . ' AND item_id=' . $technique->id) ? true : false;
		}

		function has_ability($ability) {
			return PlayerItem::find('player_id=' . $this->id . ' AND item_id=' . $ability->id) ? true : false;
		}

		function has_speciality($speciality) {
			return PlayerItem::find('player_id=' . $this->id . ' AND item_id=' . $speciality->id) ? true : false;
		}

		function has_consumable($consumable) {
			return PlayerItem::find('player_id=' . $this->id . ' AND item_id=' . $consumable->id) ? true : false;
		}

		function has_item($item) {
			if(is_numeric($item)) {
				$id	= $item;
			} else {
				if(is_a($item, 'Item')) {
					$id	= $item->id;
				} elseif(is_a($item, 'PlayerItem')) {
					$id	= $item->item_id;
				} else {
					throw new Exception("Invalid argument", 1);
				}
			}

			if(!isset(Player::$has_item_cache[$this->id])) {
				Player::$has_item_cache[$this->id]	= [];
			}

			if(!isset(Player::$has_item_cache[$this->id][$id])) {
				$result	= $this->get_item($id) ? true : false;
				Player::$has_item_cache[$this->id][$id]	= $result;
			} else {
				$result	= Player::$has_item_cache[$this->id][$id];
			}

			return $result;
		}

		function add_technique($technique) {
			$item				= new PlayerItem();
			$item->player_id	= $this->id;
			$item->item_id		= $technique->id;

			$item->save();

			$exp				= $technique->formula()->consume_mana * 2;
			$this->exp			+= $exp;
			$this->less_stamina	+= $technique->req_for_stamina;
			$this->less_mana	+= $technique->req_for_mana;

			$this->save();

			return array('exp' => $exp);
		}

		function add_ability($ability) {
			$item					= new PlayerItem();
			$item->player_id		= $this->id;
			$item->item_id			= $ability->id;
			$item->variant_type_id	= $this->ability_variant_type_id;

			$item->save();

			$this->_update_sum_attributes();
		}

		function add_speciality($speciality) {
			$item					= new PlayerItem();
			$item->player_id		= $this->id;
			$item->item_id			= $speciality->id;
			$item->variant_type_id	= $this->speciality_variant_type_id;

			$item->save();

			$this->_update_sum_attributes();
		}

		function add_talent($talent) {
			$item				= new PlayerItem();
			$item->player_id	= $this->id;
			$item->item_id		= $talent->id;

			$item->save();

			$this->_update_sum_attributes();
		}

		function add_consumable($consumable, $quantity = 1) {
			if($this->has_consumable($consumable)) {
				$item					= $this->get_item($consumable);
				$item->quantity			+= $quantity;
			} else {
				$item					= new PlayerItem();
				$item->player_id		= $this->id;
				$item->item_id			= $consumable->id;
				$item->quantity			= $quantity;
			}

			$item->save();

			return $item;
		}

		function use_consumable($consumable) {
			if($this->has_consumable($consumable)) {
				$item			= $this->get_item($consumable);
				$item->quantity	-= 1;
				$item->save();

				if($item->for_file) {
					$player->less_life	-= $item->for_file;

					if($player->less_life < 0) {
						$player->less_life	= 0;
					}
				}

				if($item->for_mana) {
					$player->less_mana	-= $item->for_mana;

					if($player->less_mana < 0) {
						$player->less_mana	= 0;
					}
				}

				if($item->for_stamina) {
					$player->less_stamina	-= $item->for_stamina;

					if($player->less_stamina < 0) {
						$player->less_stamina	= 0;
					}
				}

				$player->save();

				return $item;
			}

			return false;
		}

		function max_attribute_training() {
			$total	= (4000 + (($this->graduation()->sorting <= 2 ? 0 : $this->graduation()->sorting - 2) * 1000));
			$total	+= $total * $this->training_day_multipliers[date('N')];

			return $total;
		}

		function max_technique_training() {
			$total	= (3000 + (($this->graduation()->sorting <= 2 ? 0 : $this->graduation()->sorting - 2) * 1000));
			$total	+= $total * $this->training_day_multipliers[date('N')];

			return $total;
		}

		function available_attribute_training() {
			return $this->max_attribute_training() - $this->training_points_spent;
		}

		function available_training_points() {
			return $this->training_total_to_point() - $this->training_points_spent;
		}

		function training_to_next_point($current = false) {
			if(!$current) {
				return ($this->training_total_to_point() + 1) * $this->training_base;				
			} else {
				return $this->training_total - $this->training_total_to_point(true);
			}
		}

		function training_total_to_point($return_amount = false) {
			$counter		= 1;
			$amount			= 0;
			$amount_next	= 0;

			if ($this->training_total < $this->training_base) {
				if($return_amount) {
					return 0;
				} else {
					return 0;					
				}
			}

			while (true) {
				$points			= $counter * $this->training_base;
				$amount			+= $points;

				if($this->training_total < $amount) {
					$amount_next	= $amount - $points;
					break;
				}

				$counter++;
			}

			return $return_amount ? $amount_next : ($counter - 1);
		}

		function get_item($item) {
			if(is_numeric($item)) {
				$id	= $item;
			} else {
				if(is_a($item, 'Item')) {
					$id	= $item->id;
				} elseif(is_a($item, 'PlayerItem')) {
					$id	= $item->item_id;
				} else {
					throw new Exception("Invalid argument", 1);
				}
			}

			return PlayerItem::find_first('player_id=' . $this->id . ' AND item_id=' . $id);
		}

		function learned_techniques() {
			$result	= array();
			$items	= Recordset::query('
				SELECT
					a.id

				FROM
					player_items a JOIN items b ON b.id=a.item_id AND b.item_type_id=1

				WHERE
					a.player_id=' . $this->id
			);

			foreach($items->result_array() as $item) {
				$result[]	= PlayerItem::find($item['id']);
			}

			return $result;
		}

		function learned_talents() {
			$result	= array();
			$items	= Recordset::query('
				SELECT
					a.id

				FROM
					player_items a JOIN items b ON b.id=a.item_id AND b.item_type_id=6

				WHERE
					a.player_id=' . $this->id
			);

			foreach($items->result_array() as $item) {
				$result[]	= PlayerItem::find($item['id']);
			}

			return $result;
		}

		private function _update_sum_attributes() {
			$at		=& $this->attributes();
			$items	= Recordset::query('
				SELECT
					a.item_id

				FROM
					player_items a JOIN items b ON b.id=a.item_id

				WHERE
					a.player_id=' . $this->id . '
					AND b.item_type_id IN(3, 4, 6)');

			$at_for							= 0;
			$at_int							= 0;
			$at_res							= 0;
			$at_agi							= 0;
			$at_dex							= 0;
			$at_vit							= 0;
			$for_life						= 0;
			$for_mana						= 0;
			$for_stamina					= 0;
			$for_atk						= 0;
			$for_def						= 0;
			$for_hit						= 0;
			$for_init						= 0;
			$for_crit						= 0;
			$for_inc_crit					= 0;
			$for_abs						= 0;
			$for_inc_abs					= 0;
			$for_prec						= 0;
			$for_inti						= 0;
			$for_conv						= 0;
			$bonus_food_discount			= 0;
			$bonus_weapon_discount			= 0;
			$bonus_luck_discount			= 0;
			$bonus_mana_consume				= 0;
			$bonus_cooldown					= 0;
			$bonus_exp_fight				= 0;
			$bonus_currency_fight			= 0;
			$bonus_attribute_training_cost	= 0;
			$bonus_training_earn			= 0;
			$bonus_training_exp				= 0;
			$bonus_quest_time				= 0;
			$bonus_food_heal				= 0;
			$bonus_npc_in_quests			= 0;
			$bonus_daily_npc				= 0;
			$bonus_map_npc					= 0;
			$bonus_drop						= 0;
			$bonus_stamina_max				= 0;
			$bonus_stamina_heal				= 0;
			$bonus_stamina_consume			= 0;

			foreach ($items->result_array() as $item) {
				$instance	= Item::find_first($item['item_id']);

				$at_for							+= $instance->at_for;
				$at_int							+= $instance->at_int;
				$at_res							+= $instance->at_res;
				$at_agi							+= $instance->at_agi;
				$at_dex							+= $instance->at_dex;
				$at_vit							+= $instance->at_vit;

				$for_life						+= $instance->for_life;
				$for_mana						+= $instance->for_mana;
				$for_stamina					+= $instance->for_stamina;
				$for_atk						+= $instance->for_atk;
				$for_def						+= $instance->for_def;
				$for_hit						+= $instance->for_hit;
				$for_init						+= $instance->for_init;
				$for_crit						+= $instance->for_crit;
				$for_inc_crit					+= $instance->for_inc_crit;
				$for_abs						+= $instance->for_abs;
				$for_inc_abs					+= $instance->for_inc_abs;
				$for_prec						+= $instance->for_prec;
				$for_inti						+= $instance->for_inti;
				$for_conv						+= $instance->for_conv;

				$bonus_food_discount			+= $instance->bonus_food_discount;
				$bonus_weapon_discount			+= $instance->bonus_weapon_discount;
				$bonus_luck_discount			+= $instance->bonus_luck_discount;
				$bonus_mana_consume				+= $instance->bonus_mana_consume;
				$bonus_cooldown					+= $instance->bonus_cooldown;
				$bonus_exp_fight				+= $instance->bonus_exp_fight;
				$bonus_currency_fight			+= $instance->bonus_currency_fight;
				$bonus_attribute_training_cost	+= $instance->bonus_attribute_training_cost;
				$bonus_training_earn			+= $instance->bonus_training_earn;
				$bonus_training_exp				+= $instance->bonus_training_exp;
				$bonus_quest_time				+= $instance->bonus_quest_time;
				$bonus_food_heal				+= $instance->bonus_food_heal;
				$bonus_npc_in_quests			+= $instance->bonus_npc_in_quests;
				$bonus_daily_npc				+= $instance->bonus_daily_npc;
				$bonus_map_npc					+= $instance->bonus_map_npc;
				$bonus_drop						+= $instance->bonus_drop;
				$bonus_stamina_max				+= $instance->bonus_stamina_max;
				$bonus_stamina_heal				+= $instance->bonus_stamina_heal;
				$bonus_stamina_consume			+= $instance->bonus_stamina_consume;
			}

			$at->sum_at_for							= $at_for;
			$at->sum_at_int							= $at_int;
			$at->sum_at_res							= $at_res;
			$at->sum_at_agi							= $at_agi;
			$at->sum_at_dex							= $at_dex;
			$at->sum_at_vit							= $at_vit;

			$at->sum_for_life						= $for_life;
			$at->sum_for_mana						= $for_mana;
			$at->sum_for_stamina					= $for_stamina;
			$at->sum_for_atk						= $for_atk;
			$at->sum_for_def						= $for_def;
			$at->sum_for_hit						= $for_hit;
			$at->sum_for_init						= $for_init;
			$at->sum_for_crit						= $for_crit;
			$at->sum_for_inc_crit					= $for_inc_crit;
			$at->sum_for_abs						= $for_abs;
			$at->sum_for_inc_abs					= $for_inc_abs;
			$at->sum_for_prec						= $for_prec;
			$at->sum_for_inti						= $for_inti;
			$at->sum_for_conv						= $for_conv;

			$at->sum_bonus_food_discount			= $bonus_food_discount;
			$at->sum_bonus_weapon_discount			= $bonus_weapon_discount;
			$at->sum_bonus_luck_discount			= $bonus_luck_discount;
			$at->sum_bonus_mana_consume				= $bonus_mana_consume;
			$at->sum_bonus_cooldown					= $bonus_cooldown;
			$at->sum_bonus_exp_fight				= $bonus_exp_fight;
			$at->sum_bonus_currency_fight			= $bonus_currency_fight;
			$at->sum_bonus_attribute_training_cost	= $bonus_attribute_training_cost;
			$at->sum_bonus_training_earn			= $bonus_training_earn;
			$at->sum_bonus_training_exp				= $bonus_training_exp;
			$at->sum_bonus_quest_time				= $bonus_quest_time;
			$at->sum_bonus_food_heal				= $bonus_food_heal;
			$at->sum_bonus_npc_in_quests			= $bonus_npc_in_quests;
			$at->sum_bonus_daily_npc				= $bonus_daily_npc;
			$at->sum_bonus_map_npc					= $bonus_map_npc;
			$at->sum_bonus_drop						= $bonus_drop;
			$at->sum_bonus_stamina_max				= $bonus_stamina_max;
			$at->sum_bonus_stamina_heal				= $bonus_stamina_heal;
			$at->sum_bonus_stamina_consume			= $bonus_stamina_consume;

			$at->save();
		}

		function get_gauge() {

		}

		function set_gauge($value) {

		}

		function get_techniques() {
			$items	= Recordset::query('SELECT a.id FROM player_items a JOIN items b ON b.id=a.item_id WHERE a.player_id=' . $this->id . ' AND b.item_type_id IN(1, 7)');
			$return	= [];

			foreach($items->result_array() as $item) {
				$return[]	= PlayerItem::find($item['id']);
			}

			$return[]	= new FakePlayerItem(112, $this);
			$return[]	= new FakePlayerItem(113, $this);

			return $return;
		}

		function get_technique($id) {
			if($id == 112 || $id == 113) {
				return new FakePlayerItem($id, $this);
			}

			return PlayerItem::find(Recordset::query('SELECT a.id FROM player_items a JOIN items b ON b.id=a.item_id WHERE a.player_id=' . $this->id . ' AND a.item_id=' . $id)->row()->id);
		}

		function get_npc() {
			return SharedStore::G('NPC_' . $this->id);
		}

		function save_npc($npc) {
			SharedStore::S('NPC_' . $this->id, $npc);
		}

		function build_technique_lock_uid() {
			return 'LOCKS_' . $this->id;
		}

		function build_modifiers_uid() {
			return 'MODIFIERS_' . $this->id;
		}

		static function set_instance($player) {
			Player::$instance	= $player;
		}

		static function &get_instance() {
			return Player::$instance;
		}
	}