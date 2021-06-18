<?php echo partial('shared/title', array('title' => 'techniques.index.title', 'place' => 'techniques.index.title')) ?>
<div id="learn-technique-info-container"></div>
<div class="barra-secao barra-secao-<?php echo $player->character()->anime_id ?>">
	<table width="725" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="85">&nbsp;</td>
		<td align="center"><?php echo t('techniques.index.header.name') ?></td>
		<td width="140" align="center"><?php echo t('techniques.index.header.requirements') ?></td>
		<td width="117" align="center"><?php echo t('techniques.index.header.status') ?></td>
	</tr>
	</table>
</div>
<table id="technique-list" width="725" border="0" cellpadding="0" cellspacing="0" style="padding-left:5px">
<?php $counter = 0; ?>
<?php foreach ($items as $item): ?>
	<?php
		if($player->has_technique($item)) {
			continue;
		}

		$color	= $counter++ % 2 ? '091e30' : '173148';
		extract($item->has_requirement($player));
	?>
	<tr bgcolor="<?php echo $color ?>" id="learn-status-<?php echo $item->id ?>">
		<td width="85" align="center">
			<img src="<?php echo image_url($item->image(true)) ?>" class="technique-popover" data-source="#technique-content-<?php echo $item->id ?>" data-title="<?php echo $item->description()->name ?>" data-trigger="hover" data-placement="right" />
			<div class="technique-container" id="technique-content-<?php echo $item->id ?>">
				<?php echo $item->technique_tooltip() ?>
			</div>
		</td>
		<td align="left">
			<b class="amarelo" style="font-size:14px; position: relative; top: 5px;"><?php echo $item->description()->name ?></b><hr />
			<span><?php echo $item->description()->description ?></span>
			<br /><br />
		</td>
		<td width="140" align="center">
			<img src="<?php echo image_url('requer.png') ?>" class="requirement-popover" data-source="#requirement-content-<?php echo $item->id ?>" data-title="<?php echo t('popovers.titles.requirements') ?>" data-trigger="hover" data-placement="left" />
			<div class="requirement-container" id="requirement-content-<?php echo $item->id ?>"><?php echo $requirement_log ?></div>
		</td>
		<td width="117" align="center">
			<?php if ($player->has_technique($item)): ?>
				<a class="btn btn-success disabled"><b class="glyphicon glyphicon-ok"></b><?php echo t('techniques.index.learned') ?></a>
			<?php else: ?>
				<?php if (!$has_requirement): ?>
					<a class="btn btn-primary disabled"><?php echo t('techniques.index.learn') ?></a>
				<?php else: ?>
					<input type="button" class="btn btn-primary learn" value="<?php echo t('techniques.index.learn') ?>" data-id="<?php echo $item->id ?>" />
				<?php endif ?>
			<?php endif ?>
		</td>
	</tr>
	<tr height="4"></tr>
<?php endforeach ?>
</table>