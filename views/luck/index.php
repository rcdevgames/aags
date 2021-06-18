<?php echo partial('shared/title', array('title' => 'luck.index.title', 'place' => 'luck.index.title')) ?>
<div id="luck-container">
	<div id="daynames">
		<?php for($f = 1; $f <= 7; $f++): ?>
			<div class="dayname"><?php echo t('daynames.' . $f) ?></div>
		<?php endfor ?>
	</div>
	<div id="luck-status">
		<?php for($f = 1; $f <= 7; $f++): ?>
			<div class="day-<?php echo $f ?> day <?php echo $week_data && $week_data[$f] ? 'green' : '' ?>">
				<div class="ball"></div>
				<div class="check"></div>
			</div>
		<?php endfor ?>
	</div>
	<div id="luck-stripes">
		<div id="luck-stripe-1" class="luck-stripe"></div>
		<div id="luck-stripe-2" class="luck-stripe"></div>
		<div id="luck-stripe-3" class="luck-stripe"></div>
		<div id="luck-stripe-4" class="luck-stripe"></div>
	</div>
	<div id="luck-stripes-shadows">
		<div></div>
		<div></div>
		<div></div>
		<div></div>
	</div>
	<div id="luck-mask"></div>
	<div id="luck-types">
		<div class="daily"><?php echo t('luck.index.daily') ?></div>
		<div class="weekly"><?php echo t('luck.index.weekly') ?></div>
	</div>
	<div id="buttons">
		<div class="daily">
			<div class="button" data-type="daily" data-currency="1">
				<span><?php echo t('luck.daily.currency', array('total' => $daily_currency, 'currency' => t('currencies.' . $player->character()->anime_id))) ?></span>
			</div>
			<div class="button" data-type="daily" data-currency="2">
				<span><?php echo t('luck.daily.vip', array('total' => $daily_vip, 'vip' => t('currencies.vip'))) ?></span>
			</div>
		</div>
		<div class="weekly">
			<div class="button" data-type="weekly" data-currency="1">
				<span><?php echo t('luck.weekly.currency', array('total' => $weekly_currency, 'currency' => t('currencies.' . $player->character()->anime_id))) ?></span>
			</div>
			<div class="button" data-type="weekly" data-currency="2">
				<span><?php echo t('luck.weekly.vip', array('total' => $weekly_vip, 'vip' => t('currencies.vip'))) ?></span>
			</div>
		</div>
	</div>
	<div id="luck-button"><span><?php echo t('luck.index.play') ?></span></div>
	<div id="result"></div>
</div>
<br />
<div class="barra-secao barra-secao-<?php echo $player->character()->anime_id ?>">
	<table width="725" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td align="center"><?php echo t('luck.index.header.name') ?></td>
			<td align="center" width="120"><?php echo t('luck.index.header.chance') ?></td>
			<td align="center" width="220"><?php echo t('luck.index.header.won') ?></td>
		</tr>
	</table>
</div>
<table width="725" id="luck-reward-list">
	<?php $counter = 0; ?>
	<?php foreach ($reward_list->result() as $choosen_reward): ?>
		<?php
			$color	= $counter++ % 2 ? '091e30' : '173148';
		?>
		<tr bgcolor="<?php echo $color ?>">
			<td align="center">
				<?php
					$message	= '';

					if($choosen_reward->currency) {
						$message	.= $choosen_reward->currency . ' ' . t('currencies.' . $player->character()->anime_id);
					}

					if($choosen_reward->vip) {
						$message	.= $choosen_reward->vip . ' ' . t('currencies.vip');
					}

					if($choosen_reward->item_id) {
						$item		= Item::find_first($choosen_reward->item_id);
						$message	.= $item->name . ' x' . $choosen_reward->quantity;
					}

					$ats	= array(
						'at_for'	=> t('at.at_for'),
						'at_int'	=> t('at.at_int'),
						'at_res'	=> t('at.at_res'),
						'at_agi'	=> t('at.at_agi'),
						'at_dex'	=> t('at.at_dex'),
						'at_vit'	=> t('at.at_vit')
					);

					foreach ($ats as $key => $value) {
						if($choosen_reward->$key) {
							$message	.= t('luck.index.messages.point', array('count' => $choosen_reward->$key, 'attribute' => $value));
						}
					}

					if($choosen_reward->traning_total) {
						$message	.= t('luck.index.messages.training_total', array('count' => $choosen_reward->traning_total));
					}

					if($choosen_reward->weekly_points_spent) {
						$message	.= t('luck.index.messages.weekly_points_spent', array('count' => $choosen_reward->weekly_points_spent));
					}

					echo $message;
				?>
			</td>
			<td align="center" width="120"><?php echo $choosen_reward->chance ?>%</td>
			<td align="center" width="220">
				<?php if ($choosen_reward->total): ?>
					<?php echo t('luck.index.won_count', array('count' => $choosen_reward->total)) ?>
				<?php endif ?>
			</td>
		</tr>
		<tr height="4"></tr>
	<?php endforeach ?>
</table>