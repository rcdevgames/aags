<div class="technique-data fix-lines">
	<div class="type"><?php echo t('techniques.types.talent') ?></div>
	<hr />
	<table border="0" width="100%">
		<?php foreach ($ats as $at): ?>
			<?php if ((float)$item->$at): ?>
				<tr>
					<td colspan="2"><?php echo t('talents.at.' . $at, ['value' => $item->$at]) ?></td>
				</tr>
			<?php endif ?>
		<?php endforeach ?>
		<?php foreach ($formulas as $formula): ?>
			<?php if ((float)$item->$formula): ?>
				<tr>
					<td colspan="2"><?php echo t('talents.formula.' . $formula, ['value' => $item->$formula]) ?></td>
				</tr>
			<?php endif ?>
		<?php endforeach ?>
		<?php if ($item->bonus_food_discount): ?>
			<tr>
				<td colspan="2"><?php echo t('bonuses.bonus_food_discount', ['value' => $item->bonus_food_discount, 'currency' => t('currencies.' . $item->anime()->id)]) ?></td>
			</tr>
		<?php endif ?>
		<?php if ($item->bonus_weapon_discount): ?>
			<tr>
				<td colspan="2"><?php echo t('bonuses.bonus_weapon_discount', ['value' => $item->bonus_weapon_discount, 'currency' => t('currencies.' . $item->anime()->id)]) ?></td>
			</tr>
		<?php endif ?>
		<?php if ($item->bonus_luck_discount): ?>
			<tr>
				<td colspan="2"><?php echo t('bonuses.bonus_luck_discount', ['value' => $item->bonus_luck_discount, 'currency' => t('currencies.' . $item->anime()->id)]) ?></td>
			</tr>
		<?php endif ?>
		<?php if ($item->bonus_mana_consume): ?>
			<tr>
				<td colspan="2"><?php echo t('bonuses.bonus_mana_consume', ['value' => $item->bonus_mana_consume, 'mana' => t('formula.for_mana.' . $item->anime()->id)]) ?></td>
			</tr>
		<?php endif ?>
		<?php if ($item->bonus_cooldown): ?>
			<tr>
				<td colspan="2"><?php echo t('bonuses.bonus_cooldown', ['value' => $item->bonus_cooldown]) ?></td>
			</tr>
		<?php endif ?>
		<?php if ($item->bonus_exp_fight): ?>
			<tr>
				<td colspan="2"><?php echo t('bonuses.bonus_exp_fight', ['value' => $item->bonus_exp_fight]) ?></td>
			</tr>
		<?php endif ?>
		<?php if ($item->bonus_currency_fight): ?>
			<tr>
				<td colspan="2"><?php echo t('bonuses.bonus_currency_fight', ['value' => $item->bonus_currency_fight, 'currency' => t('currencies.' . $item->anime()->id)]) ?></td>
			</tr>
		<?php endif ?>
		<?php if ($item->bonus_attribute_training_cost): ?>
			<tr>
				<td colspan="2"><?php echo t('bonuses.bonus_attribute_training_cost', ['value' => $item->bonus_attribute_training_cost, 'mana' => t('formula.for_mana.' . $item->anime()->id)]) ?></td>
			</tr>
		<?php endif ?>
		<?php if ($item->bonus_training_earn): ?>
			<tr>
				<td colspan="2"><?php echo t('bonuses.bonus_training_earn', ['value' => $item->bonus_training_earn]) ?></td>
			</tr>
		<?php endif ?>
		<?php if ($item->bonus_training_exp): ?>
			<tr>
				<td colspan="2"><?php echo t('bonuses.bonus_training_exp', ['value' => $item->bonus_training_exp]) ?></td>
			</tr>
		<?php endif ?>
		<?php if ($item->bonus_quest_time): ?>
			<tr>
				<td colspan="2"><?php echo t('bonuses.bonus_quest_time', ['value' => $item->bonus_quest_time]) ?></td>
			</tr>
		<?php endif ?>
		<?php if ($item->bonus_food_heal): ?>
			<tr>
				<td colspan="2"><?php echo t('bonuses.bonus_food_heal', ['value' => $item->bonus_food_heal]) ?></td>
			</tr>
		<?php endif ?>
		<?php if ($item->bonus_npc_in_quests): ?>
			<tr>
				<td colspan="2"><?php echo t('bonuses.bonus_npc_in_quests', ['value' => $item->bonus_npc_in_quests]) ?></td>
			</tr>
		<?php endif ?>
		<?php if ($item->bonus_daily_npc): ?>
			<tr>
				<td colspan="2"><?php echo t('bonuses.bonus_daily_npc', ['value' => $item->bonus_daily_npc]) ?></td>
			</tr>
		<?php endif ?>
		<?php if ($item->bonus_map_npc): ?>
			<tr>
				<td colspan="2"><?php echo t('bonuses.bonus_map_npc', ['value' => $item->bonus_map_npc]) ?></td>
			</tr>
		<?php endif ?>
		<?php if ($item->bonus_drop): ?>
			<tr>
				<td colspan="2"><?php echo t('bonuses.bonus_drop', ['value' => $item->bonus_drop]) ?></td>
			</tr>
		<?php endif ?>
		<?php if ($item->bonus_stamina_max): ?>
			<tr>
				<td colspan="2"><?php echo t('bonuses.bonus_stamina_max', ['value' => $item->bonus_stamina_max]) ?></td>
			</tr>
		<?php endif ?>
		<?php if ($item->bonus_stamina_heal): ?>
			<tr>
				<td colspan="2"><?php echo t('bonuses.bonus_stamina_heal', ['value' => $item->bonus_stamina_heal]) ?></td>
			</tr>
		<?php endif ?>
		<?php if ($item->bonus_stamina_consume): ?>
			<tr>
				<td colspan="2"><?php echo t('bonuses.bonus_stamina_consume', ['value' => $item->bonus_stamina_consume]) ?></td>
			</tr>
		<?php endif ?>
	</table>
</div>