<div class="technique-data">
	<div class="type <?php echo $type_class ?>">
		<?php echo $type ?> -
		<span class="<?php echo $unique_class ?>"><?php echo $unique ?></span>
	</div>
	<div class="description"><?php echo $description->description ?></div>
	<hr />
	<table border="0" width="100%">
		<?php if ($item->is_buff): ?>
			<?php foreach (buff_properties() as $buff_property): ?>
				<?php
					if(!(float)($item->{$buff_property->field})) {
						continue;
					}

					$closure	= $buff_property->formatter;
				?>
				<tr>
					<td class="col-lg-7">
						<img src="<?php echo image_url($buff_property->image) ?>" />
						<?php echo $buff_property->name ?>
					</td>
					<td class="col-lg-5"><?php echo $closure($item, $buff_property) ?></td>
				</tr>
			<?php endforeach ?>
		<?php else: ?>
			<tr><td colspan="2" class="text-only"><?php echo t('techniques.combat_values') ?></td></tr>
			<?php if ($item->is_defensive): ?>
				<tr>
					<td class="col-lg-7">
						<img src="<?php echo image_url('icons/for_def.png') ?>" />
						<?php echo t('techniques.defense') ?>
					</td>
					<td class="col-lg-5">
						<?php echo $formula->base->defense ?>
						<?php if ($formula->level->for_def): ?>
							<span class="enhancer-value">
								( +<?php echo $formula->level->for_def ?> )
							</span>
						<?php endif ?>
					</td>
				</tr>
			<?php else: ?>
				<tr>
					<td class="col-lg-7">
						<img src="<?php echo image_url('icons/for_atk.png') ?>" />
						<?php echo t('techniques.attack') ?>
					</td>
					<td class="col-lg-5">
						<?php echo $formula->base->damage ?>
						<?php if ($formula->level->for_atk): ?>
							<span class="enhancer-value">
								( +<?php echo $formula->level->for_atk ?> )
							</span>
						<?php endif ?>
					</td>
				</tr>
			<?php endif ?>
		<?php endif ?>
		<?php if ($formula->consume_mana): ?>
			<tr>
				<td class="col-lg-7">
					<img src="<?php echo image_url('icons/for_mana.png') ?>" />
					<?php echo t('techniques.consume', array('name' => t('formula.for_mana.' . $item->anime()->id))) ?>
				</td>
				<td class="col-lg-5">
					<?php echo $formula->consume_mana ?>
					<?php if ($formula->level->for_mana): ?>
						<span class="enhancer-value">
							( +<?php echo $formula->level->for_mana ?> )
						</span>
					<?php endif ?>
				</td>
			</tr>
		<?php endif ?>
		<?php if ($formula->cooldown): ?>
			<tr>
				<td class="col-lg-7">
					<img src="<?php echo image_url('icons/for_mana.png') ?>" />
					<?php echo t('techniques.cooldown') ?>
				</td>
				<td class="col-lg-5">
					<?php echo $formula->base->cooldown ?>
					<?php if ($formula->level->cooldown): ?>
						<span class="enhancer-value">
							( +<?php echo $formula->level->cooldown ?> )
						</span>
					<?php endif ?>
				</td>
			</tr>
		<?php endif ?>
		<?php if ($formula->is_player_item && $item->item_type_id == 1): ?>
			<tr>
				<td class="col-lg-7">
					<img src="<?php echo image_url('icons/for_prec.png') ?>" />
					<?php echo t('formula.for_hit') ?>
				</td>
				<td class="col-lg-5">
					<?php if (in_array($item->id, [112, 113])): ?>
						<?php echo exp_bar(1, 1, 110, '100%') ?>						
					<?php else: ?>
						<?php
							$current_hit	= $formula->hit_chance > $item->req_for_hit_chance ? $item->req_for_hit_chance : $formula->hit_chance;
							$percent		= as_percent($current_hit, $item->req_for_hit_chance);
						?>
						<?php echo exp_bar($formula->hit_chance, $item->req_for_hit_chance, 110, $current_hit . ' / ' . $item->req_for_hit_chance . ' (' . $percent . '%)') ?>
					<?php endif ?>
				</td>
			</tr>
		<?php endif ?>
	</table>
	
	<?php if ($item->is_buff): ?>
	<hr />
		<div>
			<span class="glyphicon glyphicon-star"></span>
			Não passa turno ao utilizar
		</div>
	<?php endif ?>
</div>