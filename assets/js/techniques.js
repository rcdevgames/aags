(function () {
	var	is_learning	= false;

	$('#technique-list').on('click', '.learn', function () {
		if(is_learning) {
			return;
		}

		var _		= $(this);
		is_learning	= true;

		lock_screen(true);

		$.ajax({
			url:		make_url('techniques#learn'),
			type:		'post',
			data:		{id: _.data('id')},
			dataType:	'json',
			success:	function (result) {
				is_learning	= false;
				lock_screen(false);

				if(result.success) {
					$(document.body).scrollTop(0);

					var	target	= $('#learn-status-' + _.data('id'));

					target.animate({
						opacity: 0
					}, {
						done:	function () {
							target.remove();
						}
					});

					$('#learn-technique-info-container').html(result.view);

					character_exp(result.exp, result.max_exp, result.level);

					character_stats({
						mana:			result.mana,
						max_mana:		result.max_mana,
						stamina:		result.stamina,
						max_stamina:	result.max_stamina
					});
				} else {
					format_error(result);
				}
			}
		});
	});

	$('#ability-speciality-container-tabs').on('click', 'a', function (e) {
		e.preventDefault();
		 $(this).tab('show');

		 $('#ability-speciality-container-tabs a').removeClass('btn-primary');
		 $(this).addClass('btn-primary');

		 $('#ability-speciality-learn-container .description').hide();
		 $('#ability-speciality-container-description-' + $(this).data('id')).show();
	});

	$('#ability-speciality-container-tabs li.default a').trigger('click');

	$('#ability-speciality-learn-container').on('click', '.learn-master', function () {
		var	_	= $(this);

		jconfirm($(this).data('confirmation'), function () {
			lock_screen(true);

			$.ajax({
				url:		parseInt(_.data('ability')) ? make_url('techniques/train_ability') : make_url('techniques/train_speciality'),
				type:		'post',
				dataType:	'json',
				data:		{id: _.data('id')},
				success:	function (result) {
					lock_screen(false);

					if (result.success) {
						jalert(result.message, function () {
							location.reload();
						});
					} else {
						format_error(result);
					}
				}
			});
		})
	});

	$('#ability-speciality-container').on('click', '.learn', function () {
		var _		= $(this);
		is_learning	= true;

		lock_screen(true);

		$.ajax({
			url:		parseInt(_.data('ability')) ? make_url('techniques#learn_ability') : make_url('techniques#learn_speciality'),
			type:		'post',
			data:		{id: _.data('id')},
			dataType:	'json',
			success:	function (result) {
				is_learning	= false;
				lock_screen(false);

				if(result.success) {
					$(document.body).scrollTop(0);

					var	target	= $(_.data('target'));

					target.animate({
						opacity: 0
					}, {
						done:	function () {
							target.remove();
						}
					});

					$('#learn-ability-speciality-info-container').html(result.view);
				} else {
					format_error(result);
				}
			}
		});
	});

	var	technique_wait_timer	= $('#technique-wait-timer');

	if(technique_wait_timer.length) {
		create_timer(
			technique_wait_timer.data('hours'),
			technique_wait_timer.data('minutes'),
			technique_wait_timer.data('seconds'),
			'technique-wait-timer',
			function () { location.reload() },
			null,
			true
		);
	}

	$('#technique-training-status-container .cancel').on('click', function () {
		jconfirm($(this).data('confirmation'), function () {
			lock_screen(true);

			$.ajax({
				url:		make_url('trainings#technique_wait'),
				type:		'post',
				data:		{cancel: 1},
				dataType:	'json',
				success:	function (result) {
					if(result.success) {
						location.href	= make_url('trainings#techniques');
					} else {
						lock_screen(false);
						format_error(result);
					}
				}
			});
		});
	});

	$('#technique-training-status-container .finish').on('click', function () {
		lock_screen(true);

		$.ajax({
			url:		make_url('trainings#technique_wait'),
			type:		'post',
			data:		{finish: 1},
			dataType:	'json',
			success:	function (result) {
				if(result.success) {
					location.href	= make_url('trainings#techniques');
				} else {
					lock_screen(false);
					format_error(result);
				}
			}
		});
	});
})();