<?php
	$with_battle	= false;

	if ($_SESSION['player_id']) {
		$player	= Player::get_instance();

		if($player && ($player->battle_npc_id) && preg_match('/battle/', $controller)) {
			$with_battle	= true;
		}
	} else {
		$player	= false;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'>
<link rel="stylesheet" type="text/css" href="<?php echo asset_url('css/bootstrap.css') ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo asset_url('css/layout.css') ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo asset_url('css/characters.css') ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo asset_url('css/luck.css') ?>"/>
<link rel="shortcut icon" href="<?php echo image_url('favicon.ico') ?>" type="image/x-icon"/>
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
<script type="text/javascript" src="<?php echo asset_url('js/jquery.js') ?>"></script>
<title>Anime All Stars Game - Seja o Herói de nossa História</title>
<script type="text/javascript">
	var	_site_url			= "<?php echo $site_url ?>";
	var	_rewrite_enabled	= <?php echo $rewrite_enabled ? 'true' : 'false' ?>;
</script>
</head>

<body>
<!-- Barra do Topo -->
<!--
<div id="barra-topo">
	<div id="top-container">
		<?php if ($_SESSION['loggedin']): ?>
			<div class="credits">
				<?php echo t('top.credits', array('count' => User::get_instance()->credits)) ?>				
			</div>
		<?php endif ?>		
	</div>
</div>
-->
<!-- Barra do Topo -->

<!-- Topo -->
<?php if (!$_SESSION['player_id']): ?>
	<div id="background-topo">
		<div style="width: 1070px; height: 254px; text-align: center; margin: auto; padding-top: 1px; padding-left: 3px;">
			<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="1070" height="254" id="teste3" align="middle">
				<param name="movie" value="<?php echo image_url('teste3.swf') ?>" />
				<param name="quality" value="high" />
				<param name="bgcolor" value="#000000" />
				<param name="play" value="true" />
				<param name="loop" value="true" />
				<param name="wmode" value="transparent" />
				<param name="scale" value="showall" />
				<param name="menu" value="true" />
				<param name="devicefont" value="false" />
				<param name="salign" value="" />
				<param name="allowScriptAccess" value="sameDomain" />
				<!--[if !IE]>-->
				<object type="application/x-shockwave-flash" data="<?php echo image_url('teste3.swf') ?>" width="1070" height="254">
					<param name="movie" value="<?php echo image_url('teste3.swf') ?>" />
					<param name="quality" value="high" />
					<param name="bgcolor" value="#000000" />
					<param name="play" value="true" />
					<param name="loop" value="true" />
					<param name="wmode" value="transparent" />
					<param name="scale" value="showall" />
					<param name="menu" value="true" />
					<param name="devicefont" value="false" />
					<param name="salign" value="" />
					<param name="allowScriptAccess" value="sameDomain" />
				<!--<![endif]-->
					<a href="http://www.adobe.com/go/getflash">
						<img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" />
					</a>
				<!--[if !IE]>-->
				</object>
				<!--<![endif]-->
			</object>
		</div>
	
		<?php /*<div id="logo"><a href="<?php echo make_url() ?>"><img src="<?php echo image_url('logo.png') ?>" border="0"/></a></div>*/?>
	</div>	
<?php else: ?>
	<div id="background-topo2" style="background-image: url(<?php echo image_url($player->character_theme()->header_image(true)) ?>)">
		<div class="bg" style="background-image: url(<?php echo image_url($player->character_theme()->header_image(true)) ?>)"></div>
		<div class="info">
			<!--
			<div class="topo-nome">
				<span class="b1"><?php echo $player->character()->anime()->description()->name ?> / <?php echo $player->character()->description()->name ?></span>
				<span class="b2"><?php echo $player->name ?></span>
			</div>
			-->
			<?php echo top_exp_bar($player) ?>
		</div>
		<div class="menu">
			<div class="values">
				<div class="life"><span class="c"><?php echo $player->for_life() ?></span>/<span class="m"><?php echo $player->for_life(true) ?></span></div>
				<div class="mana"><span class="c"><?php echo $player->for_mana() ?></span>/<span class="m"><?php echo $player->for_mana(true) ?></span></div>
				<div class="stamina"><span class="c"><?php echo $player->for_stamina() ?></span>/<span class="m"><?php echo $player->for_stamina(true) ?></span></div>
				<div class="currency"><?php echo $player->currency ?></div>
				<div class="relogio"><img src="<?php echo image_url('icons/relogio.png')?>" /></div>
				<div class="mensagem"><img src="<?php echo image_url('icons/email.png')?>" /></div>
				<div class="vip"><img src="<?php echo image_url('icons/Vip.png')?>" /></div>
				<div class="logout"><a href="<?php echo make_url('users#logout')?>" name="Logout" title="Logout"><img src="<?php echo image_url('icons/log-out.png')?>" border="0" alt="Logout"/></a></div>
			</div>
			<div class="menu-content">
				<?php global $raw_menu_data; ?>
				<ul>
					<?php foreach ($raw_menu_data as $menu_category): ?>
						<li class="hoverable">
							<img src="<?php echo image_url('menu-icons/' . $menu_category['id'] . (!sizeof($menu_category['menus']) ? '-D' : '') . '.png') ?>">
							<span><?php echo t($menu_category['name']) ?></span>
							<?php if (sizeof($menu_category['menus'])): ?>
								<ul>
									<?php foreach ($menu_category['menus'] as $menu): ?>
										<li><a href="<?php echo make_url($menu['href']) ?>"><?php echo t($menu['name']) ?></a></li>
									<?php endforeach ?>
								</ul>
							<?php endif ?>
						</li>
					<?php endforeach ?>
					<li id="inventory-trigger">
						<img src="<?php echo image_url('menu-icons/10.png') ?>">
						<span><?php echo t('menus.inventory') ?></span>
						<ul data-text="<?php echo t('global.wait') ?>" id="inventory-container"></ul>
					</li>
				</ul>
			</div>
		</div>
		<div class="cloud"></div>
	</div>
<?php endif ?>
<!-- Topo -->

<!-- Conteúdo -->
<div id="conteudo" class="<?php echo $player ? 'with-player' : '' ?> <?php echo $with_battle ? 'with-battle' : '' ?>">
	<?php if ($_SESSION['player_id']): ?>
		<div id="player-top-status">
			<div class="anime"><?php echo $player->character()->anime()->description()->name ?> / <?php echo $player->character()->description()->name ?></div>
			<div class="name"><?php echo $player->name ?></div>
			<div class="level"><?php echo $player->level ?></div>
		</div>
	<?php endif ?>
	<div id="pagina">
		<div id="colunas">
			<?php if (!$player || !$with_battle): ?>
				<div id="esquerda" class="<?php echo $player ? 'with-player' : '' ?>">
					<?php if ($player): ?>
						<?php echo partial('shared/left_character', ['player' => $player]) ?>
					<?php else: ?>
						<div id="menu">
							<?php if (!$_SESSION['loggedin']): ?>
								<div id="login" style="background-image:url('<?php echo image_url('bg-login.png') ?>');">
									<div id="form-login">
										<form method="post" onsubmit="return false">
											<input type="text" name="email" placeholder="Digite seu Login" class="in-login"/>
											<input type="password" name="password" placeholder="Digite sua Senha" class="in-senha"/>
											<input type="text" name="captcha" class="in-codigo" placeholder="Digite o Código"/>
											<!--<input type="text" name="captcha" class="in-captcha" placeholder="2568"/>-->
											<img class="in-captcha" src="<?php echo make_url('captcha#login') ?>" />
											<div style="position: relative; left: -8px; margin-top: -4px">
												<img src="<?php echo image_url('buttons/bt-senha.png') ?>" alt="Esqueci minha Senha"/>
												<input class="play-button" type="image" src="<?php echo image_url('buttons/bt-jogar.png') ?>" width="37" height="23" />
												<img src="<?php echo image_url('buttons/bt-face.png') ?>" alt="Logar com Facebook"/>
											</div>
										</form>
									</div>
								</div>
							<?php else: ?>
								<?php if ($_SESSION['player_id']): ?>
									<div id="login" style="background-image:url('<?php echo image_url('bg-login-' . $player->character()->anime_id . '.png') ?>');">
									</div>
								<?php else: ?>
									<div id="login" style="background-image:url('<?php echo image_url('bg-login-0.png') ?>');">
									</div>
								<?php endif ?>
							<?php endif ?>
							<div id="menu-conteudo">
								<div id="menu-topo"></div>
								<div id="menu-repete">
									<?php global $menu_data; ?>
									<?php foreach ($menu_data as $menu_category): ?>
										<?php if (sizeof($menu_category['menus'])): ?>
											<img src="<?php echo image_url('menus/' . $_SESSION['language_id'] . '/' . $menu_category['id'] . '_' . ($player ? $player->character()->anime_id : rand(1, 6)) . '.png') ?>" />
											<?php foreach ($menu_category['menus'] as $menu): ?>
												<li><a href="<?php echo make_url($menu['href']) ?>"><?php echo t($menu['name']) ?></a></li>											
											<?php endforeach ?>
										<?php endif ?>
									<?php endforeach ?>
									<div class="clearfix"></div>
								</div>
								<div id="menu-fim"></div>
							</div>
						</div>
					<?php endif ?>
				</div>				
			<?php endif ?>
			<div id="direita" class="<?php echo $player ? 'with-player' : '' ?>">	
				@yield
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
		</div>
		<div class="esquerda-gradient" ></div>
		<div class="clearfix"></div>
	</div>
	<div class="clearfix"></div>
</div>
<div id="rodape">
	<div id="rodape-posicao">
		<div id="facebook">
			<div style="width: 420px; height: 90px;"></div>
		</div>
		<div id="texto-rodape">
			<p>Personagens e desenhos © CopyRight 2002 by Masashi Kishimoto. Todos os direitos reservados<br />
			   <b>©2013 AniGame Allstars - Todos os direitos reservados sobre o sistema e gráficos</b>
			 </p>
		</div>
		<!-- <div id="outros-jogos">
			<div style="padding-top: 18px;"><a href="http://dragonballgame.com.br" target="_blank"><img src="<?php echo image_url('logos/dbg-logo.png') ?>" alt="Dragon Ball Game" /></a></div>
			<div><a href="http://narutogame.com.br" target="_blank"><img src="<?php echo image_url('logos/logo_ng.png') ?>" alt="Naruto Game" /></a></div>
			<div style="padding-top: 12px;"><a href="http://cdzgame.com.br" target="_blank"><img src="<?php echo image_url('logos/cdzg-logo.png') ?>" alt="CDZ Game" /></a></div>
			<div style="padding-top: 20px;"><a href="http://bleachgame.com.br" target="_blank"><img src="<?php echo image_url('logos/bg-logo.png') ?>" alt="Bleach Game" /></a></div>
		</div> -->
	</div>
</div>
<script type="text/javascript" src="<?php echo asset_url('js/i18n.js') ?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/bootstrap.js') ?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/bootbox.js') ?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/global.js') ?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/users.js') ?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/characters.js') ?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/graduations.js') ?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/cards.js') ?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/techniques.js') ?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/trainings.js') ?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/shop.js') ?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/luck.js') ?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/reset_password.js') ?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/talents.js') ?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/inventory.js') ?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/battles.js') ?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/battle_npcs.js') ?>"></script>
<?php if ($_SESSION['loggedin']): ?>
	<script src="http://cdn.sockjs.org/sockjs-0.3.js"></script>
	<script type="text/javascript" src="<?php echo asset_url('js/notifier.js') ?>"></script>
<?php endif ?>

<script type="text/javascript" src="<?php echo asset_url('js/png_animator.js') ?>"></script>
<!-- Conteúdo -->
</body>
</html>
