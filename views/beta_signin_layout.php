<html>
<head>
	<title>Anime All Stars Game - Seja o Herói de nossa História</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="<?php echo asset_url('css/bootstrap.css') ?>"/>
	<link rel="stylesheet" type="text/css" href="<?php echo asset_url('css/layout.css') ?>"/>
	<link rel="stylesheet" type="text/css" href="<?php echo asset_url('css/beta.css') ?>"/>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
	<script type="text/javascript" src="<?php echo asset_url('js/jquery.js') ?>"></script>
	<script type="text/javascript">
		var	_site_url			= "<?php echo $site_url ?>";
		var	_rewrite_enabled	= <?php echo $rewrite_enabled ? 'true' : 'false' ?>;
	</script>
</head>
<body>
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
	<div id="beta-container">@yield</div>
	<div id="beta-facebook">
		<div style="width: 970px; height: 190px;">
			<div class="fb-like-box" data-href="https://www.facebook.com/AnimeAllStarsGame" data-width="970" data-height="190" data-colorscheme="light" data-show-faces="true" data-header="false" data-stream="false" data-show-border="false"></div>
		</div>
	</div>
	<br />
	<br />
	<div id="fb-root"></div>
	<script type="text/javascript" src="<?php echo asset_url('js/i18n.js') ?>"></script>
	<script type="text/javascript" src="<?php echo asset_url('js/bootstrap.js') ?>"></script>
	<script type="text/javascript" src="<?php echo asset_url('js/bootbox.js') ?>"></script>
	<script type="text/javascript" src="<?php echo asset_url('js/global.js') ?>"></script>
	<script type="text/javascript" src="<?php echo asset_url('js/beta.js') ?>"></script>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/pt_BR/all.js#xfbml=1&appId=271999446156621";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
</body>
</html>