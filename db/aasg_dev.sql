-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 05/03/2021 às 11:55
-- Versão do servidor: 10.1.38-MariaDB
-- Versão do PHP: 5.6.40

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `aasg_dev`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `animes`
--

DROP TABLE IF EXISTS `animes`;
CREATE TABLE IF NOT EXISTS `animes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `animes`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `anime_descriptions`
--

DROP TABLE IF EXISTS `anime_descriptions`;
CREATE TABLE IF NOT EXISTS `anime_descriptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `anime_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `anime_descriptions`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `battle_npcs`
--

DROP TABLE IF EXISTS `battle_npcs`;
CREATE TABLE IF NOT EXISTS `battle_npcs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL,
  `battle_type_id` int(11) NOT NULL,
  `battle_log` mediumblob NOT NULL,
  `finished` tinyint(1) NOT NULL DEFAULT '0',
  `finished_at` datetime DEFAULT NULL,
  `won` int(11) DEFAULT '0',
  `current_turn` int(11) NOT NULL DEFAULT '1',
  `inactivity` tinyint(1) NOT NULL DEFAULT '0',
  `draw` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `characters`
--

DROP TABLE IF EXISTS `characters`;
CREATE TABLE IF NOT EXISTS `characters` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `anime_id` int(11) NOT NULL DEFAULT '0',
  `at_for` int(11) NOT NULL DEFAULT '0',
  `at_int` int(11) NOT NULL DEFAULT '0',
  `at_res` int(11) NOT NULL DEFAULT '0',
  `at_agi` int(11) NOT NULL DEFAULT '0',
  `at_dex` int(11) NOT NULL DEFAULT '0',
  `at_vit` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_anime_id` (`anime_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `characters`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `character_descriptions`
--

