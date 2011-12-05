$(window).addEvent('domready', function() {
	var form = $('avisota_update');

	function doUpdate()
	{
		var update = form.getElements('input[type="checkbox"][name="update[]"]');
		if (update.length) {
			update = update[update.length-1];

			new Request.HTML({
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
				onSuccess: function(responseTree, responseElements, responseHTML, responseJavaScript) {
					responseElements[0].replaces(update);
					setTimeout(doUpdate, 1);
				},
				onFailure: function() {
					update = new Element('img')
						.set('src', 'system/modules/Avisota/html/error.png')
						.replaces(update);
				}
			}).send();
		}
	}

	form.addEvent('submit', function() {
		window.setTimeout(doUpdate, 1);
		form.disabled = true;
		form.getElement('input[type=submit]').disabled = true;
		return false;
	});
});
