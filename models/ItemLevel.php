<?php
	class ItemLevel extends Relation {
		static	$always_cached	= true;

		function parse($stats, $player) {
			$ok		= false;
			$req	= '';

			if($this->req_use) {
				$need	= $this->req_use;
				$have	= $stats->uses;
				$req	.= t('techniques.tooltip.level_req.req_use', array('count' => $this->req_use, 'have' => $have));

				if($stats->uses >= $this->req_use) {
					$ok	= true;
				}
			}

			if($this->req_kills) {
				$need	= $this->req_kills;
				$have	= $stats->kills;
				$req	.= t('techniques.tooltip.level_req.req_kills', array('count' => $this->req_kills, 'have' => $have));

				if($stats->kills >= $this->req_kills) {
					$ok	= true;
				}
			}

			if($this->req_kills_with_crit) {
				$need	= $this->req_kills_with_crit;
				$have	= $stats->kills_with_crit;
				$req	.= t('techniques.tooltip.level_req.req_kills_with_crit', array('count' => $this->req_kills_with_crit, 'have' => $have));

				if($stats->kills_with_crit >= $this->req_kills_with_crit) {
					$ok	= true;
				}
			}

			if($this->req_kills_with_precision) {
				$need	= $this->req_kills_with_precision;
				$have	= $stats->kills_with_precision;
				$req	.= t('techniques.tooltip.level_req.req_kills_with_precision', array('count' => $this->req_kills_with_precision, 'have' => $have));

				if($stats->kills_with_precision >= $this->req_kills_with_precision) {
					$ok	= true;
				}
			}

			if($this->req_use_low_stat) {
				$need	= $this->req_use_low_stat;
				$have	= $stats->use_low_stat;
				$req	.= t('techniques.tooltip.level_req.req_use_low_stat', array(
					'count'	=> $this->req_use_low_stat,
					'have'	=> $have,
					'mana'	=> t('formula.for_mana.' . $player->character()->anime_id)
				));

				if($stats->use_low_stat >= $this->req_use_low_stat) {
					$ok	= true;
				}
			}

			if($this->req_full_defenses) {
				$need	= $this->req_full_defenses;
				$have	= $stats->full_defenses;
				$req	.= t('techniques.tooltip.level_req.req_full_defenses', array('count' => $this->req_full_defenses, 'have' => $have));

				if($stats->full_defenses >= $this->req_full_defenses) {
					$ok	= true;
				}
			}

			return array('req' => $req, 'ok' => $ok);
		}
	}