(function () {
	var container	= $('#inventory-container');

	container.on('click', '.item', function (e) {
		var	_	= $(this);

		if(parseInt(_.data('consumable'))) {
			lock_screen(true);

			$.ajax({
				url:		make_url('characters#inventory'),
				data:		{item: _.data('id')},
				type:		'post',
				dataType:	'json',
				success:	function (result) {
					lock_screen(false);

					if(result.success) {
						if(result.delete) {
							_.remove();
						}

						if(result.quantity) {
							$('.quantity', _).html('x' + result.quantity);
						}

						character_stats({
							life:			result.life,
							max_life:		result.max_life,
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
		}

		e.stopPropagation();
	});

	$('#inventory-trigger').on('click', function () {
		var	_	= $(this);

		if(container.hasClass('shown')) {
			_.removeClass('shown');
			container.removeClass('shown');
		} else {
			_.addClass('shown');
			container.addClass('shown');
			container.html(_.data('text'));

			$.ajax({
				url:		make_url('characters#inventory'),
				success:	function (result) {
					container.html(result);

					$('.item img', container).each(function () {
						$(this).popover({
							content:	$($(this).data('source')).html(),
							html:		true
						});
					}).on('shown.bs.popover', function () {
						var _		= $(this);
						var	popover	= $('.popover', _.parent());

						$(document.body).append(popover);

						popover.css({
							position:	'absolute',
							left:		_.offset().left - popover.width() - 4,
							top:		_.offset().top - (popover.height() / 3)
						});
					});
				}
			});
		}
	});
})();