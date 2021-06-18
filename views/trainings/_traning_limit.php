<div class="msg-container">
	<div class="msg_top"></div>	
	 <div class="msg_repete">
		<div class="msg" style="background:url(<?php echo image_url('msg/'. $player->character()->anime_id . '-2.png')?>); background-repeat: no-repeat;">
		</div>
		<div class="msgb" style="position:relative; margin-left: 231px; text-align: left; top: -37px">
			<b><?php echo t('attributes.attributes.weekly_limit') ?></b>
			<div class="content" id="basic-training-info-container">
				<?php echo t('attributes.attributes.info') ?><br /><br />
				<?php echo exp_bar($player->weekly_points_spent, $player->max_attribute_training(), 455, $player->weekly_points_spent . ' / ' . $player->max_attribute_training()) ?>
				<?php if ($_POST): ?>
					<div id="basic-training-complete">
						<br />
						<b><?php echo t('attributes.attributes.info_finished') ?></b><br /><br />
						<span style="font-weight: bold;"><?php echo t('attributes.attributes.you_won') ?></span>
						<span class="verde exp"><?php echo t('attributes.attributes.exp', ['exp' => $earn_exp]) ?></span> e
						<span class="verde points"><?php echo t('attributes.attributes.points', ['points' => $earn_points]) ?></span>.<br />
						<span style="font-weight: bold;"><?php echo t('attributes.attributes.you_spent') ?></span>
						<span class="vermelho spent-mana"><?php echo $spent_mana ?></span>
						<img width="16" src="<?php echo image_url('icons/for_mana.png') ?>" />
						<span class="vermelho spent-stamina"><?php echo $spent_stamina ?></span>
						<img width="16" src="<?php echo image_url('icons/for_stamina.png') ?>" />
					</div>
				<?php endif ?>
			</div>	
		</div>		
	</div>
	<div class="msg_bot"></div>	
	<div class="msg_bot2"></div>	
</div>
