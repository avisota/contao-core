$(window).addEvent('domready', function() {
	var form = $('avisota_update');

	function doUpdate()
	{
		var update = form.getElements('input[type="checkbox"][name="update[]"][checked]');
		for (var i=0; i<update.length; i++) {
			if (update[i].checked) {
				update = update[i];

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
							.set('src', 'system/modules/Avisota2/html/update.gif')
							.replaces(update);
					},
					onSuccess: function(responseText, responseXML) {
						update = new Element('img')
							.set('src', 'system/modules/Avisota2/html/updated.png')
							.set('alt', responseText)
							.set('title', responseText)
							.replaces(update);
						update.getParent().getNext().slide('out');
						setTimeout(doUpdate, 1);
					},
					onFailure: function(xhr) {
						console.log(arguments);
						update = new Element('img')
							.set('src', 'system/modules/Avisota2/html/error.png')
							.set('alt', xhr.responseText)
							.set('title', xhr.responseText)
							.replaces(update);
					}
				}).send();
				return;
			}
		}
		window.location.href = window.location.href.replace(/main\.php.*/, 'main.php?do=repository_manager&update=database');
	}

	var doupdate = form.getElement('input[type=submit][name=doupdate]');
	var dbupdate = form.getElement('input[type=submit][name=dbupdate]');

	function startUpdate()
	{
		window.setTimeout(doUpdate, 1);
		form.disabled = true;
		doupdate.disabled = true;
		dbupdate.disabled = true;
		return false;
	}

	doupdate.addEvent('click', startUpdate);
});
