var Outbox = function (totalCount, queueId, cycleTime, cyclePause) {
    var containerElement = $('avisota_outbox');

    var failedCount = 0;
    var successCount = 0;
    var openCount = parseInt(totalCount);
    var timeout = 0;
    var duration = 0;

    var failedCountElement = $('avisota_outbox_execution_failed');
    var successCountElement = $('avisota_outbox_execution_success');
    var openCountElement = $('avisota_outbox_execution_open');
    var timeoutElement = $('avisota_outbox_execution_timeout');
    var durationElement = $('avisota_outbox_execution_duration');

    var progressFailedElement = $('avisota_outbox_progress_failed');
    var progressSuccessElement = $('avisota_outbox_progress_success');
    var progressOpenElement = $('avisota_outbox_progress_working');

    var timerTrigger = false;
    var timer = function () {
        duration++;
        durationElement.set('html', duration.formatTime(true, true));

        if (timeout > 0) {
            timeout--;
            timeoutElement.set('html', timeout.formatTime());
        }
        else {
            timeoutElement.set('html', '0:00');
        }

        timerTrigger = timer.delay(1000, this);
    };

    function setProgress(failed, success, open) {
        if (failed === undefined) {
            failed = Math.floor(failedCount / totalCount * 100);
        }
        if (success === undefined) {
            success = Math.floor(successCount / totalCount * 100);
        }
        if (open === undefined) {
            open = 100 - failed - success;
        }
        progressFailedElement.setStyle('width', failed + '%');
        progressSuccessElement.setStyle('width', success + '%');
        progressOpenElement.setStyle('width', open + '%');
    }

    var request = new Request.JSON({
        url: 'system/modules/avisota-core/web/queue_execute.php',
        link: 'ignore',
        onRequest: function () {
            timeout = parseInt(cycleTime);
            timeoutElement.set('html', timeout.formatTime());
            containerElement
                .removeClass('initializing')
                .removeClass('waiting')
                .removeClass('finished')
                .addClass('running');
        },
        onSuccess: function (responseJSON, responseText) {
            if (!responseJSON || responseJSON.error) {
                // logged out
                if (responseText.indexOf('tl_login') > -1) {
                    window.location.reload();
                }

                // other error
                else {
                    window.clearTimeout(timerTrigger);
                    containerElement
                        .removeClass('initializing')
                        .removeClass('running')
                        .removeClass('waiting')
                        .removeClass('finished')
                        .addClass('errored');
                    setProgress(100, 0, 0);
                    $('avisota_outbox_exception').setStyle('display', 'block');
                    $('avisota_outbox_exception_text').set('text', responseText);
                }
            }
            else if ((responseJSON.failed + responseJSON.success) > 0) {
                failedCount += parseInt(responseJSON.failed);
                successCount += parseInt(responseJSON.success);
                openCount -= parseInt(responseJSON.failed + responseJSON.success);

                failedCountElement.set('text', failedCount.formatNumber());
                successCountElement.set('text', successCount.formatNumber());
                openCountElement.set('text', openCount.formatNumber());
                setProgress();

                containerElement
                    .removeClass('initializing')
                    .removeClass('running')
                    .removeClass('finished')
                    .addClass('waiting');

                timeout = parseInt(cyclePause);
                timeoutElement.set('html', timeout.formatTime());
                request.get.delay(cyclePause * 1000, request, {id: queueId});
            }
            else {
                containerElement
                    .removeClass('initializing')
                    .removeClass('running')
                    .removeClass('waiting')
                    .addClass('finished');

                window.clearTimeout(timerTrigger);
                timeout = 0;
            }
        },
        onError: function (text, error) {
            window.clearTimeout(timerTrigger);
            containerElement
                .removeClass('initializing')
                .removeClass('running')
                .removeClass('waiting')
                .removeClass('finished')
                .addClass('errored');
            setProgress(100, 0, 0);
            $('avisota_outbox_exception').setStyle('display', 'block');
            $('avisota_outbox_exception_text').set('html', text);
        },
        // Caching exception thanks richardhj #224
        onFailure: function (xhr) {
            window.clearTimeout(timerTrigger);
            containerElement
                .removeClass('initializing')
                .removeClass('running')
                .removeClass('waiting')
                .removeClass('finished')
                .addClass('errored');
            setProgress(100, 0, 0);
            $('epost_outbox_exception').setStyle('display', 'block');
            var response = JSON.decode(xhr.response);
            $('avisota_outbox_exception_text').set('html', response.error);
        }
    });

    (function () {
        timerTrigger = timer.delay(1000, this);

        request.get({id: queueId});
    }).delay(100, this);
};

/*
 var Outbox = new Class({
 initialize: function(outbox, newsletter, cycleTime, cyclePause, expectedTime)
 {
 this.outbox = outbox;
 this.newsletter = newsletter;
 this.cycleTimeSum = cycleTime/10;
 this.cyclePause = cyclePause;
 this.expectedTime = expectedTime;

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
 url: 'system/modules/avisota/AvisotaTransport.php',
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
 .src = 'system/modules/avisota/html/blank.gif';
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
 this.cycleCountSum += responseJSON.success.length + responseJSON.failed.length;
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

 this.request.post.delay(this.cyclePause, this.request, { id: this.outbox.id, action: 'send' });
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
 e.getElement('pre.response').set('text', error);
 }.bind(this)
 });

 (function() {
 timerTrigger = timer.delay(1000, this);

 this.request.post({ id: this.outbox.id, action: 'send' });
 }).delay(100, this);
 }
 });
 */
