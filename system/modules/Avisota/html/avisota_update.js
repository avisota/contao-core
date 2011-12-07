$(window).addEvent('domready', function() {
	var form = $('avisota_update');

	function doUpdate()
	{
		var update = form.getElements('input[type="checkbox"][name="update[]"]');
		if (update.length) {
			update = update[update.length-1];

			new Request({
				url: 'contao/main.php',
				data: {
					'do': 'avisota_update',
					isAjax: 1,
					update: update.value
				},
				method: 'get',
				evalScripts: false,
				onRequest: function() {
					update = new Element('img')
						.set('src', 'system/modules/Avisota/html/update.gif')
						.replaces(update);
				},
				onSuccess: function(responseText, responseXML) {
					update = new Element('img')
						.set('src', 'system/modules/Avisota/html/updated.png')
						.set('alt', responseText)
						.set('title', responseText)
						.replaces(update);
					setTimeout(doUpdate, 1);
				},
				onFailure: function(xhr) {
					console.log(arguments);
					update = new Element('img')
						.set('src', 'system/modules/Avisota/html/error.png')
						.set('alt', xhr.responseText)
						.set('title', xhr.responseText)
						.replaces(update);
				}
			}).send();
		} else {
			window.location.href = window.location.href.replace(/main\.php.*/, 'main.php?do=repository_manager&update=database');
		}
	}

	form.addEvent('submit', function() {
		window.setTimeout(doUpdate, 1);
		form.disabled = true;
		form.getElement('input[type=submit]').disabled = true;
		return false;
	});
});
