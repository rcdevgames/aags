<div id="popup-character-themes" data-background="<?php echo image_url('backgrounds/none.jpg') ?>">
	<?php foreach ($themes as $theme): ?>
		<div class="attack-list attack-list-<?php echo $theme->id ?>">
			<?php foreach ($theme->attacks(true) as $attack): ?>
				<div class="attack"><?php echo $attack->image() ?></div>
			<?php endforeach ?>	
		</div>
		<div class="ability-list ability-list-<?php echo $theme->id ?>">
			<?php foreach ($theme->abilities() as $ability): ?>
				<div class="attack"><?php echo $ability->image() ?></div>
				<?php break; ?>
			<?php endforeach ?>	
			<?php foreach ($theme->specialities() as $speciality): ?>
				<div class="attack"><?php echo $speciality->image() ?></div>
				<?php break; ?>
			<?php endforeach ?>	
		</div>
		<div class="theme-images card-container theme-images-<?php echo $theme->id ?>">
			<?php foreach ($theme->images() as $image): ?>
				<div class="card">
					<?php echo $image->profile_image() ?>
				</div>
			<?php endforeach ?>
		</div>
		<div class="theme-controls theme-controls-<?php echo $theme->id ?>">
			<a href="javascript:;" class="btn btn-success back"><?php echo t('characters.themes.back') ?></a>
			<?php if($player): ?>
				<?php if ($theme->is_buyable): ?>
						<?php if (!$user->is_theme_bought($theme->id)): ?>
							<?php if ($theme->price_vip || $theme->price_currency): ?>
								<?php if ($theme->price_vip): ?>
									<?php if ($user->credits >= $theme->price_vip): ?>
										<a href="javascript:;" class="btn btn-warning buy-theme" data-theme="<?php echo $theme->id ?>"><?php echo t('characters.themes.buy_vip', array('price' => $theme->price_vip)) ?></a>
									<?php else: ?>
										<a href="javascript:;" class="btn btn-warning disabled"><?php echo t('characters.themes.buy_vip', array('price' => $theme->price_vip)) ?></a>
									<?php endif ?>
								<?php else: ?>
									<?php if ($player->currency >= $theme->price_currency): ?>
										<a href="javascript:;" class="btn btn-warning buy-theme" data-theme="<?php echo $theme->id ?>">
											<?php echo t('characters.themes.buy_currency', array('price' => $theme->price_currency, 'currency' => t('currencies.' . $player->character()->anime_id))) ?>
										</a>
									<?php else: ?>
										<a href="javascript:;" class="btn btn-warning disabled">
											<?php echo t('characters.themes.buy_currency', array('price' => $theme->price_currency, 'currency' => t('currencies.' . $player->character()->anime_id))) ?>
										</a>
									<?php endif ?>
								<?php endif ?>
							<?php else: ?>
								<a href="javascript:;" class="btn btn-warning buy-theme" data-theme="<?php echo $theme->id ?>"><?php echo t('characters.themes.buy_free') ?></a>
							<?php endif ?>
						<?php else: ?>
							<a href="javascript:;" class="btn btn-warning disabled"><?php echo t('characters.themes.already_bought') ?></a>
						<?php endif ?>
				<?php else: ?>
					<?php if (!$theme->is_default): ?>
						<a href="javascript:;" class="btn btn-warning disabled"><?php echo t('characters.themes.unavailable') ?></a>					
					<?php endif ?>
				<?php endif ?>
			<?php endif ?>
		</div>
	<?php endforeach ?>
	<div id="hability-list"></div>
	<div id="bar-theme">
		<?php echo section_bar(t('characters.themes.choose'), $character->anime_id) ?>		
	</div>
	<div id="bar-images">
		<?php echo section_bar(t('characters.themes.images'), $character->anime_id) ?>		
	</div>
	<div id="theme-list" class="card-container">
		<?php foreach ($themes as $theme): ?>
			<div class="card theme"
				data-theme="<?php echo $theme->id ?>"
				data-buyable="<?php echo $theme->is_buyable ?>"
				data-vip="<?php echo $theme->price_vip ?>"
				data-currency="<?php echo $theme->price_currency ?>"
				data-background="<?php echo image_url($theme->background_image(true)) ?>"
				data-toggle="tooltip"
				title="<?php echo $theme->description()->name ?><br /><?php echo t('characters.themes.theme_info_click') ?>">
				<?php echo $theme->profile_image() ?>
				<?php if ($player): ?>
					<?php if ($user->is_theme_bought($theme->id) || $theme->is_default): ?>
						<?php if ($theme->id == $player->character_theme_id): ?>
							<a href="javascript:;" class="btn btn-warning disabled" data-theme="<?php echo $theme->id ?>"><?php echo t('characters.themes.use_this') ?></a>
						<?php else: ?>
							<a href="javascript:;" class="btn btn-warning use-theme" data-theme="<?php echo $theme->id ?>"><?php echo t('characters.themes.use_this') ?></a>
						<?php endif ?>
					<?php endif ?>
				<?php endif ?>
			</div>
		<?php endforeach ?>		
	</div>
</div>