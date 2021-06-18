<?php echo partial('shared/title', array('title' => 'battles.npc.title', 'place' => 'battles.npc.title')) ?>
<div>
	<div class="pull-left">
		<?php echo $player->profile_image() ?>
		<div align="center" class="nome-personagem"><?php echo $player->name ?></div>
	</div>
	<div style="float: left; padding-top: 20px">
		<img src="<?php echo image_url('battle/vs2.png')?>" />
	</div>
	<div class="pull-right">
		<?php echo $npc->profile_image() ?>
		<div align="center" class="nome-personagem"><?php echo $npc->name ?></div>
	</div>
	<div class="clearfix"></div>
</div>
<div align="center">
	<a href="javascript:;" id="btn-enter-npc-battle" class="btn btn-primary btn-lg"><?php echo t('battles.npc.accept') ?></a>
</div>
