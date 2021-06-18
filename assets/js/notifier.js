(function () {
	var	sockjs	= new SockJS('http://animeallstarsgame.com.br:2552/notifier');

	sockjs.onopen	= function () {
		sockjs.send('connect');
	}

	sockjs.onmessage	= function (result) {
		console.log(result);
		console.log(result.data);
	}
})();