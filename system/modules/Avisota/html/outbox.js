var Outbox = new Class({
	initialize: function(outbox, newsletter, cycleTime, cyclePause, expectedTime, holdOnErrors, maxErrorCount, maxErrorRate, messages)
	{
		this.outbox = outbox;
		this.newsletter = newsletter;
		this.cycleTimeSum = cycleTime/10;
		this.cyclePause = cyclePause;
		this.expectedTime = expectedTime;
		this.holdOnErrors = holdOnErrors;
		this.maxErrorCount = maxErrorCount;
		this.maxErrorRate = maxErrorRate;
		this.messages = messages;
		
		this.remeaningTime = 0;
		this.cycleTimeCount = .1;
		this.cycleCountSum = 0;
		this.cycleCountCount = 0;
		
		this.outstanding = parseInt(outbox.outstanding);
		this.sended = parseInt(outbox.recipients - outbox.outstanding - outbox.failed);
		this.failed = parseInt(outbox.failed);
		this.elapsedTime = 0;
		
		this.logContainer = $('outbox_log').getElement('tbody');
		this.logElement = null;
		this.logDummy = this.logContainer.getElement('tr').dispose();
		
		var timerTrigger = false;
		var timer = function() {
			this.elapsedTime ++;
			$('elapsed_time').set('html', this.elapsedTime.formatTime());
			
			this.expectedTime --;
			$('due_time').set('html', this.expectedTime.formatTime());
			
			if (this.remeaningTime > 0 && this.logElement) {
				this.remeaningTime --;
				this.logElement.getElement('td.time').set('html', (-this.remeaningTime).formatTime());
			}
			
			timerTrigger = timer.delay(1000, this);
		};
		
		this.request = new Request.JSON({
			url: 'system/modules/Avisota/AvisotaTransport.php',
			link: 'ignore',
			onRequest: function() {
				this.logElement = this.logDummy
					.clone()
					.inject(this.logContainer, 'top');
				
				this.remeaningTime = parseInt(this.cycleTimeSum / this.cycleTimeCount);
			}.bind(this),
			onComplete: function() {
				this.logElement
					.getElement('td.indicator img')
					.src = 'system/modules/Avisota/html/blank.gif';
				this.remeaningTime = 0;
			}.bind(this),
			onSuccess: function(responseJSON, responseText) {
				if (responseJSON) {
					if (responseJSON.success.length + responseJSON.failed.length == 0)
					{
						window.location.search = window.location.search.replace(/&act=send/, '').replace(/&id=\d+/, '');
					}
					else
					{
						// recipient counting
						this.outstanding -= responseJSON.success.length + responseJSON.failed.length;
						this.sended += responseJSON.success.length;
						this.failed += responseJSON.failed.length;
						var total = responseJSON.success.length + responseJSON.failed.length;
						this.cycleCountSum += total;
						this.cycleCountCount ++;
						
						// time calculation
						this.cycleTimeSum += responseJSON.time;
						this.cycleTimeCount ++;
						this.expectedTime = this.outstanding / (this.cycleCountSum / this.cycleCountCount) * (this.cycleTimeSum / this.cycleTimeCount);
						
						// update ui
						$('outstanding').set('html', this.outstanding.formatNumber());
						$('sended').set('html', this.sended.formatNumber());
						$('failed').set('html', this.failed.formatNumber());
						
						this.logElement.getElement('td.sended').set('html', responseJSON.success.length.formatNumber());
						this.logElement.getElement('td.failed').set('html', responseJSON.failed.length.formatNumber());
						this.logElement.getElement('td.time').set('html', responseJSON.time.formatTime());

						if (!this.holdOnErrors
							|| (responseJSON.failed.length < this.maxErrorCount
								&& (total <= 2 * this.maxErrorCount
									|| responseJSON.failed.length / total < this.maxErrorRate))
							|| confirm(this.messages.toMuchErrorsConfirm)) {
							this.request.post.delay(this.cyclePause, this.request, { id: this.outbox.id, action: 'send' });
						}
						else {
							window.clearTimeout(timerTrigger);

							new Element('tr')
								.adopt(new Element('td', { 'colspan': 3, 'class': 'tl_file_list aborted', 'text': this.messages.aborted }))
								.adopt(new Element('td', { 'class': 'tl_file_list', 'text': ' ' }))
								.inject(this.logContainer, 'top');
						}
					}
				}
				// logged out
				else if (responseText.indexOf('name="FORM_SUBMIT" value="tl_login"') > -1) {
					window.location.reload();
				}
				// other error
				else {
					window.clearTimeout(timerTrigger);
					
					var e = $('transport_error');
					e.setStyle('display', '');
					e.getElement('pre.response').set('text', responseText);
				}
			}.bind(this),
			onError: function(text, error) {
				window.clearTimeout(timerTrigger);
				
				var e = $('transport_error');
				e.setStyle('display', '');
				e.getElement('pre.response').set('text', '');
				new Element('div').set('text', error).inject(e.getElement('pre.response'));
				new Element('div').set('html', text).inject(e.getElement('pre.response'));
			}.bind(this)
		});
		
		(function() {
			timerTrigger = timer.delay(1000, this);
			
			this.request.post({ id: this.outbox.id, action: 'send' });
		}).delay(100, this);
	}
});
