<?php echo partial('shared/title', array('title' => 'characters.create.title', 'place' => 'characters.create.title')) ?>
<form id="f-create-character">
	<input type="hidden" name="character_id" value="" />
	<div id="character-creation-container">
		<div id="character-data">
			<div style="width:231px; height:300px; float: left; position: relative; top: 20px; text-align: center ">
				<img src="imagens/Profile/1.jpg" width="235" height="281" id="character-profile-image" />
				<input class="button btn btn-warning" id="change-theme" type="button" value="<?php echo t('characters.create.change_theme') ?>" style="position:relative; top: -30px" />
			</div>
			<div style="width:495px; height:300px; float: left; position: relative; top: 30px;">
				<div style="float: left; width: 495px;">
					<div class="titulo-home2"><p>Dados do Personagem</p></div>
				</div>
				<div id="character-info" style="float: left; width: 255px; text-align: left; position: relative; top: 13px; line-height: 31px;">
					<div class="row">
						<div class="col-lg-2">
							<labeL class="branco" style="margin-top: 7px"><?php echo t('characters.create.labels.name') ?>:</labeL>
						</div>
						<div class="col-lg-9" style="height: 30px">
							<input type="text" name="name" placeholder="Nome do personagem" class="form-control" /><br />
						</div>
					</div>
					<span class="branco"><?php echo t('characters.create.labels.anime') ?>:</span> <span class="cinza anime">--</span><br />
					<span class="branco"><?php echo t('characters.create.labels.anime_totals') ?>:</span> <span class="cinza anime_totals">--</span><br />
					<span class="branco"><?php echo t('characters.create.labels.character') ?>:</span> <span class="cinza character">--</span><br />
					<span class="branco"><?php echo t('characters.create.labels.character_totals') ?>:</span> <span class="cinza character_totals">--</span>
					<div class="break"></div>
					<input type="submit" class="btn btn-primary" value="<?php echo t('characters.create.submit') ?>" style="position:relative; left: 60px; top: 10px;"/>
				</div>
				<div id="character-attributes" style="float: left; width: 240px; text-align: left; position: relative; top: 10px;">
					<?php foreach ($attributes as $_ => $at): ?>
						<div class="bg_td2 <?php echo $_ ?>">
							<div class="atr_float" style="width: 20px; text-align:left; left: 10px; position:relative;">
								<img src="<?php echo image_url('icons/' . $_ . '.png') ?>" style="margin-top:-6px;" />
							</div>
							<div class="amarelo atr_float" style="width: 90px; text-align:left; padding-left:18px;"><?php echo $at ?></div>
							<div class="atr_float bar bar-<?php echo $_ ?>" style="margin-top: 7px">
								<?php echo exp_bar(0, 0, 110) ?>
							</div>							
						</div>					
					<?php endforeach ?>			
				</div>
			</div>
			<div class="break"></div>
		</div>
		<div id="anime-list">
			<div class="barra-secao"><p><?php echo t('characters.create.section_anime') ?></p></div>
			<?php foreach ($animes as $anime): ?>
				<a class="anime" data-id="<?php echo $anime->id ?>">
					<img src="<?php echo image_url('anime/' . $anime->id . '.jpg') ?>" alt="<?php echo $anime->description()->name ?>" />
				</a>			
			<?php endforeach ?>
			<div class="break"></div>
		</div>
		<div id="anime-character-list">
			<div class="barra-secao"><p><?php echo t('characters.create.section_character') ?></p></div>
			<?php foreach ($animes as $anime): ?>
				<div id="anime-characters-<?php echo $anime->id ?>" class="anime-characters">
				<?php foreach ($anime->characters() as $character): ?>
					<a class="character" data-id="<?php echo $character->id ?>">
						<?php echo $character->small_image() ?>
					</a>
				<?php endforeach ?>
				<div class="break"></div>
				</div>
			<?php endforeach ?>
			<div class="break"></div>
		</div>
	</div>
</form>
<script type="text/javascript">
	var	_characters	= [];
	var	_animes		= [];

	<?php foreach ($animes as $anime): ?>
		_animes[<?php echo $anime->id ?>]	= '<?php echo addslashes($anime->description()->name) ?>';

		<?php foreach ($anime->characters() as $character): ?>
			_characters[<?php echo $character->id ?>]	= {
				name:		'<?php echo addslashes($character->description()->name) ?>',
				anime:		<?php echo $anime->id ?>,
				profile:	"<?php echo image_url($character->profile_image(true)) ?>",
				at: {
					at_for:	<?php echo $character->at_for ?>,
					at_int:	<?php echo $character->at_int ?>,
					at_res:	<?php echo $character->at_res ?>,
					at_agi:	<?php echo $character->at_agi ?>,
					at_dex:	<?php echo $character->at_dex ?>,
					at_vit:	<?php echo $character->at_vit ?>
				}
			};
		<?php endforeach ?>
	<?php endforeach ?>
</script>