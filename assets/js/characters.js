(function () {
	var	form						= $('#f-create-character');
	var	creation_current_character	= null;

	if(form.length) {
		$('#anime-list').on('click', '.anime', function () {
			var	_	= $(this);

			$('#anime-list .anime').removeClass('selected');
			_.addClass('selected');

			$('.anime-characters').hide();
			$('#anime-characters-' + _.data('id')).show();

			$('#anime-characters-' + _.data('id') + ' .character:first').trigger('click');
		});

		$('#anime-character-list').on('click', '.character', function () {
			var	_			= $(this);
			var	character	= _characters[_.data('id')];

			$('#anime-character-list .character').removeClass('selected');
			_.addClass('selected');

			$('#character-info .character').html(character.name);
			$('#character-info .anime').html(_animes[character.anime]);

			$('#anime-character-list .barra-secao, #anime-list .barra-secao').removeClass('barra-secao-1 barra-secao-2 barra-secao-3 barra-secao-4 barra-secao-5 barra-secao-6');
			$('#anime-character-list .barra-secao, #anime-list .barra-secao').addClass('barra-secao-' + character.anime);

			var	max						= 0;
			creation_current_character	= _.data('id');

			if(character.at.at_for > max) { max = character.at.at_for };
			if(character.at.at_int > max) { max = character.at.at_int };
			if(character.at.at_res > max) { max = character.at.at_res };
			if(character.at.at_agi > max) { max = character.at.at_agi };
			if(character.at.at_dex > max) { max = character.at.at_dex };
			if(character.at.at_vit > max) { max = character.at.at_vit };

			fill_exp_bar('#character-attributes .at_for .exp-bar', character.at.at_for, max);
			fill_exp_bar('#character-attributes .at_int .exp-bar', character.at.at_int, max);
			fill_exp_bar('#character-attributes .at_res .exp-bar', character.at.at_res, max);
			fill_exp_bar('#character-attributes .at_agi .exp-bar', character.at.at_agi, max);
			fill_exp_bar('#character-attributes .at_dex .exp-bar', character.at.at_dex, max);
			fill_exp_bar('#character-attributes .at_vit .exp-bar', character.at.at_vit, max);

			$('#character-profile-image').attr('src', character.profile);

			$('[name=character_id]', form).val(_.data('id'));
		});

		$('#anime-list .anime:first').trigger('click');

		form.on('submit', function (e) {
			lock_screen(true);

			$.ajax({
				url:		make_url('characters#create'),
				type:		'post',
				data:		form.serialize(),
				dataType:	'json',
				success:	function (result) {
					if(result.success) {
						location.href	=	make_url('characters#select?created');
					} else {
						lock_screen(false);
						format_error(result);
					}
				}
			});

			e.preventDefault();
		});

		$('#character-data #change-theme').on('click', function () {
			var	win	= bootbox.dialog({message: '...', buttons: [
				{
					label: 'Fechar',
					class:	'btn btn-default'
				}
			]});

			$('.modal-dialog', win).addClass('pattern-container');
			$('.modal-content', win).addClass('with-pattern');

			$.ajax({
				url:		make_url('characters#list_themes'),
				data:		{show_only: 1, character: creation_current_character},
				success:	function (result) {
					$('.bootbox-body', win).html(result);

					_apply_themes_cb(win);
				}
			});
		});
	}

	$('#select-player-list-container').on('click', '.player', function () {
		var	_		= $(this);
		var	player	= _players[_.data('id')];

		$('#select-player-list-container .player').removeClass('selected');
		_.addClass('selected');

		$('#current-player-info .name').html(player.name);
		$('#current-player-info .anime').html(player.anime);
		$('#current-player-info .level').html(player.level);
		$('#current-player-info .currency').html(player.currency);
		$('#current-player-info .amount').html(player.amount);
		$('#current-player-info .graduation').html(player.graduation);

		var	max	= 0;

		if(player.max_life > max) {
			max	= player.max_life;
		}

		if(player.max_mana > max) {
			max	= player.max_mana;
		}

		if(player.max_stamina > max) {
			max	= player.max_stamina;
		}

		fill_exp_bar('#current-player-attributes .bar-life .exp-bar', player.life, player.max_life);
		fill_exp_bar('#current-player-attributes .bar-mana .exp-bar', player.mana, player.max_mana);
		fill_exp_bar('#current-player-attributes .bar-stamina .exp-bar', player.stamina, player.max_stamina);
		fill_exp_bar('#current-player-info .bar-exp .exp-bar', player.exp, player.level_exp);

		$('#current-player-image').attr('src', player.profile);
	});

	$('#select-player-list-container .player:first').trigger('click');

	$('#current-player-info .remove').on('click', function () {
		bootbox.confirm($(this).data('message'), function (result) {
			if(result) {
				lock_screen(true);

				$.ajax({
					url:		make_url('characters#remove'),
					data:		{id: $('#select-player-list-container .selected').data('id')},
					type:		'post',
					dataType:	'json',
					success:	function (result) {
						if(result.success) {
							location.href	= make_url('characters#select?deleted');
						} else {
							lock_screen(false);
							format_error(result);
						}
					}
				});
			}
		});
	});

	$('#current-player-info .play').on('click', function () {
		lock_screen(true);

		$.ajax({
			url:		make_url('characters#select'),
			data:		{id: $('#select-player-list-container .selected').data('id')},
			type:		'post',
			dataType:	'json',
			success:	function (result) {
				if(result.success) {
					location.href	= make_url('characters#status');
				} else {
					lock_screen(false);
					format_error(result);
				}
			}
		});
	});

	$('#current-player-change-image, #current-player-change-theme').on('click', function () {
		var	_	= $(this);
		var	win	= bootbox.dialog({message: '...', buttons: [
			{
				label: 'Fechar',
				class:	'btn btn-default'
			}
		]});

		$('.modal-dialog', win).addClass('pattern-container');
		$('.modal-content', win).addClass('with-pattern');

		$.ajax({
			url:		_.data('url'),
			success:	function (result) {
				$('.bootbox-body', win).html(result);

				// This one is for the images
				$('.modal-content', win).on('click', '.image', function () {
					win.modal('hide');
					lock_screen(true);

					$.ajax({
						url:		make_url('characters#list_images'),
						type:		'post',
						data:		{id: $(this).data('id')},
						dataType:	'json',
						success:	function (result) {
							if(result.success) {
								location.href	= make_url('characters#status');
							} else {
								lock_screen(false);
								format_error(result);
							}
						}
					});

				});

				_apply_themes_cb(win);
			}
		});
	});

	function _apply_themes_cb(win) {
		$('.modal-content', win).on('click', '.theme img', function () {
			var	theme	= $(this).parent();

			$('#popup-character-themes', win).css({
				backgroundImage:	'url(' + theme.data('background') + ')'
			});

			$('#bar-theme', win).hide();
			$('#bar-images', win).show();

			$('.attack-list', win).stop().hide();
			$('.attack-list-' + theme.data('theme'), win).stop().show();

			$('.ability-list', win).stop().hide();
			$('.ability-list-' + theme.data('theme'), win).stop().show();

			$('#theme-list', win).hide();
			$('.theme-images', win).hide();
			$('.theme-controls', win).hide();

			$('.theme-images-' + theme.data('theme'), win).show();
			$('.theme-controls-' + theme.data('theme'), win).show();
		});

		$('.theme', win).tooltip({html: true});
		$('.back', win).on('click', function () {
			$('#bar-theme', win).show();
			$('#bar-images', win).hide();

			$('.attack-list', win).stop().hide();
			$('.ability-list', win).stop().hide();

			$('#theme-list', win).show();

			$('.theme-images', win).hide();
			$('.theme-controls', win).hide();

			var	container	= $('#popup-character-themes', win);

			container.css({
				backgroundImage:	'url(' + container.data('background') + ')'
			});
		});

		cardize('#popup-character-themes #theme-list');

		$('.theme-images', win).each(function () {
			cardize(this);
		});

		$('.buy-theme, .use-theme', win).on('click', function () {
			lock_screen(true);

			var	data	= {theme: $(this).data('theme')};
			var	_this	= $(this);

			if(_this.hasClass('buy-theme')) {
				data.buy	= 1;
			} else {
				data.use	= 1;
			}

			$.ajax({
				url:		make_url('characters#list_themes'),
				type:		'post',
				data:		data,
				dataType:	'json',
				success:	function (result) {
					if(result.success) {
						location.href	= make_url('characters#status');
					} else {
						lock_screen(false);
						format_error(result);
					}
				}
			});
		});

		$('.use-theme', win).on('click', function () {
			lock_screen(true);
		});		
	}
})();