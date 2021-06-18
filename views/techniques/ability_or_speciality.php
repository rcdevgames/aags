<?php if ($ability): ?>
	<?php echo partial('shared/title', array('title' => 'abilities.index.title', 'place' => 'abilities.index.title')) ?>
<?php else: ?>
	<?php echo partial('shared/title', array('title' => 'specialities.index.title', 'place' => 'specialities.index.title')) ?>	
<?php endif ?>
<?php
	echo partial('ability_or_speciality_info', array(
		'types'			=> $types,
		'ability'		=> $ability,
		'checker_field'	=> $checker_field,
		'translate_key'	=> $translate_key,
		'player'		=> $player
	));
?>
<div id="learn-ability-speciality-info-container"></div>
<div class="tab-content" id="ability-speciality-container">
	<div class="barra-secao barra-secao-<?php echo $player->character()->anime_id ?>">
		<table width="725" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td width="96">&nbsp;</td>
			<td align="center">Nome / Descrição</td>
			<td width="140" align="center">Requerimentos</td>
			<td width="117" align="center">Status</td>
		</tr>
		</table>
	</div>
	<?php foreach ($types as $type): ?>
		<?php $counter = 0; ?>
		<div class="tab-pane" id="tab-<?php echo $type->id ?>">
			<table width="725" border="0" cellpadding="0" cellspacing="0" style="padding-left:5px">
				<?php foreach ($items as $item): ?>
					<?php
						if($player->$checker_method($item)) {
							continue;
						}

						$color	= $counter++ % 2 ? '091e30' : '173148';

						$item->apply_variant($type->id);
						extract($item->has_requirement($player));
					?>
					<tr bgcolor="#<?php echo $color ?>" id="learn-status-<?php echo $type->id ?>-<?php echo $item->id ?>">
						<td width="96" align="center">
							<img src="<?php echo image_url($item->image(true)) ?>" class="technique-popover" data-source="#technique-content-<?php echo $type->id ?>-<?php echo $item->id ?>" data-title="<?php echo $item->description()->name ?>" data-trigger="hover" data-placement="right" />
							<div class="technique-container" id="technique-content-<?php echo $type->id ?>-<?php echo $item->id ?>">
								<?php echo $item->technique_tooltip() ?>
							</div>
						</td>
						<td align="left">
							<b class="amarelo" style="font-size:14px; position: relative; top: 5px;"><?php echo $item->description()->name ?></b><hr />
							<span><?php echo $item->description()->description ?></span>
							<br /><br />
						</td>
						<td width="140" align="center">
							<img src="<?php echo image_url('requer.png') ?>" class="requirement-popover" data-source="#requirement-content-<?php echo $type->id ?>-<?php echo $item->id ?>" data-title="<?php echo t('popovers.titles.requirements') ?>" data-trigger="hover" data-placement="left" />
							<div class="requirement-container" id="requirement-content-<?php echo $type->id ?>-<?php echo $item->id ?>"><?php echo $requirement_log ?></div>
						</td>
						<td width="117" align="center">
							<?php if ($has_requirement && !$player->$checker_method($item)): ?>
								<input type="button" class="btn btn-primary learn" value="<?php echo t('techniques.index.learn') ?>" data-id="<?php echo $item->id ?>" data-target="#learn-status-<?php echo $type->id ?>-<?php echo $item->id ?>" data-ability="<?php echo $ability ? 1 : 0 ?>" />
							<?php else: ?>
								<a class="btn btn-primary disabled"><?php echo t('techniques.index.learn') ?></a>
							<?php endif ?>
						</td>
					</tr>
					<tr height="4"></tr>
				<?php endforeach ?>				
			</table>
		</div>
	<?php endforeach ?>
</div>