<?php echo partial('shared/title', array('title' => 'attributes.attributes.title', 'place' => 'attributes.attributes.title')) ?>
<div id="traning-limit-container">
	<?php echo partial('traning_limit', ['player' => $player]) ?>
</div>
<br />
<div class="barra-secao">
	<table width="725" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="325" align="center"><?php echo t('attributes.attributes.headers.manual_training') ?></td>
		<td width="250" align="center"><?php echo t('attributes.attributes.headers.quantity') ?></td>
		<td width="150" align="center"><?php echo t('attributes.attributes.headers.status') ?></td>
	</tr>
	</table>
</div>
<form id="training-attribute-basic">
	<table width="725" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td align="center" width="325">
					<div><?php echo t('attributes.attributes.will_spend_mana') ?> <span class="mana">--</span> <img width="16" src="<?php echo image_url('icons/for_mana.png') ?>" /></div>
					<div><?php echo t('attributes.attributes.will_spend_stamina') ?> <span class="stamina">--</span> <img width="16" src="<?php echo image_url('icons/for_stamina.png') ?>" /></div>
			</td>
			<td align="center" width="250">
				<select name="quantity" data-consume-mana="<?php echo $consume_mana ?>" data-consume-stamina="<?php echo $consume_stamina ?>">
					<?php for($i = 5; $i <= 50; $i += 5): ?>
						<option value="<?php echo $i ?>"><?php echo $i ?></option>
					<?php endfor ?>
				</select>
			</td>
			<td align="center" width="150">
				<?php if ($player->training_points_spent < $player->max_attribute_training()): ?>
					<a class="btn btn-primary train"><?php echo t('attributes.attributes.train') ?></a>
				<?php else: ?>
					<a class="btn btn-danger disabled"><?php echo t('attributes.attributes.train') ?></a>
				<?php endif ?>
			</td>
		</tr>
	</table>
</form>
<br /><br />
<div class="barra-secao">
	<table width="725" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="325" align="center"><?php echo t('attributes.attributes.headers.automatic_training') ?></td>
		<td width="250" align="center"><?php echo t('attributes.attributes.headers.quantity') ?></td>
		<td width="150" align="center"><?php echo t('attributes.attributes.headers.status') ?></td>
	</tr>
	</table>
</div>
<table width="725" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td align="center" width="325">
			Escolha o tempo que quer deixar treinando automático, esse recurso pode ser usado sempre que você quiser.	
		</td>
		<td align="center" width="250">
			<select>
				<option value="1">Treinar por 30 Minutos</option>
				<option value="2">Treinar por 30 Minutos</option>
				<option value="3">Treinar por 30 Minutos</option>
			</select>
		</td>
		<td align="center" width="150">
			<input type="button" value="Treinar" />
		</td>
	</tr>
</table>
<br />
<div id="training-distribute-container">
	
</div>