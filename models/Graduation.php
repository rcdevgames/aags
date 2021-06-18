<?php
	class Graduation extends Relation {
		static	$always_cached	= true;

		function description() {
			return GraduationDescription::find_first('graduation_id=' . $this->id . ' AND language_id=' . $_SESSION['language_id'], array('cache' => true));
		}

		function has_requirement($player) {
			$ok				= true;
			$log			= '<ul class="requirement-list">';
			$error			= '<li class="error"><span class="glyphicon glyphicon-remove"></span> %result</li>';
			$success		= '<li class="success"><span class="glyphicon glyphicon-ok"></span> %result</li>';
			$quest_counters	= $player->quest_counters();

			if($this->req_level) {
				$ok		= $this->req_level > $player->level ? false : $ok;
				$log	.= str_replace('%result', t('graduations.requirements.level', array('level' => $this->req_level)), $this->req_level > $player->level ? $error : $success);
			}

			if($this->req_quest_count) {
				$ok		= $this->req_quest_count > $quest_counters->total ? false : $ok;
				$log	.= str_replace('%result', t('graduations.requirements.quest_count', array('count' => $this->req_quest_count)), $this->req_quest_count > $quest_counters->total ? $error : $success);
			}

			if($this->req_training_points) {
				$ok		= false;
				$log	.= str_replace('%result', t('graduations.requirements.training_points', array('count' => $this->req_training_points)), $error);
			}

			if($this->req_wins_pvp) {
				$ok		= false;
				$log	.= str_replace('%result', t('graduations.requirements.wins_pvp', array('count' => $this->req_wins_pvp)), $error);
			}

			if($this->req_wins_npc) {
				$ok		= false;
				$log	.= str_replace('%result', t('graduations.requirements.wins_npc', array('count' => $this->req_wins_npc)), $error);
			}

			if($this->req_technique_count) {
				$ok		= false;
				$log	.= str_replace('%result', t('graduations.requirements.technique_count', array('count' => $this->req_technique_count)), $error);
			}

			for($f = 1; $f <= 5; $f++) {
				$property	= 'req_technique_l' . $f . '_count';

				if($this->$property) {
					$ok		= false;
					$log	.= str_replace('%result', t('graduations.requirements.technique_l' . $f . '_count', array('count' => $this->$property)), $error);
				}
			}

			$log	.= '</ul>';

			return array('has_requirement' => $ok, 'requirement_log' => $log);
		}
	}