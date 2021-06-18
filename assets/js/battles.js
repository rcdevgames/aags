(function () {
	var	locked				= [];
	var	battle_container	= $('#battle-container');
	var	log_container		= $('.log', battle_container);
	var	all_loaded			= false;
	var	images_to_load		= 0;
	var	load_count			= 0;
	var	lcanvas				= null;
	var	rcanvas				= null;
	var max_log_scroll		= 0;
	var	current_log_scroll	= 0;
	var	images				= {
		rn:	{element: null, url: 'battle/bars/battle_lb_right.png', loaded: false},
		rf:	{element: null, url: 'battle/bars/battle_lb_right_fill.png', loaded: false}
	};

	function update_log_tooltip() {
		$('.log .i', battle_container).each(function () {
			var	_	= $(this);

			_.popover({
				content:	$(document.getElementById(_.data('tooltip'))).html(),
				html:		true,
				placement:	'bottom',
				trigger:	'hover'
			});
		});
	}

	function draw_modifiers(objekt, status, container) {
		$('.item', container).remove();

		var	item	= $(document.createElement('DIV')).addClass('item');
		var	item_id	= 'i-' + (Math.random() * 65535) + '.' + (Math.random() * 65535);
		var	popover	= $(document.createElement('DIV')).attr('id', item_id).css({display: 'none'});
		var	html	= '<div class="modifier-tooltip">' + I18n.t('battles.status_tooltip.atk', {value: status.atk}) + "<br />" +
					  I18n.t('battles.status_tooltip.def', {value: status.def}) + "<br />" +
					  I18n.t('battles.status_tooltip.crit', {value: status.crit, inc: status.crit_inc}) + "<br />" +
					  I18n.t('battles.status_tooltip.abs', {value: status.abs, inc: status.abs_inc}) + "<br />" +
					  I18n.t('battles.status_tooltip.prec', {value: status.prec}) + "<br />" +
					  I18n.t('battles.status_tooltip.inti', {value: status.inti}) + "<br />" +
					  I18n.t('battles.status_tooltip.conv', {value: status.conv}) + "<br />" +
					  I18n.t('battles.status_tooltip.init', {value: status.init}) + '</div>';

		item.append('<img src="' + image_url('battle/details.png') + '"  class="technique-popover" data-source="' + item_id + '" data-trigger="hover" data-placement="bottom"  />')
		item.append(popover);
		popover.append(html);
		container.append(item);

		if(objekt.mods && objekt.mods.length) {
			objekt.mods.forEach(function (mod) {
				var	item	= $(document.createElement('DIV')).addClass('item');
				var	item_id	= 'i-' + (Math.random() * 65535) + '.' + (Math.random() * 65535);
				var	popover	= $(document.createElement('DIV')).attr('id', item_id).css({display: 'none'});
				var	html	= '<div class="modifier-tooltip">' + mod.tooltip + '</div>';

				item.append('<img src="' + image_url(mod.image) + '" width="24" height="24" class="technique-popover" data-source="' + item_id + '" data-trigger="hover" data-placement="bottom"  />')
				item.append(popover);
				popover.append(html);
				container.append(item);
			});
		}

		$('.item img', container).each(function () {
			var	_	= $(this);

			_.popover({
				content:	$(document.getElementById(_.data('source'))).html(),
				html:		true,
				placement:	'bottom',
				trigger:	'hover'
			});
		});
	}

	window.draw_battle_hb	= function (value, max, pos) {
		pos	= pos || 'r';

		if(pos == 'r' && !rcanvas) {
			rcanvas	= document.createElement('canvas');
			$('#battle-container #enemy .life-container').append(rcanvas);
		} else if(pos == 'l' && !lcanvas) {
			lcanvas	= document.createElement('canvas');
			$('#battle-container #player .life-container').append(lcanvas);
		}

		var	canvas		= pos == 'r' ? rcanvas : lcanvas;

		canvas.width	= 284;
		canvas.height	= 119;
		canvas.pos		= pos;

		if(!canvas._first_value) {
			canvas._first_value	= true;
			canvas.setAttribute('data-value', value);
		} else {
			if(canvas._iv) {
				clearInterval(canvas._iv);
			}

			canvas._iv			= setInterval(function () {
				if(value > canvas.getAttribute('data-value')) {
					canvas.setAttribute('data-value', parseInt(canvas.getAttribute('data-value')) + 1);
				} else {
					canvas.setAttribute('data-value', parseInt(canvas.getAttribute('data-value')) - 1);
				}

				if(canvas.getAttribute('data-value') == value) {
					clearInterval(canvas._iv);
				}
			}, 10);
		}

		canvas.setAttribute('data-max-value', max);

		if(!canvas._cb) {
			canvas._cb	= function () {
				var context		= canvas.getContext('2d');
				var	value		= this.getAttribute('data-value');
				var	max_value	= this.getAttribute('data-max-value');

				context.save();

				if(this.pos == 'l') {
					context.translate(284, 0);
					context.scale(-1, 1);

					$('#battle-container #player .life').html(value + '/' + max_value);
				} else {
					$('#battle-container #enemy .life').html(value + '/' + max_value);
				}

				context.clearRect(0, 0, 284, 119);
				context.drawImage(images['rn'].element, 0, 0);

				var	start_h		= 100;
				var	end_h		= 119;
				var	base_w		= 223;
				var	px			= (base_w/(max_value / 2)) * value;

				context.beginPath();
				context.moveTo(0, end_h);

				if(value > max_value / 2) {
					var	divider	= max_value / 2
					var	v		= divider - (value - divider);

					context.lineTo(base_w, end_h);

					context.arc(base_w, 62, 62, 0.5 * Math.PI, Math.PI + (v * ((1.5 * Math.PI) / divider)), true);
					context.arc(base_w, 62, 38, Math.PI + (v * ((1.5 * Math.PI) / divider)), 0.5 * Math.PI, false);

					context.lineTo(0, start_h);
					context.lineTo(0, end_h);
				} else {
					context.lineTo(px, end_h);

					context.lineTo(px, start_h);
					context.lineTo(0, start_h);
					context.lineTo(0, end_h);
				}

				context.closePath();
				context.clip();
				context.drawImage(images['rf'].element, 0, 0);

				context.restore();
			}

			setInterval(function () {
				requestAnimationFrame(function () {
					canvas._cb.apply(canvas);
				});
			}, 1000 / 60);
		}
	};

	window.draw_battle_mb	= function (value, max, pos) {
		var	w	= (value * 100 / max);
		pos		= pos || 'r';

		if(pos == 'r') {
			$('#battle-container #enemy .mana-fill').animate({width: w + '%'});
			$('#battle-container #enemy .mana .text').html(value + ' / ' + max);
		} else {
			$('#battle-container #player .mana-fill').animate({width: w + '%'});
			$('#battle-container #player .mana .text').html(value + ' / ' + max);
		}
	}

	for(var i in images) {
		var	el				= document.createElement('img');
		el.src				= image_url(images[i].url);
		el.style.display	= 'none';
		el.onload			= function () {
			load_count++;

			if(load_count >= images_to_load) {
				all_loaded	= true;
			}

			images[this.getAttribute('data-key')].loaded	= true;
		}

		el.setAttribute('data-key', i);

		images[i].element	= el;
		images_to_load++;
	}

	for(var i in images) {
		document.body.appendChild(images[i].element);
	}

	if(battle_container.length) {
		$('.log-scroller .up', battle_container).on('click', function () {
			current_log_scroll	-= 10;

			if(current_log_scroll < 0) {
				current_log_scroll	= 0;
			}

			log_container.scrollTop(current_log_scroll);
		});

		$('.log-scroller .down', battle_container).on('click', function () {
			current_log_scroll	+= 10;
			log_container.scrollTop(current_log_scroll);

			if(current_log_scroll > log_container.scrollTop()) {
				current_log_scroll	= log_container.scrollTop();
			}
		});

		$('#technique-container', battle_container).on('click', '.item', function () {
			var	_	= $(this);

			if(locked[_.data('id')]) {
				jalert(I18n.t('battles.errors.technique_locked'));
				return;
			}

			var	variant	= 'attack';

			if(_.hasClass('buff')) {
				variant	= 'modifier';
			}

			$.ajax({
				url:		battle_container.data('target') + '/' + variant,
				data:		{item: _.data('item')},
				type:		'post',
				dataType:	'json',
				success:	function (result) {
					parse(result);
				}
			})
		});

		function parse(result) {
			if(result.log && result.log.length) {
				html	= '';

				result.log.forEach(function (entry) {
					html	+= '<div>' + entry + '</div><hr />';
				});

				current_log_scroll	= log_container.scrollTop();
				old_max_scroll		= max_log_scroll;

				log_container.html(html).scrollTop(1000000);
				max_log_scroll	= log_container.scrollTop();

				if(current_log_scroll != old_max_scroll) {
					log_container.scrollTop(current_log_scroll);
				}

				update_log_tooltip();
			}

			if(!result.flight) {
				if (result.player) {
					draw_battle_hb(result.player.life, result.player.life_max, 'l');					
					draw_battle_mb(result.player.mana, result.player.mana_max, 'l');

					$('#technique-container .locked', battle_container).each(function () {
						var	_				= $(this);
						var	should_unlock	= true;
						var	id				= _.data('item');

						result.player.locks.forEach(function (item) {
							if(parseInt(item.id) == parseInt(id)) {
								should_unlock	= false;
							}
						});

						if(should_unlock) {
							_.removeClass('locked');
							$('.technique-popover', _).stop().animate({opacity: 1});
						}
					});

					result.player.locks.forEach(function (item) {
						var	container	= $('#item-container-' + item.id, battle_container);

						if(!container.hasClass('locked')) {
							container.addClass('locked');

							$('.technique-popover', container).stop().animate({opacity: .2});
						}
					});

					draw_modifiers(result.player, result.player.status, $('#player .modifiers', battle_container));
				}

				if (result.enemy) {
					draw_battle_hb(result.enemy.life, result.enemy.life_max, 'r');
					draw_battle_mb(result.enemy.mana, result.enemy.mana_max, 'r');

					draw_modifiers(result.enemy, result.enemy.status, $('#enemy .modifiers', battle_container));
				}
			}

			if(result.finished) {
				var	win	= bootbox.dialog({message: result.finished, buttons: [
					{
						label:		'Fechar',
						class:		'btn btn-default',
						callback:	function () {
							location.href	= make_url('characters#status');
						}
					}
				]});

				$('.modal-dialog', win).addClass('pattern-container');
				$('.modal-content', win).addClass('with-pattern');
			}

			if(result.messages && result.messages.length) {
				format_error(result);
			}
		}

		$('.type-filter', battle_container).on('click', function () {
			var	_	= $(this);

			$('.type-filter', battle_container).addClass('disabled');
			_.removeClass('disabled');

			$('#technique-container .item', battle_container).stop().hide();

			filter	= '#technique-container .item-type-' + _.data('type');

			if (_.data('subtype')) {
				filter	+= '.' + _.data('subtype');
			}

			if (_.data('buff')) {
				filter	+= '.' + _.data('buff');
			}

			$(filter, battle_container).stop().show();
		}).last().trigger('click');

		update_log_tooltip();
		log_container.scrollTop(1000000);

		current_log_scroll	= log_container.scrollTop();
		max_log_scroll		= current_log_scroll;

		// Initial ping to draw status
		$.ajax({
			url:		battle_container.data('target') + '/ping',
			type:		'post',
			dataType:	'json',
			success:	function (result) {
				if(result.player) {
					draw_modifiers(result.player, result.player.status, $('#player .modifiers', battle_container));					
				}

				if(result.enemy) {
					draw_modifiers(result.enemy, result.enemy.status, $('#enemy .modifiers', battle_container));
				}
			}
		});
	}
})();