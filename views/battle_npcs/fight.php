<?php echo partial('shared/title_battle', array('title' => 'battles.npc.fight.title', 'place' => 'battles.npc.fight.title')) ?>
<?php
	echo partial('shared/fight_panel', [
		'player'		=> $player,
		'enemy'			=> $npc,
		'techniques'	=> $techniques,
		'target_url'	=> $target_url,
		'log'			=> $log
	]);
?>