(function () {
	_locked	= [];

	$('#btn-enter-npc-battle').on('click', function () {
		lock_screen(true);

		$.ajax({
			url:		make_url('battle_npcs#accept'),
			dataType:	'json',
			success:	function (result) {
				if(result.success) {
					location.href	= make_url('battle_npcs#fight');
				} else {
					format_error(result);
				}
			}
		});
	});
})();