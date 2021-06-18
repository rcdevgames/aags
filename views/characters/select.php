<?php echo partial('shared/title', array('title' => 'characters.select.title', 'place' => 'characters.select.title')) ?>
<?php if (!sizeof($players)): ?>
	<?php echo partial('shared/info', array('id'=> 3, 'title' => 'characters.select.none', 'message' => t('characters.select.none_msg', array('url' => make_url('characters#create'))))) ?>
<?php else: ?>
	<?php if (isset($_GET['created'])): ?>
		<?php echo partial('shared/info', array('id'=> 3, 'title' => 'characters.create.created', 'message' => t('characters.create.created_msg'))) ?>
	<?php endif ?>
	<?php if (isset($_GET['deleted'])): ?>
		<?php echo partial('shared/info', array('id'=> 3, 'title' => 'characters.remove.success', 'message' => t('characters.remove.success_msg'))) ?>
	<?php endif ?>
	<?php if (isset($_GET['deleted_ok'])): ?>
		<?php echo partial('shared/info', array('id'=> 3, 'title' => 'characters.removed.success', 'message' => t('characters.removed.success_msg'))) ?>
	<?php endif ?>
	<div style="width: 730px; position: relative;">
		<div style="width:231px; height:300px; float: left; position: relative; top: 20px;">
			<img src="imagens/Profile/1.jpg" width="235" height="281" id="current-player-image" />
		</div>
		<div style="width:495px; height:300px; float: left; position: relative; top: 30px;">
			<div style="float: left; width: 495px;">
				<div class="titulo-home2"><p>Dados do Personagem</p></div>
			</div>
			<div style="float: left; width: 255px; text-align: left; position: relative; top: 25px;" id="current-player-info">
				<div class="laranja name" style="font-size:16px;">--</div>
				<div class="box_level level">
					--
				</div>
				<div style="float: left; position: relative; top: 15px; left: 5px;">
					<div class="b4">
						<?php echo t('characters.select.labels.graduation') ?>: <span class="cinza graduation">--</span>
					</div>
					<div class="bar-exp"><?php echo exp_bar(0, 0, 175) ?></div>
				</div>
				<div style="float: left; clear:both; position: relative; top: 15px;">
					<span class="branco currency"><?php echo t('characters.select.labels.currency') ?> </span>: <span class="cinza amount">--</span><br />
					<span class="branco"><?php echo t('characters.select.labels.anime') ?>: </span><span class="cinza anime">--</span>
				</div>
				<div style="float: left; clear:both; position: relative; top: 40px; width: 490px; text-align: center">
					<input class="button btn btn-primary play" type="button" value="<?php echo t('buttons.play') ?>" style="width:80px;" />
					<input class="button btn btn-danger remove" type="button" value="<?php echo t('buttons.remove') ?>" style="width:80px;" data-message="<?php echo t('characters.select.delete_confirmation') ?>" />
				</div>
			</div>
			<div style="float: left; width: 240px; text-align: left; position: relative; top: 20px;" id="current-player-attributes">
				<div class="bg_td2">
					<div class="atr_float"  style="width: 24px; text-align:left; left: 10px; position:relative;">
						<img src="<?php echo image_url('icons/for_life.png') ?>" style="margin-top:-6px;" />
					</div>
					<div class="amarelo atr_float" style="width: 90px; text-align:left; padding-left:16px;">Vida</div>
					<div class="atr_float bar-life" style="margin-top: 7px">
						<?php echo exp_bar(0, 0, 110) ?>
					</div>
				</div>
				<div class="bg_td2">
					<div class="atr_float"  style="width: 24px; text-align:left; left: 10px; position:relative;">
						<img src="<?php echo image_url('icons/for_mana.png') ?>" style="margin-top:-6px;" />
					</div>
					<div class="amarelo atr_float" style="width: 90px; text-align:left; padding-left:16px;">Chakra</div>
					<div class="atr_float bar-mana" style="margin-top: 7px">
						<?php echo exp_bar(0, 0, 110) ?>
					</div>
				</div>
				<div class="bg_td2">
					<div class="atr_float"  style="width: 24px; text-align:left; left: 10px; position:relative;">
						<img src="<?php echo image_url('icons/for_stamina.png') ?>" style="margin-top:-6px;" />
					</div>
					<div class="amarelo atr_float" style="width: 90px; text-align:left; padding-left:16px;">Stamina</div>
					<div class="atr_float bar-stamina" style="margin-top: 7px">
						<?php echo exp_bar(0, 0, 110) ?>
					</div>
				</div>		
			</div>
		</div>
		<div style="position: relative; clear: both; float: left; top: 20px;">
			<div class="barra-secao"><p><?php echo t('characters.select.section_favorite') ?></p></div>
			<div style="width: 426px; float: left;" id="select-player-list-container">
				<?php foreach ($players as $player): ?>
					<a style="float: left; position: relative;" data-id="<?php echo $player->id ?>" class="player">
						<?php echo $player->small_image() ?>
					</a>					
				<?php endforeach ?>
			</div>
			<div style="position: relative; top: 10px; float:right">
				<img src="<?php echo image_url('banner.jpg') ?>" />
			</div>
		</div>
	</div>
	<script type="text/javascript">
		var	_players	= [];

		<?php foreach ($players as $player): ?>
			_players[<?php echo $player->id ?>]	= {
				name:			"<?php echo $player->name ?>",
				anime:			"<?php echo $player->character()->anime()->description()->name ?>",
				level:			<?php echo $player->level ?>,
				exp:			<?php echo $player->exp ?>,
				profile:		"<?php echo image_url($player->profile_image(true)) ?>",
				currency:		"<?php echo t('currencies.' . $player->character()->anime_id) ?>",
				amount:			<?php echo $player->currency ?>,
				graduation:		"<?php echo $player->graduation()->description()->name ?>",
				exp:			<?php echo $player->exp ?>,
				level_exp:		<?php echo $player->level_exp() ?>,
				life:			<?php echo $player->for_life() ?>,
				mana:			<?php echo $player->for_mana() ?>,
				stamina:		<?php echo $player->for_stamina() ?>,
				max_life:		<?php echo $player->for_life(true) ?>,
				max_mana:		<?php echo $player->for_mana(true) ?>,
				max_stamina:	<?php echo $player->for_stamina(true) ?>
			}
		<?php endforeach ?>		
	</script>
<?php endif ?>