<div id="battle-container" data-target="<?php echo $target_url ?>">
	<?php if(!$player->battle_npc_id): ?>
		<div id="ranking"></div>
	<?php endif; ?>
	<div class="top"></div>
	<div class="player-container">
		<div class="chains"></div>
		<div id="players">
			<div id="vs">
				<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="349" height="290" id="teste3" align="middle">
					<param name="movie" value="<?php echo image_url('battle/vs.swf') ?>" />
					<param name="wmode" value="transparent" />
					<object type="application/x-shockwave-flash" data="<?php echo image_url('battle/vs.swf') ?>" width="349" height="290">
						<param name="movie" value="<?php echo image_url('battle/vs.swf') ?>" />
						<param name="wmode" value="transparent" />
					</object>
				</object>
				<div class="log">
					<?php if (is_array($log)): ?>
						<?php foreach ($log as $entry): ?>
							<div><?php echo $entry ?></div><hr />
						<?php endforeach ?>
					<?php endif ?>
				</div>
				<div class="log-scroller">
					<span class="up glyphicon glyphicon-chevron-up"></span>
					<span class="down glyphicon glyphicon-chevron-down"></span>
				</div>
				<div class="log-timer"></div>
			</div>
			<div id="player" class="player-box">
				<div class="modifiers"></div>
				<?php echo $player->profile_image() ?>
				<div class="name"><?php echo $player->name ?></div>
				<div class="mana"><div class="mana-fill"></div><div class="text"></div></div>
				<div class="life-container">
					<div class="life" style="text-align: right; padding-right: 10px"></div>
				</div>
				<div class="player-info">
					<div class="level"><?php echo $player->level ?></div>
					<div class="anime-headline">
						<div class="anime"><?php echo $player->character()->anime()->description()->name ?> / <?php echo $player->graduation()->description()->name ?></div>
						<div class="headline"><?php echo $player->headline_id ? $player->headline()->description()->name : '--' ?></div>
					</div>
				</div>
			</div>
			<div id="enemy" class="player-box">
				<div class="modifiers"></div>
				<?php echo $enemy->profile_image() ?>
				<div class="name"><?php echo $enemy->name ?></div>
				<div class="mana"><div class="mana-fill"></div><div class="text" style="text-align: left; padding-left: 10px"></div></div>
				<div class="life-container">
					<div class="life"></div>
				</div>
				<div class="player-info">
					<div class="level"><?php echo $enemy->level ?></div>
					<div class="anime-headline">
						<div class="anime"><?php echo $enemy->character()->anime()->description()->name ?> / --</div>
						<div class="headline">--</div>
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
	<div id="divider">
		<div class="text"><?php echo t('battles.technique_text') ?></div>
		<div class="filter-container">
			<?php
				$types	= [];

				foreach ($techniques as $technique) {
					$item	= $technique->item();

					if($item->item_type_id == 1) {
						$subtype	= $item->is_defensive ? 'defense' : 'attack';
						$key		= $item->item_type_id . $subtype . ($item->is_buff ? 'buff' : '');
						$name_key	= $item->item_type_id . '.' . ($item->is_buff ? 'buff' : $subtype);

						if (!isset($types[$key])) {
							$types[$key]	= [
								'name'		=> t('item_types.' . $name_key),
								'subtype'	=> $subtype,
								'type'		=> $item->item_type_id,
								'buff'		=> $item->is_buff ? 'buff' : 'normal'
							];
						}
					} else {
						if(!isset($types[$item->item_type_id])) {
							$types[$item->item_type_id]	= [
								'name'		=> t('item_types.'. $item->item_type_id),
								'subtype'	=> '',
								'type'		=> $item->item_type_id,
								'buff'		=> $item->is_buff ? 'buff' : 'normal'
							];
						}
					}
				}

				krsort($types);
			?>
			<?php foreach ($types as $type): ?>
				<div class="type-filter" data-type="<?php echo $type['type'] ?>" data-subtype="<?php echo $type['subtype'] ?>" data-buff="<?php echo $type['buff'] ?>"><?php echo $type['name'] ?></div>
			<?php endforeach ?>
		</div>
	</div>
	<div id="technique-container">
		<?php foreach ($techniques as $technique): ?>
			<?php $item	= $technique->item() ?>
			<div class="item item-type-<?php echo $item->item_type_id ?> <?php echo $item->is_defensive ? 'defense' : 'attack' ?> <?php echo $item->is_buff ? 'buff' : 'normal' ?>" id="item-container-<?php echo $item->id ?>" data-item="<?php echo $item->id ?>">
				<img src="<?php echo image_url($item->image(true)) ?>" class="technique-popover" data-source="#technique-content-<?php echo $item->id ?>" data-title="<?php echo $item->description()->name ?>" data-trigger="hover" data-placement="bottom" />
				<div class="technique-container" id="technique-content-<?php echo $item->id ?>">
					<?php echo $item->technique_tooltip() ?>
				</div>
			</div>
		<?php endforeach ?>
		<div class="clearfix"></div>
	</div>
	<div class="bottom"></div>
</div>
<script type="text/javascript">
	$(document).ready(function () {
		draw_battle_hb(<?php echo $enemy->for_life() ?>, <?php echo $enemy->for_life(true) ?>);
		draw_battle_hb(<?php echo $player->for_life() ?>, <?php echo $player->for_life(true) ?>, 'l');

		draw_battle_mb(<?php echo $enemy->for_mana() ?>, <?php echo $enemy->for_mana(true) ?>);
		draw_battle_mb(<?php echo $player->for_mana() ?>, <?php echo $player->for_mana(true) ?>, 'l');
	});
</script>