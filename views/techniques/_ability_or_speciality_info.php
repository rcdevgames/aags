<div class="msg-container" id="ability-speciality-learn-container">
	<div class="msg_top"></div>
	 <div class="msg_repete">
		<div class="msg" style="background:url(<?php echo image_url('msg/'. Player::get_instance()->character()->anime_id . '-1.png')?>); background-repeat: no-repeat;">
		</div>
		<div class="msgb" style="position:relative; margin-left: 231px; text-align: left; top: -37px">
			<b><?php echo t($translate_key . 'info_text_title') ?></b>
			<div class="content">
				<p><?php echo t($translate_key . 'info_text') ?></p><br/>
				<ul class="" id="ability-speciality-container-tabs" style="width: 500px; float: left; height:40px">
					<?php $has_active	= false ?>
					<?php foreach ($types as $type): ?>
						<li style="float: left; margin-left: 3px;" class="<?php echo (!$player->$checker_field && !$has_active) || ($player->$checker_field && $player->$checker_field == $type->id) ? 'default' : '' ?>">
							<?php $has_active = true ?>
							<a class="btn btn-default" data-id="<?php echo $type->id ?>" href="#tab-<?php echo $type->id ?>"><?php echo $type->description()->name ?></a>
						</li>
					<?php endforeach ?>
				</ul>
				<br />
				<?php foreach ($types as $type): ?>
					<div style="display: none" class="description" id="ability-speciality-container-description-<?php echo $type->id ?>">
						<p><?php echo $type->description()->description ?></p><br />
						<?php if ($player->$checker_field): ?>
							<?php if ($player->$checker_field != $type->id): ?>
								<a class="btn btn-primary learn-master" data-confirmation="<?php echo t($translate_key . 'confirmation') ?>" data-id="<?php echo $type->id ?>" data-ability="<?php echo $ability ? 1 : 0 ?>" href="javascript:;"><?php echo t($translate_key . 'switch_button') ?></a>
							<?php else: ?>
								<a class="btn btn-success"><?php echo t($translate_key . 'learned_button') ?></a>
							<?php endif ?>
						<?php else: ?>
							<a class="btn btn-primary learn-master" data-confirmation="<?php echo t($translate_key . 'confirmation') ?>" data-id="<?php echo $type->id ?>" data-ability="<?php echo $ability ? 1 : 0 ?>" href="javascript:;"><?php echo t($translate_key . 'learn_button') ?></a>
						<?php endif ?>
					</div>
				<?php endforeach ?>
			</div>
		</div>		
	</div>
	<div class="msg_bot"></div>	
	<div class="msg_bot2"></div>
</div>
