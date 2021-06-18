<div class="msg-container">
	<div class="msg_top"></div>	
	 <div class="msg_repete">
		<div class="msg" style="background:url(<?php echo image_url('msg/'. $player->character()->anime_id . '-3.png')?>); background-repeat: no-repeat;">
		</div>
		<div class="msgb" style="position:relative; margin-left: 231px; text-align: left; top: -37px">
			<b>Pontos Disponíveis</b>
			<div class="content">
				<?php if ($points): ?>
					<?php
						echo t('attributes.distribute.having_points', array(
									'total'	=> $points,
									'lack'	=> $point_exp - $current_exp
								));
					?>
				<?php else: ?>
					<?php echo t('attributes.distribute.no_points') ?>
				<?php endif ?>
				<br /><br />
				<?php echo exp_bar($current_exp, $point_exp, 455, $current_exp . ' / ' . $point_exp) ?><br /><br />
				<span class="laranja"><?php echo t('attributes.distribute.info') ?></span>
			</div>
		</div>		
	</div>
	<div class="msg_bot"></div>	
	<div class="msg_bot2"></div>	
</div>
<br />
<div class="barra-secao"><p>Distribuição dos Pontos nos Atributos</p></div>
<?php if(sizeof($errors)): ?>
	<div class="alert alert-block alert-danger" style="margin: 10px 20px">
		<a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>
		<h4><?php echo t('attributes.distribute.errors.header') ?></h4>
		<ul>
			<?php foreach ($errors as $error): ?>
				<li><?php echo $error ?></li>
			<?php endforeach ?>
		</ul>
	</div>
<?php endif; ?>
<table width="725" border="0" cellspacing="0" cellpadding="0">
	<?php $counter = 0 ?>
	<?php foreach ($attributes as $_ => $attribute): ?>
	<tr bgcolor="<?php echo $counter++ % 2 ? '#173148' : '#091e30' ?>">
		<td width="160" height="35" align="center"><span class="amarelo"><?php echo $attribute ?></span></td>
		<td width="60"><img src="<?php echo image_url('icons/' . str_replace('_trained', '', $_) . '.png') ?>" /></td>
		<td width="360"><?php echo exp_bar($player->{$_}(), $max, 350) ?></td>
		<td width="135" align="center">
			<?php if ($points): ?>
				<select name="<?php echo str_replace('_trained', '', $_) ?>_val" data-default="<?php echo t('attributes.distribute.select') ?>">
					<option value="0"><?php echo t('attributes.distribute.select') ?></option>
					<?php for($i = 1; $i <= $points; $i++): ?>
						<option value="<?php echo $i ?>"><?php echo $i ?></option>
					<?php endfor; ?>
				</select>				
			<?php else: ?>
				--
			<?php endif ?>
		</td>
		<td width="100">
			<?php if ($points): ?>
				<a class="btn btn-primary distribute" data-attribute="<?php echo str_replace('_trained', '', $_) ?>"><?php echo t('attributes.distribute.distribute') ?></a>
			<?php else: ?>
				<a class="btn btn-primary disabled"><?php echo t('attributes.distribute.distribute') ?></a>
			<?php endif ?>
		</td>
	</tr>
	<tr height="3"></tr>
	<?php endforeach; ?>
</table>
<br />
<div align="center">
	<?php if ($points): ?>
		<a class="btn btn-primary distribute-general" data-max="<?php echo $points ?>"><?php echo t('attributes.distribute.distribute_general') ?></a>
	<?php else: ?>
		<a class="btn btn-primary disabled"><?php echo t('attributes.distribute.distribute_general') ?></a>
	<?php endif ?>
</div>