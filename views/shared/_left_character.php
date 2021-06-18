<div style="width:242px; height:285px; float: left; text-align: center">	
	<?php echo $player->profile_image() ?>
	<input class="button btn btn-warning" type="button" id="current-player-change-theme" data-url="<?php echo make_url('characters#list_themes') ?>" value="Temas" style="position:relative; top: -30px" />
	<input class="button btn btn-primary" type="button" id="current-player-change-image" data-url="<?php echo make_url('characters#list_images') ?>" value="Imagens" style="position:relative; top: -30px" />
</div>
<div style="color: #FFFFFF; width: 164px; margin-left:10px; float: left; text-align: left">
	<b class="amarelo" style="font-size:12px;">Player:</b><br />
	<b style="font-size: 16px !important"><?php echo $player->name ?></b><br /><br />
	<b class="amarelo" style="font-size:12px;">Dados Principais</b>
	<br /><br />
	Titulo:
	<select name="sPlayerTitulo" id="sPlayerTitulo" onchange="doPlayerTitulo()" style="width:150px">
		<option value="0">Nenhuma</option>
	
	</select><br />
	Anime: <span class="cinza">Naruto</span><br />
	Level: <span class="cinza">99</span><br />
	Poder: <span class="cinza">999.999</span><br />
	Graduação: <span class="cinza">Genin</span><br />
	Clã: <span class="cinza">Uzumaki</span><br />
	Invocação: <span class="cinza">Sapos</span><br />
	Ranking Anime: <span class="cinza">1000º</span><br />
	Ranking Geral: <span class="cinza">1000º</span><br />
	Score: <span class="cinza">100.000</span><br />
	Pontos de Conquista: <span class="cinza">1000º</span><br />
	Equipe: <span class="cinza">Nenhuma</span><br />
	Organização: <span class="cinza">Nenhuma</span>
</div>	
