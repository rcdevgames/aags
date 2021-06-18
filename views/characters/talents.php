<?php echo partial('shared/title', array('title' => 'characters.talents.title', 'place' => 'characters.talents.title')) ?>
<div id="talents-container">
	<?php foreach ($list as $level => $items) { ?>
		<div class="talents">
			<div class="level <?=($player->level >= $level ? 'on' : '');?>">
				<p><?=$level;?></p>
			</div>
			<?php foreach ($items as $item) { ?>
				<div class="item <?=($player->has_item($item) ? 'on' : '');?>" data-item="<?=$item->id;?>">
					<div class="image" >
						<img src="<?=(image_url($item->image(true)));?>"  class="technique-popover" data-source="#talent-content-<?=$item->id;?>" data-title="<?=$item->description()->name;?>" data-trigger="hover" data-placement="bottom" />
						<div class="technique-container" id="talent-content-<?php echo $item->id ?>">
							<?=$item->tooltip();?>
						</div>
					</div>
					<div class="description">
						<p><?=$item->description()->name;?></p>
					</div>
				</div>
			<?php } ?>
		</div>	
	<?php } ?>
</div>
