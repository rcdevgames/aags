<?php if (!sizeof($player_items)): ?>
	<?php echo t('characters.inventory.empty') ?>
<?php endif ?>
<?php foreach ($player_items as $player_item): ?>
	<?php $item	= $player_item->item() ?>
	<div class="item" data-consumable="<?php echo in_array($item->item_type_id, $consumables) ? 1 : 0 ?>" data-id="<?php echo $item->id ?>" data-quantity="<?php echo $player_item->quantity ?>">
		<img src="<?php echo image_url($item->image(true)) ?>" class="inventory-item-popover" data-source="#inventory-item-content-<?php echo $item->id ?>" data-title="<?php echo $item->description()->name ?>" data-trigger="hover" data-placement="left" />
		<div class="inventory-item-container" id="inventory-item-content-<?php echo $item->id ?>">
			<?php echo $item->tooltip() ?>
		</div>
		<span class="quantity">x<?php echo $player_item->quantity ?></span>
	</div>
<?php endforeach ?>