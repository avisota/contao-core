var Outbox = new Class({
	initialize: function(outbox, newsletter)
	{
		this.outbox = outbox;
		this.newsletter = newsletter;
		
		this.request = new Request.JSON({
			url: 'system/modules/Avisota/AvisotaTransport.php',
			link: 'ignore',
			onSuccess: function(responseJSON, responseText) {
				
			},
			onError: function(text, error) {
				
			}
		});
	},
	set: function(k, v) {
		// format number
		if (isFinite(v))
		{
			var a = v.stoString().split('');
			v = '';
			var n = 0;
			while (a.length > 0) {
				if (n%3 == 0)
					v = '.' + v;
				v = a.pop() + v;
				n ++;
			}
		}
		// set text of element
		$(k).set('text', v);
	},
	start: function() {
		this.get({ id: this.outbox.id });
	}
});
