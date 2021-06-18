var	I18n	= {
	t:	function (key, assigns, locale) {
		var	s	= I18n.translations[locale || I18n.default_locale][key] || '-- TRANSLATION MISSING: ' + key + ' --';

		if(assigns) {
			for(var i in assigns) {
				s	= s.replace('#{' + i + '}', assigns[i]);
			}
		}

		return s;
	},
	default_locale:	'pt-BR',
	translations: {}
};