(function () {
	var	join_form	= $('#f-user-join');

	if(join_form.length) {
		join_form.on('submit', function (e) {
			lock_screen(true);

			e.preventDefault();

			$.ajax({
				url:		make_url('users#join_complete'),
				data:		join_form.serialize(),
				type:		'post',
				dataType:	'json',
				success:	function (result) {
					if(result.success) {
						location.href	= make_url('users#activation/' + result.key);
					} else {
						lock_screen(false);
						format_error(result);
					}
				}
			});
		});
	}
})();