DROP TABLE IF EXISTS `character_descriptions`;
CREATE TABLE IF NOT EXISTS `character_descriptions` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `character_id` int(11) NOT NULL DEFAULT '0',
  `language_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_language_id` (`language_id`),
  KEY `idx_character_id` (`character_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `character_descriptions`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `character_themes`
--

DROP TABLE IF EXISTS `character_themes`;
CREATE TABLE IF NOT EXISTS `character_themes` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `character_id` int(11) NOT NULL DEFAULT '0',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `is_buyable` tinyint(1) NOT NULL DEFAULT '0',
  `price_vip` int(11) NOT NULL DEFAULT '0',
  `price_currency` int(11) NOT NULL DEFAULT '0',
  `theme_code` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_character_id` (`character_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `character_themes`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `character_theme_descriptions`
--

DROP TABLE IF EXISTS `character_theme_descriptions`;
CREATE TABLE IF NOT EXISTS `character_theme_descriptions` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `character_theme_id` int(11) NOT NULL DEFAULT '0',
  `language_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `idx_language_id` (`language_id`),
  KEY `idx_character_theme_id` (`character_theme_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `character_theme_descriptions`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `character_theme_images`
--

DROP TABLE IF EXISTS `character_theme_images`;
CREATE TABLE IF NOT EXISTS `character_theme_images` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `character_theme_id` int(11) DEFAULT NULL,
  `image` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_character_theme_id` (`character_theme_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `character_theme_images`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `character_theme_items`
--

DROP TABLE IF EXISTS `character_theme_items`;
CREATE TABLE IF NOT EXISTS `character_theme_items` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `item_id` int(11) DEFAULT NULL,
  `character_theme_id` int(11) DEFAULT NULL,
  `language_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`),
  KEY `character_theme_id` (`character_theme_id`),
  KEY `language_id` (`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

--
-- Despejando dados para a tabela `character_theme_items`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `countries`
--

DROP TABLE IF EXISTS `countries`;
CREATE TABLE IF NOT EXISTS `countries` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `countries`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `graduations`
--

DROP TABLE IF EXISTS `graduations`;
CREATE TABLE IF NOT EXISTS `graduations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `anime_id` int(11) DEFAULT NULL,
  `req_level` int(11) NOT NULL DEFAULT '1',
  `req_quest_count` int(11) NOT NULL DEFAULT '0',
  `req_training_points` int(11) NOT NULL DEFAULT '0',
  `req_wins_pvp` int(11) NOT NULL DEFAULT '0',
  `req_wins_npc` int(11) NOT NULL DEFAULT '0',
  `req_technique_count` int(11) NOT NULL DEFAULT '0',
  `req_technique_l1_count` int(11) NOT NULL DEFAULT '0',
  `req_technique_l2_count` int(11) NOT NULL DEFAULT '0',
  `req_technique_l3_count` int(11) NOT NULL DEFAULT '0',
  `req_technique_l4_count` int(11) NOT NULL DEFAULT '0',
  `req_technique_l5_count` int(11) NOT NULL DEFAULT '0',
  `sorting` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `graduations`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `graduation_descriptions`
--

DROP TABLE IF EXISTS `graduation_descriptions`;
CREATE TABLE IF NOT EXISTS `graduation_descriptions` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `graduation_id` int(11) DEFAULT NULL,
  `language_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `graduation_id` (`graduation_id`,`language_id`) USING BTREE,
  KEY `idx_graduation_id` (`graduation_id`),
  KEY `idx_language_id` (`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `graduation_descriptions`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `headlines`
--

DROP TABLE IF EXISTS `headlines`;
CREATE TABLE IF NOT EXISTS `headlines` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name_br` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `headlines`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `items`
--

DROP TABLE IF EXISTS `items`;
CREATE TABLE IF NOT EXISTS `items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_type_id` int(11) NOT NULL,
  `cooldown` int(11) NOT NULL DEFAULT '0',
  `duration` int(11) NOT NULL DEFAULT '0',
  `is_generic` tinyint(1) NOT NULL DEFAULT '1',
  `is_defensive` tinyint(1) NOT NULL DEFAULT '0',
  `is_buff` tinyint(1) NOT NULL DEFAULT '0',
  `buff_direction` enum('friend','target') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'friend',
  `price_currency` int(11) NOT NULL DEFAULT '0',
  `price_vip` int(11) NOT NULL DEFAULT '0',
  `req_level` int(11) NOT NULL DEFAULT '1',
  `req_graduation_id` int(11) NOT NULL DEFAULT '1',
  `req_at_for` int(11) NOT NULL DEFAULT '0',
  `req_at_int` int(11) NOT NULL DEFAULT '0',
  `req_at_res` int(11) NOT NULL DEFAULT '0',
  `req_at_agi` int(11) NOT NULL DEFAULT '0',
  `req_at_dex` int(11) NOT NULL DEFAULT '0',
  `req_at_vit` int(11) NOT NULL DEFAULT '0',
  `req_for_hit_chance` int(11) NOT NULL DEFAULT '0',
  `at_for` int(11) NOT NULL DEFAULT '0',
  `at_int` int(11) NOT NULL DEFAULT '0',
  `at_res` int(11) NOT NULL DEFAULT '0',
  `at_agi` int(11) NOT NULL DEFAULT '0',
  `at_dex` int(11) NOT NULL DEFAULT '0',
  `at_vit` int(11) NOT NULL DEFAULT '0',
  `for_atk` int(11) NOT NULL DEFAULT '0',
  `for_def` int(11) NOT NULL DEFAULT '0',
  `for_hit` int(11) NOT NULL DEFAULT '0',
  `for_init` int(11) NOT NULL DEFAULT '0',
  `for_crit` float(10,2) NOT NULL DEFAULT '0.00',
  `for_inc_crit` float(10,2) NOT NULL DEFAULT '0.00',
  `for_abs` float(10,2) NOT NULL DEFAULT '0.00',
  `for_inc_abs` float(10,2) NOT NULL DEFAULT '0.00',
  `for_prec` int(11) NOT NULL DEFAULT '0',
  `for_inti` int(11) NOT NULL DEFAULT '0',
  `for_conv` int(11) NOT NULL DEFAULT '0',
  `for_life` int(11) NOT NULL DEFAULT '0',
  `for_mana` int(11) NOT NULL DEFAULT '0',
  `for_stamina` int(11) NOT NULL DEFAULT '0',
  `bonus_food_heal` int(11) NOT NULL DEFAULT '0',
  `bonus_food_discount` int(11) NOT NULL DEFAULT '0',
  `bonus_weapon_discount` int(11) NOT NULL DEFAULT '0',
  `bonus_luck_discount` int(11) NOT NULL DEFAULT '0',
  `bonus_mana_consume` int(11) NOT NULL DEFAULT '0',
  `bonus_cooldown` int(11) NOT NULL DEFAULT '0',
  `bonus_exp_fight` int(11) NOT NULL DEFAULT '0',
  `bonus_currency_fight` int(11) NOT NULL DEFAULT '0',
  `bonus_training_earn` int(11) NOT NULL DEFAULT '0',
  `bonus_training_exp` int(11) NOT NULL DEFAULT '0',
  `bonus_attribute_training_cost` int(11) NOT NULL DEFAULT '0',
  `bonus_quest_time` int(11) NOT NULL DEFAULT '0',
  `bonus_npc_in_quests` int(11) NOT NULL DEFAULT '0',
  `bonus_daily_npc` int(11) NOT NULL DEFAULT '0',
  `bonus_map_npc` int(11) NOT NULL DEFAULT '0',
  `bonus_drop` int(11) NOT NULL DEFAULT '0',
  `bonus_stamina_max` int(11) NOT NULL DEFAULT '0',
  `bonus_stamina_heal` int(11) NOT NULL DEFAULT '0',
  `bonus_stamina_consume` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `items`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `item_descriptions`
--

DROP TABLE IF EXISTS `item_descriptions`;
CREATE TABLE IF NOT EXISTS `item_descriptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `anime_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `image` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `item_descriptions`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `item_levels`
--

DROP TABLE IF EXISTS `item_levels`;
CREATE TABLE IF NOT EXISTS `item_levels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `is_generic` tinyint(1) NOT NULL DEFAULT '0',
  `is_defensive` tinyint(1) NOT NULL DEFAULT '0',
  `is_buff` tinyint(1) NOT NULL DEFAULT '0',
  `req_graduation_id` int(11) NOT NULL DEFAULT '0',
  `req_player_item_level` int(11) NOT NULL DEFAULT '0',
  `req_use` int(11) NOT NULL DEFAULT '0',
  `req_kills` int(11) NOT NULL DEFAULT '0',
  `req_kills_with_crit` int(11) NOT NULL DEFAULT '0',
  `req_kills_with_precision` int(11) NOT NULL DEFAULT '0',
  `req_use_low_stat` int(11) NOT NULL DEFAULT '0',
  `req_full_defenses` int(11) NOT NULL DEFAULT '0',
  `sorting` int(11) NOT NULL DEFAULT '0',
  `for_inc_crit` int(11) NOT NULL DEFAULT '0',
  `for_mana` int(11) NOT NULL DEFAULT '0',
  `for_atk` int(11) NOT NULL DEFAULT '0',
  `for_def` int(11) NOT NULL DEFAULT '0',
  `for_hit_chance` int(11) NOT NULL DEFAULT '0',
  `cooldown` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `item_levels`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `item_variants`
--

DROP TABLE IF EXISTS `item_variants`;
CREATE TABLE IF NOT EXISTS `item_variants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `item_variant_type_id` int(11) NOT NULL,
  `sorting` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `item_variant_types`
--

DROP TABLE IF EXISTS `item_variant_types`;
CREATE TABLE IF NOT EXISTS `item_variant_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `item_variant_type_names`
--

DROP TABLE IF EXISTS `item_variant_type_names`;
CREATE TABLE IF NOT EXISTS `item_variant_type_names` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_variant_type_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `languages`
--

DROP TABLE IF EXISTS `languages`;
CREATE TABLE IF NOT EXISTS `languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `header` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `header_mini` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `languages`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `luck_rewards`
--

DROP TABLE IF EXISTS `luck_rewards`;
CREATE TABLE IF NOT EXISTS `luck_rewards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `menus`
--

DROP TABLE IF EXISTS `menus`;
CREATE TABLE IF NOT EXISTS `menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_category_id` int(11) NOT NULL,
  `name` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `href` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `hidden` tinyint(1) NOT NULL DEFAULT '1',
  `h_loggedin` tinyint(4) NOT NULL DEFAULT '0',
  `h_player` tinyint(4) NOT NULL DEFAULT '0',
  `h_next_level` tinyint(4) NOT NULL DEFAULT '0',
  `h_training_technique` tinyint(4) NOT NULL DEFAULT '0',
  `h_battle_npc` tinyint(4) NOT NULL DEFAULT '0',
  `h_battle_pvp` tinyint(4) NOT NULL DEFAULT '0',
  `h_hospital` tinyint(4) NOT NULL DEFAULT '0',
  `h_time_quest` tinyint(4) NOT NULL DEFAULT '0',
  `sorting` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `menus`
--

INSERT INTO `menus` (`id`, `menu_category_id`, `name`, `href`, `active`, `hidden`, `h_loggedin`, `h_player`, `h_next_level`, `h_training_technique`, `h_battle_npc`, `h_battle_pvp`, `h_hospital`, `h_time_quest`, `sorting`) VALUES
(1, 1, 'menus.home', 'home', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(2, 1, 'menus.join', 'users#join', 1, 0, 2, 0, 0, 0, 0, 0, 0, 0, 2),
(3, 1, 'menus.reset_password', 'users#reset_password', 1, 0, 2, 0, 0, 0, 0, 0, 0, 0, 3),
(4, 0, '', 'users#login', 1, 1, 2, 0, 0, 0, 0, 0, 0, 0, 0),
(5, 2, 'menus.create_character', 'characters#create', 1, 0, 1, 0, 0, 0, 2, 2, 0, 0, 3),
(6, 2, 'menus.select_character', 'characters#select', 1, 0, 1, 2, 0, 0, 2, 2, 0, 0, 4),
(7, 1, 'menus.logout', 'users#logout', 1, 0, 1, 0, 0, 0, 0, 0, 0, 0, 2),
(8, 0, '', 'characters#list_themes', 1, 1, 1, 0, 0, 0, 0, 0, 2, 2, 0),
(9, 2, 'menus.character_status', 'characters#status', 1, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0),
(10, 2, 'menus.switch_character', 'characters#select', 1, 0, 1, 1, 0, 0, 2, 2, 0, 0, 4),
(11, 2, 'menus.talents', 'characters#talents', 1, 0, 1, 1, 2, 2, 2, 2, 0, 0, 2),
(12, 3, 'menus.graduations', 'graduations#index', 1, 0, 1, 1, 2, 2, 2, 2, 0, 0, 1),
(13, 0, '', 'graduations#graduate', 1, 1, 1, 1, 2, 2, 2, 2, 2, 2, 0),
(14, 0, '', 'characters#inventory', 1, 1, 1, 1, 0, 0, 0, 0, 2, 2, 0),
(15, 0, '', 'characters#list_images', 1, 1, 1, 1, 0, 0, 2, 2, 2, 2, 0),
(16, 3, 'menus.techniques', 'techniques', 1, 0, 1, 1, 2, 2, 2, 2, 0, 0, 2),
(17, 0, '', 'techniques#learn', 1, 0, 1, 1, 2, 2, 2, 2, 2, 2, 0),
(18, 3, 'menus.specialities', 'techniques#specialities', 1, 0, 1, 1, 2, 2, 2, 2, 0, 0, 3),
(19, 3, 'menus.abilities', 'techniques#abilities', 1, 0, 1, 1, 2, 2, 2, 2, 0, 0, 4),
(20, 3, 'menus.attribute_training', 'trainings#attributes', 1, 0, 1, 1, 2, 2, 2, 2, 0, 0, 4),
(21, 0, '', 'trainings#train_attribute', 1, 1, 1, 1, 2, 2, 2, 2, 2, 2, 0),
(22, 0, '', 'trainings#distribute_attribute', 1, 1, 1, 1, 2, 2, 2, 2, 2, 2, 0),
(23, 3, 'menus.train_techniques', 'trainings#techniques', 1, 0, 1, 1, 2, 2, 2, 2, 0, 0, 0),
(24, 3, 'menus.train_technique_status', 'trainings#technique_wait', 1, 0, 1, 1, 2, 1, 2, 2, 0, 0, 0),
(25, 0, '', 'techniques#train_speciality', 1, 1, 1, 1, 2, 2, 2, 2, 2, 2, 0),
(26, 0, '', 'techniques#train_ability', 1, 1, 1, 1, 2, 2, 2, 2, 2, 2, 0),
(27, 4, 'menus.shop', 'shop#food', 1, 0, 1, 1, 2, 2, 2, 2, 0, 0, 1),
(28, 4, 'menus.shop_weapon', 'shop#weapons', 1, 0, 1, 1, 2, 2, 2, 2, 0, 0, 2),
(29, 0, '', 'shop#buy', 1, 1, 1, 1, 2, 2, 2, 2, 2, 2, 0),
(30, 5, 'menus.battles.npc', 'battle_npcs', 1, 0, 1, 1, 2, 2, 2, 2, 0, 0, 1),
(31, 0, '', 'battle_npcs#accept', 1, 1, 1, 1, 2, 2, 2, 2, 2, 2, 0),
(32, 5, 'menus.battles.npc_in_battle', 'battle_npcs#fight', 1, 0, 1, 1, 2, 2, 1, 2, 0, 0, 1),
(33, 0, '', 'battle_npcs#ping', 1, 1, 1, 1, 2, 2, 1, 2, 2, 2, 0),
(34, 0, '', 'battle_npcs#attack', 1, 1, 1, 1, 2, 2, 1, 2, 2, 2, 0),
(35, 0, '', 'battle_npcs#modifier', 1, 1, 1, 1, 2, 2, 1, 2, 2, 2, 0),
(36, 1, '', 'users#activation', 1, 1, 2, 0, 0, 0, 0, 0, 0, 0, 0),
(37, 0, '', 'users#validate_activation', 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(38, 1, '', 'users#activate', 1, 1, 2, 0, 0, 0, 0, 0, 0, 0, 0),
(39, 1, '', 'users#account_locked', 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `menu_categories`
--

DROP TABLE IF EXISTS `menu_categories`;
CREATE TABLE IF NOT EXISTS `menu_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `h_loggedin` tinyint(4) NOT NULL DEFAULT '0',
  `h_player` tinyint(4) NOT NULL DEFAULT '0',
  `h_next_level` tinyint(4) NOT NULL DEFAULT '0',
  `h_training_technique` tinyint(4) NOT NULL DEFAULT '0',
  `h_battle_npc` tinyint(4) NOT NULL DEFAULT '0',
  `h_battle_pvp` tinyint(4) NOT NULL DEFAULT '0',
  `h_hospital` tinyint(4) NOT NULL DEFAULT '0',
  `h_time_quest` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `menu_categories`
--

INSERT INTO `menu_categories` (`id`, `name`, `h_loggedin`, `h_player`, `h_next_level`, `h_training_technique`, `h_battle_npc`, `h_battle_pvp`, `h_hospital`, `h_time_quest`) VALUES
(1, 'menu_categories.main', 0, 0, 0, 0, 0, 0, 0, 0),
(2, 'menu_categories.character', 1, 0, 0, 0, 0, 0, 0, 0),
(3, 'menu_categories.academy', 1, 1, 0, 0, 0, 0, 0, 0),
(4, 'menu_categories.world', 1, 1, 0, 0, 0, 0, 0, 0),
(5, 'menu_categories.battles', 1, 0, 0, 0, 0, 0, 0, 0),
(6, 'menu_categories.guild', 1, 0, 0, 0, 0, 0, 0, 0),
(7, 'menu_categories.ranking', 1, 0, 0, 0, 0, 0, 0, 0),
(8, 'menu_categories.user', 1, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `players`
--

DROP TABLE IF EXISTS `players`;
CREATE TABLE IF NOT EXISTS `players` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `character_id` int(11) NOT NULL,
  `character_theme_id` int(11) NOT NULL,
  `character_theme_image_id` int(11) NOT NULL,
  `graduation_id` int(11) NOT NULL,
  `battle_npc_id` int(11) NOT NULL DEFAULT '0',
  `battle_pvp_id` int(11) NOT NULL DEFAULT '0',
  `time_quest_id` int(11) NOT NULL DEFAULT '0',
  `is_pvp_queued` tinyint(1) NOT NULL DEFAULT '0',
  `headline_id` int(11) NOT NULL DEFAULT '0',
  `speciality_variant_type_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `level` int(11) NOT NULL DEFAULT '1',
  `level_screen_seen` tinyint(1) NOT NULL DEFAULT '1',
  `exp` int(11) NOT NULL DEFAULT '0',
  `currency` int(11) NOT NULL DEFAULT '500',
  `hospital` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `less_life` int(11) NOT NULL DEFAULT '0',
  `less_mana` int(11) NOT NULL DEFAULT '0',
  `less_stamina` int(11) NOT NULL DEFAULT '0',
  `last_healed_at` datetime NOT NULL,
  `at_for` int(11) NOT NULL DEFAULT '0',
  `at_int` int(11) NOT NULL DEFAULT '0',
  `at_res` int(11) NOT NULL DEFAULT '0',
  `at_agi` int(11) NOT NULL DEFAULT '0',
  `at_dex` int(11) NOT NULL DEFAULT '0',
  `at_vit` int(11) NOT NULL DEFAULT '0',
  `points` int(11) NOT NULL DEFAULT '0',
  `rank` int(11) NOT NULL DEFAULT '0',
  `wins_npc` int(11) NOT NULL DEFAULT '0',
  `wins_pvp` int(11) NOT NULL DEFAULT '0',
  `losses_npc` int(11) NOT NULL DEFAULT '0',
  `losses_pvp` int(11) NOT NULL DEFAULT '0',
  `draws_npc` int(11) NOT NULL DEFAULT '0',
  `draws_pvp` int(11) NOT NULL DEFAULT '0',
  `training_total` int(11) NOT NULL DEFAULT '0',
  `training_points_spent` int(11) NOT NULL DEFAULT '0',
  `weekly_points_spent` int(11) NOT NULL DEFAULT '0',
  `removed` tinyint(1) NOT NULL DEFAULT '0',
  `technique_training_spent` int(11) NOT NULL DEFAULT '0',
  `technique_training_id` int(11) DEFAULT NULL,
  `technique_training_complete_at` datetime DEFAULT NULL,
  `technique_training_duration` int(11) DEFAULT NULL,
  `ability_variant_type_id` int(11) DEFAULT NULL,
  `won_last_battle` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `player_attributes`
--

DROP TABLE IF EXISTS `player_attributes`;
CREATE TABLE IF NOT EXISTS `player_attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL,
  `at_for` int(11) NOT NULL DEFAULT '0',
  `at_int` int(11) NOT NULL DEFAULT '0',
  `at_res` int(11) NOT NULL DEFAULT '0',
  `at_agi` int(11) NOT NULL DEFAULT '0',
  `at_dex` int(11) NOT NULL DEFAULT '0',
  `at_vit` int(11) NOT NULL DEFAULT '0',
  `sum_at_for` int(11) NOT NULL DEFAULT '0',
  `sum_at_int` int(11) NOT NULL DEFAULT '0',
  `sum_at_res` int(11) NOT NULL DEFAULT '0',
  `sum_at_agi` int(11) NOT NULL DEFAULT '0',
  `sum_at_dex` int(11) NOT NULL DEFAULT '0',
  `sum_at_vit` int(11) NOT NULL DEFAULT '0',
  `sum_for_life` int(11) NOT NULL DEFAULT '0',
  `sum_for_mana` int(11) NOT NULL DEFAULT '0',
  `sum_for_stamina` int(11) NOT NULL DEFAULT '0',
  `sum_for_atk` int(11) NOT NULL DEFAULT '0',
  `sum_for_def` int(11) NOT NULL DEFAULT '0',
  `sum_for_crit` int(11) NOT NULL DEFAULT '0',
  `sum_for_abs` int(11) NOT NULL DEFAULT '0',
  `sum_for_prec` int(11) NOT NULL DEFAULT '0',
  `sum_for_inti` int(11) NOT NULL DEFAULT '0',
  `sum_for_conv` int(11) NOT NULL DEFAULT '0',
  `sum_for_init` int(11) NOT NULL DEFAULT '0',
  `sum_for_hit` int(11) NOT NULL DEFAULT '0',
  `sum_bonus_attribute_training_cost` int(11) NOT NULL DEFAULT '0',
  `sum_bonus_stamina_consume` int(11) NOT NULL DEFAULT '0',
  `sum_bonus_stamina_max` int(11) NOT NULL DEFAULT '0',
  `sum_bonus_training_earn` int(11) NOT NULL DEFAULT '0',
  `sum_bonus_training_exp` int(11) NOT NULL DEFAULT '0',
  `sum_for_inc_crit` int(11) NOT NULL DEFAULT '0',
  `sum_for_inc_abs` int(11) NOT NULL DEFAULT '0',
  `sum_bonus_food_discount` int(11) NOT NULL DEFAULT '0',
  `sum_bonus_mana_consume` int(11) NOT NULL,
  `sum_bonus_weapon_discount` int(11) NOT NULL DEFAULT '0',
  `sum_bonus_luck_discount` int(11) NOT NULL DEFAULT '0',
  `sum_bonus_cooldown` int(11) NOT NULL DEFAULT '0',
  `sum_bonus_stamina_heal` int(11) NOT NULL DEFAULT '0',
  `sum_bonus_exp_fight` int(11) NOT NULL DEFAULT '0',
  `sum_bonus_currency_fight` int(11) NOT NULL DEFAULT '0',
  `sum_bonus_quest_time` int(11) NOT NULL DEFAULT '0',
  `sum_bonus_food_heal` int(11) NOT NULL DEFAULT '0',
  `sum_bonus_npc_in_quests` int(11) NOT NULL DEFAULT '0',
  `sum_bonus_daily_npc` int(11) NOT NULL DEFAULT '0',
  `sum_bonus_map_npc` int(11) NOT NULL DEFAULT '0',
  `sum_bonus_drop` int(11) NOT NULL DEFAULT '0',
  `exp_battle` int(11) NOT NULL DEFAULT '0',
  `currency_battle` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `player_battle_counters`
--

DROP TABLE IF EXISTS `player_battle_counters`;
CREATE TABLE IF NOT EXISTS `player_battle_counters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL,
  `total_npc_made` int(11) NOT NULL DEFAULT '0',
  `total_pvp_made` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `player_battle_pvp_logs`
--

DROP TABLE IF EXISTS `player_battle_pvp_logs`;
CREATE TABLE IF NOT EXISTS `player_battle_pvp_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL DEFAULT '0',
  `enemy_id` int(11) NOT NULL DEFAULT '0',
  `wins` int(11) NOT NULL DEFAULT '0',
  `losses` int(11) NOT NULL DEFAULT '0',
  `draws` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `player_id_enemy_id` (`player_id`,`enemy_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `player_battle_stats`
--

DROP TABLE IF EXISTS `player_battle_stats`;
CREATE TABLE IF NOT EXISTS `player_battle_stats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) DEFAULT '0',
  `wins_pvp` int(11) DEFAULT '0',
  `wins_npc` int(11) DEFAULT '0',
  `looses_pvp` int(11) DEFAULT '0',
  `looses_npc` int(11) DEFAULT '0',
  `draws_pvp` int(11) DEFAULT '0',
  `draws_npc` int(11) DEFAULT '0',
  `wins_pvp_weekly` int(11) DEFAULT '0',
  `wins_npc_weekly` int(11) DEFAULT '0',
  `looses_pvp_weekly` int(11) DEFAULT '0',
  `looses_npc_weekly` int(11) DEFAULT '0',
  `draws_pvp_weekly` int(11) DEFAULT '0',
  `draws_npc_weekly` int(11) DEFAULT '0',
  `wins_pvp_monthly` int(11) DEFAULT '0',
  `wins_npc_monthly` int(11) DEFAULT '0',
  `looses_pvp_monthly` int(11) DEFAULT '0',
  `looses_npc_monthly` int(11) DEFAULT '0',
  `draws_pvp_monthly` int(11) DEFAULT '0',
  `draws_npc_monthly` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `player_id` (`player_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `player_items`
--

DROP TABLE IF EXISTS `player_items`;
CREATE TABLE IF NOT EXISTS `player_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `level` int(11) NOT NULL DEFAULT '1',
  `variant_type_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT '1',
  `removed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `player_item_stats`
--

DROP TABLE IF EXISTS `player_item_stats`;
CREATE TABLE IF NOT EXISTS `player_item_stats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_item_id` int(11) NOT NULL,
  `exp` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `player_luck_logs`
--

DROP TABLE IF EXISTS `player_luck_logs`;
CREATE TABLE IF NOT EXISTS `player_luck_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `player_quest_counters`
--

DROP TABLE IF EXISTS `player_quest_counters`;
CREATE TABLE IF NOT EXISTS `player_quest_counters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL,
  `total` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `player_stats`
--

DROP TABLE IF EXISTS `player_stats`;
CREATE TABLE IF NOT EXISTS `player_stats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL,
  `npc_anime_id` int(11) NOT NULL DEFAULT '0',
  `npc_character_id` int(11) NOT NULL DEFAULT '0',
  `npc_character_theme_id` int(11) NOT NULL DEFAULT '0',
  `npc_character_theme_image` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` int(11) NOT NULL,
  `email` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `gender` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `user_key` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `activation_key` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `ip_lock` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `last_login_ip` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `last_login_at` datetime NOT NULL,
  `removed` tinyint(1) NOT NULL DEFAULT '0',
  `credits` int(11) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `activated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `user_character_themes`
--

DROP TABLE IF EXISTS `user_character_themes`;
CREATE TABLE IF NOT EXISTS `user_character_themes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `character_theme_id` int(11) NOT NULL,
  `price_currency` int(11) NOT NULL DEFAULT '0',
  `price_vip` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `user_character_theme_images`
--

DROP TABLE IF EXISTS `user_character_theme_images`;
CREATE TABLE IF NOT EXISTS `user_character_theme_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
