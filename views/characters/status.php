<?php echo partial('shared/title', array('title' => 'characters.status.title', 'place' => 'characters.status.title')) ?>
<div style="width: 730px; position: relative;">
	  <div class="h-combates">
			<div style="width: 241px; text-align: center; padding-top: 12px"><b class="amarelo" style="font-size:13px">Resumo de Combate</b></div>
			<div style="width: 241px; text-align: center; padding-top: 22px; font-size: 12px !important; line-height: 15px;">
				<span class="verde">Vitórias NPC:</span> <?php echo $player->wins_npc?> <br />
				<span class="verde">Vitórias PVP:</span> <?php echo $player->wins_pvp?> <br />
				<span class="vermelho">Derrotas NPC:</span> <?php echo $player->losses_npc?> <br />
				<span class="vermelho">Derrotas PVP:</span> <?php echo $player->losses_pvp?> <br />
				<span class="cinza">Empates:</span> <?php echo $player->draws?> <br />
			</div>
		</div>
		<div class="h-missoes">
			<div style="width: 241px; text-align: center; padding-top: 12px"><b class="amarelo" style="font-size:13px">Missões Completas</b></div>
			<div style="width: 241px; text-align: center; padding-top: 22px; font-size: 12px !important; line-height: 15px;">
				<span class="verde">Tempo:</span> 0 OK / 0 Falhas<br />
				<span class="verde">Interativas:</span> 0 OK / 0 Falhas<br />
				<span class="verde">Especiais:</span> 0 OK / 0 Falhas<br />
				<span class="verde">Tarefas:</span> 0
			</div>
		</div>
		<div class="h-treinamento">
			<div style="width: 241px; text-align: center; padding-top: 12px"><b class="amarelo" style="font-size:13px">Treinamento de Atributos</b></div>
			<div style="width: 241px; text-align: center; padding-top: 30px; font-size: 12px !important; line-height: 15px;">
				<span class="laranja">Total de Treino:</span> <?php echo $player->training_total?><br />
				<span class="laranja">Pontos Distribuídos:</span> <?php echo $player->training_points_spent?>
			</div>
		</div>
<!-- DIVISAO -->
	<div style="position: relative; float: left; width:365px; left: 6px; top: 15px">	
		<div class="titulo-home"><p>Atributos</p></div>
		<?php foreach ($attributes as $_ => $attribute): ?>
			<div class="bg_td">
				<div class="amarelo atr_float" style="width: 90px; text-align:left; padding-left:16px;"><?php echo $attribute ?></div>
				<div class="atr_float"  style="width: 20px; text-align:left;margin-left: 6px;">
					<img src="<?php echo image_url('icons/' . $_ . '.png') ?>" style="position: relative; top: -5px; left: -10px;" />
				</div>
				<!--
				<div class="atr_float"  style="width: 80px; text-align:left;">
					<span class="branco"><?php echo $player->{$_}() ?></span>
				</div>
				-->
				<div class="atr_float" style="margin-top: 7px; margin-left: 20px">
					<?php echo exp_bar($player->{$_}(), $max2, 175) ?>
				</div>
			</div>			
		<?php endforeach ?>
	</div>	
		<br /><br />
	<div style="position: relative; float: left; width:365px; left: 33px; top: 15px">	
		<div class="titulo-home"><p>Fórmulas</p></div>
		<?php foreach ($formulas as $_ => $formula): ?>
			<div class="bg_td">
				<div class="amarelo atr_float" style="width: 90px; text-align:left; padding-left:16px;"><?php echo $formula ?></div>
				<div class="atr_float"  style="width: 20px; text-align:left;margin-left: 6px;">
					<img src="<?php echo image_url('icons/' . $_ . '.png') ?>" style="position: relative; top: -5px; left: -10px;" />
				</div>
				<!--
				<div class="atr_float"  style="width: 80px; text-align:left;">
					<span class="branco"><?php echo $player->{$_}() ?></span>
				</div>
				-->
				<div class="atr_float" style="margin-top: 7px; margin-left: 20px">
					<?php echo exp_bar($player->{$_}(), $max, 175) ?>
				</div>
			</div>
		<?php endforeach ?>
		</div>					
	</div>
