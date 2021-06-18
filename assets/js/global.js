(function () {
	$('#form-login form').on('submit', function (e) {
		lock_screen(true);

		$.ajax({
			url:		make_url('users#login'),
			data:		$(this).serialize(),
			dataType:	'json',
			type:		'post',
			success:	function (result) {
				if(!result.success) {
					lock_screen(false);

					alert(':(');
				} else {
					location.href	= result.redirect;
				}
			}
		});

		e.preventDefault();
	});

	$(window).on('scroll', function () {
		var	_	= $(this);

		if(_.scrollTop() > 241) {
			$('#background-topo2 .bg').show();

			$('#background-topo2 .menu').addClass('floatable-menu');
			$('#background-topo2 .info').addClass('floatable-info');
			$('#background-topo2 .cloud').addClass('floatable-cloud');
		} else {
			$('#background-topo2 .bg').hide();

			$('#background-topo2 .menu').removeClass('floatable-menu');
			$('#background-topo2 .info').removeClass('floatable-info');
			$('#background-topo2 .cloud').removeClass('floatable-cloud');
		}
	});

	window.make_url	= function(to) {
		if(!to) {
			return _site_url;
		}

		var to = to.split('#');

		url	=  _site_url + (_rewrite_enabled ? '' : '/index.php') + '/' + to[0] + (to.length > 1 ? '/' + to[1] : '');

		return url;
	}

	window.image_url	= function (to) {
		return _site_url + '/assets/images/' + to;
	}

	window.lock_screen	= function (show) {
		if(show) {
			var d	= $(document.createElement('DIV')).addClass('screen-lock');
			var dd	= $(document.createElement('DIV')).addClass('screen-lock-text');
			
			dd.html('<span class="glyphicon glyphicon-refresh"></span>&nbsp;Aguarde...');
			
			$(document.body).append(d, dd).css('overflow', 'hidden');
			
			if(!window.has_screen_lock_callback) {
				window.has_screen_lock_callback	= true;
				
				$(window).on('resize', function () {
					$('.screen-lock')
						.css('width', $(window).width())
						.css('height', $(window).height());
				});
			}
		} else {
			$(document.body).css('overflow', 'auto');
			$('.screen-lock, .screen-lock-text').remove();
		}
	}

	window.format_error	=	function (result) {
		var errors	= [];
		var	win		= bootbox.dialog({message: '...', buttons: [
			{
				'label': 'Fechar'
			}
		]});

		(result.errors || result.messages).forEach(function (error) {
			errors.push('<li>&bull; ' + error + '</li>');
		});

		$('.bootbox-body', win).html('<h3>Os seguintes erros impediram de salvar os dados atuais:</h3><ul>' + errors.join('') + '</ul>')
		$('.modal-body', win).css('border-top', 'solid 6px #F00');
	}

	$('.technique-popover, .requirement-popover, .shop-item-popover').each(function () {
		$(this).popover({
			content:	$($(this).data('source')).html(),
			html:		true
		});
	});

	window.exp_bar_width	= function (v, m ,w) {
		var	r = (w / m) * v;
		
		return (r > w ? w : r);
	}

	window.fill_exp_bar =	function (target, value, max, text) {
		if(!text) {
			text	= value;
		}

		var	target	= $(target);
		var	width	= exp_bar_width(value, max, target.width());

		if(value == 0) {
			width	= 0;
		}

		$('.fill', target).animate({
			width: width
		});

		$('.text', target).html(text);
	}

	$('.mr-debug-window .title').on('click', function () {
		$('.mr-debug-window').toggleClass('mr-debug-window-expanded');
	});

	$('.mr-debug-window .mr-sql-trace').on('click', function () {
		bootbox.alert($('#mr-sql-trace-' + $(this).data('id')).html());
	});

	window.jalert	= function (msg, ok_callback, options) {
		options	= options || {};

		var	win		= bootbox.dialog({message: msg, buttons: [
			{
				'label':	'Fechar',
				callback:	function () {
					if(ok_callback) {
						ok_callback.apply(null, []);
					}
				}
			}
		]});

		if(options.texturize) {
			$('.modal-dialog', win).addClass('pattern-container');
			$('.modal-content', win).addClass('with-pattern');
		}
	}

	window.jconfirm	= function (msg, ok_callback, cancel_callback, options) {
		options	= options || {};

		var	win		= bootbox.dialog({message: msg, buttons: [
			{
				'label':	'Fechar',
				callback:	function () {
					if(cancel_callback) {
						cancel_callback.apply(null, []);
					}
				}
			}, {
				'label':	'Ok',
				callback:	function () {
					if(ok_callback) {
						ok_callback.apply(null, []);
					}
				}
			}
		]});

		if(options.texturize) {
			$('.modal-dialog', win).addClass('pattern-container');
			$('.modal-content', win).addClass('with-pattern');
		}
	}

	var ___timers = [];
	window.create_timer	= function(h, m, s, t, f, identifier, change_title) {
		var title	= document.title;
		var _t		= setInterval(function () {
			s--;
			
			if(s <= 0 && m <= 0 && h <= 0) {
				clearInterval(_t);
				
				if(!f) {			
					location.reload();
					return;
				} else {
					f.apply();
				}
			}
			
			if(s <= 0) {
				s = 59;
				m--;
				
				if(m <= 0 && h > 0) {
					h--;
					m = 59;
				}
			}
			
			if(t instanceof Array) {
				for(var ii in t) {
					$("#" + t[ii]).html(
						(h < 10 ? "0" + h : h) + ":" + (m < 10 ? "0" + m : m) + ":" + (s < 10 ? "0" + s : s)
					);
				}
			} else {
				var	timer	= (h < 10 ? "0" + h : h) + ":" + (m < 10 ? "0" + m : m) + ":" + (s < 10 ? "0" + s : s);
				
				if(change_title) {
					document.title	= '[' + timer + '] ' + title;
				}
				
				$("#" + t).html(timer);
			}
		}, 1000);
		
		if(!identifier) {
			___timers.push(_t);	
		} else {
			___timers[identifier]	= _t;
		}
	}

	window.clear_timer	= function(id) {
		clearInterval(___timers[id]);
	}

	window.clear_timers	= function() {
		for(var i in ___timers) {
			clearInterval(___timers[i]);
		}
		
		___timers = [];
	}

	window.character_exp	= function (exp, max, level) {
		var	width	= parseInt($('.top-progress').width());
		var	size	= (width / max) * exp;

		if(size > width) size	= width;

		$('.top-expbar-container .level .number').html(level);
		$('.top-expbar-container .fill').animate({width: size});
		$('.top-expbar-container .light').animate({marginLeft: size + 50});

		$('.top-expbar-container .text').html(exp + ' / ' + max);
	}

	window.default_bar_change	= function (val, max, target) {
		var	width	= parseInt(target.width());
		var	size	= (width / max) * val;

		if(size > width) size	= width;

		$('.fill', target).animate({width: size});
		$('.text', target).html(val + ' / ' + max);
	}

	window.character_stats	= function (params) {
		if(typeof(params.life) != 'undefined') {
			$('#background-topo2 .life .c').html(params.life);
		}

		if(typeof(params.max_life) != 'undefined') {
			$('#background-topo2 .life .m').html(params.max_life);
		}

		if(typeof(params.mana) != 'undefined') {
			$('#background-topo2 .mana .c').html(params.mana);
		}

		if(typeof(params.max_mana) != 'undefined') {
			$('#background-topo2 .mana .m').html(params.max_mana);
		}

		if(typeof(params.stamina) != 'undefined') {
			$('#background-topo2 .stamina .c').html(params.stamina);
		}

		if(typeof(params.max_stamina) != 'undefined') {
			$('#background-topo2 .stamina .m').html(params.max_stamina);
		}

		if(typeof(params.currency) != 'undefined') {
			$('#background-topo2 .currency').html(params.currency);
		}
	}
})();