<?php
	function exp_bar_windth($v, $m, $w) {
		$r = @($w / $m) * $v;
		
		return (int)($r > $w ? $w : $r);
	}

	function top_exp_bar($player) {
		$width		= exp_bar_windth($player->exp, $player->level_exp(), 282);
		//$width		= exp_bar_windth(800, $max, 282);
		$frame_id	= $player->character()->anime_id;

		return '<div class="top-expbar-container">
			<div class="level"><span>LVL</span><div class="number">' . $player->level . '</div></div>
			<div class="light" style="margin-left: ' . (50 + $width) . 'px"></div>
			<div class="frame" style="background-image: url(' . image_url('top_exp_bar/frame_' . $frame_id . '.png') . ')"></div>
			<div class="top-progress">
				<div class="empty"></div>
				<div class="fill" style="width: ' . $width . 'px">
				</div>
				<div class="text">' . $player->exp . ' / ' . $player->level_exp() . '</div>
			</div>
			<!--<div class="top-effect anipng" data-animation="' . image_url('top_exp_bar/frames.png') . '" data-animation-width="' . $width . '" data-animation-height="29" data-frames="42"></div>-->
			<div class="graduation">' . t('top.graduation', array('grad' => $player->graduation()->description()->name)) . '</div>
		</div>';
	}

	function section_bar($text, $anime = null) {
		$anime	= $anime ? $anime : rand(1, 6);

		return '<div class="barra-secao barra-secao-' . $anime . '"><p>' . $text . '</p></div>';
	}

	function exp_bar($value, $max, $max_width, $text = null) {
		$width	= exp_bar_windth($value, $max, $max_width);

		if(!$text) {
			$text	= $value;
		}

		return	'<div class="exp-bar exp-bar-' . $max_width . '" style="width: ' . $max_width . 'px">' .
					'<div class="fill" style="width: ' . $width . 'px"></div>' .
					'<div class="text">' . $text . '</div>' .
				'</div>';
	}