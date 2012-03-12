var Avisota = {
	toggleConfirmation: function(link) {
		link = $(link);
		new Request.JSON({
			url: 'contao/main.php',
			onSuccess: function(responseJSON, responseText) {
				if (responseJSON.blacklisted) {

				} else {
					link.setProperty('data-confirmed', responseJSON.confirmed ? '1' : '')
					var img = link.getElement('img');
					img.setProperty('src',
						img.getProperty('src').replace(/\/(in)?visible.gif/, (responseJSON.confirmed ? '/visible.gif' : '/invisible.gif')));
				}
			}
		}).get({
			'do': 'avisota_recipients',
			'act': 'toggleConfirmation',
			'recipient': link.getProperty('data-recipient'),
			'list': link.getProperty('data-list'),
			'confirmed': link.getProperty('data-confirmed') ? '' : '1'
		})
	}
};
