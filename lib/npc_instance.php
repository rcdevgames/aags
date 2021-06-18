<?php
	class NpcInstance {
		use				BattleTechniqueLocks;
		use				BattleModifiers;
		use				AttributeManager;

		public	$less_life			= 0;
		public	$less_mana			= 0;
		public	$less_stamina		= 0;
		private	$gauge				= 0;
		public	$level				= 1;

		public	$at_for				= 0;
		public	$at_int				= 0;
		public	$at_res				= 0;
		public	$at_agi				= 0;
		public	$at_dex				= 0;
		public	$at_vit				= 0;

		public	$name				= '';
		private	$attacks			= [];

		private	$anime				= null;
		private	$character			= null;
		private	$character_theme	= null;
		private	$theme_image		= 0;

		function __construct($level) {
			$animes					= Anime::find('active=1', ['cache' => true]);
			$anime					= $animes[rand(0, sizeof($animes) - 1)];

			$characters				= $anime->characters(' AND active=1');
			$character				= $characters[rand(0, sizeof($characters) - 1)];

			$themes					= $character->themes();
			$theme					= $themes[rand(0, sizeof($themes) - 1)];

			$images					=  CharacterThemeImage::find('character_theme_id=' . $theme->id, ['cache' => true]);
			$image					= $images[rand(0, sizeof($images) - 1)];

			$this->anime			= $anime;
			$this->character		= $character;
			$this->character_theme	= $theme;
			$this->theme_image		= $image;

			$this->name				= $this->character->description()->name;
			$this->level			= $level;
			$this->uid				= uniqid(uniqid('', true), true);

			$attacks				= $this->character_theme->attacks();
			$attacks[]				= (new FakePlayerItem(112, $this))->item();
			$attacks[]				= (new FakePlayerItem(113, $this))->item();

			foreach($attacks as $attack) {
				if($attack->req_level <= $this->level) {
					if(!in_array($attack->id, [112, 113])) {
						$attack->set_anime($this->anime->id);
					}
					
					$this->attacks[]	= $attack;
				}
			}
		}

		function attributes() {
			$at	= new stdClass();

			$at->at_for								= 0;
			$at->at_int								= 0;
			$at->at_res								= 0;
			$at->at_agi								= 0;
			$at->at_dex								= 0;
			$at->at_vit								= 0;

			$at->sum_at_for							= 0;
			$at->sum_at_int							= 0;
			$at->sum_at_res							= 0;
			$at->sum_at_agi							= 0;
			$at->sum_at_dex							= 0;
			$at->sum_at_vit							= 0;

			$at->sum_for_life						= 0;
			$at->sum_for_mana						= 0;
			$at->sum_for_stamina					= 0;
			$at->sum_for_atk						= 0;
			$at->sum_for_def						= 0;
			$at->sum_for_hit						= 0;
			$at->sum_for_init						= 0;
			$at->sum_for_crit						= 0;
			$at->sum_for_inc_crit					= 0;
			$at->sum_for_abs						= 0;
			$at->sum_for_inc_abs					= 0;
			$at->sum_for_prec						= 0;
			$at->sum_for_inti						= 0;
			$at->sum_for_conv						= 0;

			$at->sum_bonus_food_discount			= 0;
			$at->sum_bonus_weapon_discount			= 0;
			$at->sum_bonus_luck_discount			= 0;
			$at->sum_bonus_mana_consume				= 0;
			$at->sum_bonus_cooldown					= 0;
			$at->sum_bonus_exp_fight				= 0;
			$at->sum_bonus_currency_fight			= 0;
			$at->sum_bonus_attribute_training_cost	= 0;
			$at->sum_bonus_training_earn			= 0;
			$at->sum_bonus_training_exp				= 0;
			$at->sum_bonus_quest_time				= 0;
			$at->sum_bonus_food_heal				= 0;
			$at->sum_bonus_npc_in_quests			= 0;
			$at->sum_bonus_daily_npc				= 0;
			$at->sum_bonus_map_npc					= 0;
			$at->sum_bonus_drop						= 0;
			$at->sum_bonus_stamina_max				= 0;
			$at->sum_bonus_stamina_heal				= 0;
			$at->sum_bonus_stamina_consume			= 0;

			return $at;			
		}

		function character() {
			return $this->character;
		}

		function get_gauge() {
			$this->gauge;
		}

		function set_gauge($value) {
			$this->gauge	= $value;
		}

		function choose_modifier(&$battle) {
		}

		function choose_technique() {
			$retries	= 0;
			$technique	= null;
			
			while($retries++ < 500) {
				$choosen	= $this->attacks[rand(0, sizeof($this->attacks) - 1)];

				if(!$choosen->is_buff) {
					if($choosen->formula()->consume_mana <= $this->for_mana()) {
						$technique	= $choosen;
						break;
					}
				}
			}

			if(!$technique) {
				// TODO: $_$
			} else {
				return $technique;
			}
		}

		function profile_image($path_only = false) {
			$path	= 'profile/' . $this->character->id . '/' . $this->character_theme->theme_code . '/' . $this->theme_image->image . '.jpg';

			if($path_only) {
				return $path;
			} else {
				return '<img src="' . image_url($path) . '" alt="' . $this->name . '" />';
			}
		}


		function build_technique_lock_uid() {
			return 'NPC_LOCKS_' . $this->uid;
		}

		function build_modifiers_uid() {
			return 'NPC_MODIFIERS_' . $this->uid;
		}
	}