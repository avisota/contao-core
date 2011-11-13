<script type="text/javascript" src="system/modules/Avisota/jquery/jquery.min.js"></script>
<script type="text/javascript">jQuery.noConflict();</script>
<script type="text/javascript" src="system/modules/Avisota/highstock/js/highstock.js"></script>
<script>
function drawChart() {
	if (!sends || !reads || !reacts || !links<?php if ($this->mode == 'recipient'): ?> || !flags<?php endif; ?>) return;

	if (!sends.length && !reads.length && !reacts.length<?php if ($this->mode == 'recipient'): ?> && !flags.length<?php endif; ?>) {
		$('chart').setAttribute('style', '');
		$('chart')
			.addClass('empty_stats')
			.set('text', '<?php echo specialchars($GLOBALS['TL_LANG']['avisota_tracking']['empty_stats']); ?>');
	} else {
		equalize([sends, reads, reacts]);

		var send = sends[sends.length-1][1];
		var read = reads[reads.length-1][1];
		var react = reacts[reacts.length-1][1];

		$('col_sends').set('text', send.formatNumber());
		$('col_reads').set('text', read.formatNumber());
		$('col_reads_percent').set('text', parseInt(read/send*100) + ' %');
		$('col_reacts').set('text', react.formatNumber());
		$('col_reacts_percent').set('text', parseInt(react/send*100) + ' %');
		$('col_reacts_percent2').set('text', parseInt(react/read*100) + ' %');

		<?php if ($this->mode == 'recipient'): ?>
		if (flags[0] && (!sends[0] || flags[0].x < sends[0][0])) {
			sends.splice(0, 0, [flags[0].x, 0]);
		}

		<?php endif; ?>

		$('chart').setAttribute('style', '');

		var timespan = (sends[sends.length-1][0] - sends[0][0]) / 1000;

		new Highcharts.StockChart({
			chart: {
				renderTo: 'chart'
			},
			rangeSelector: {
				buttons: [{
					type: 'minute',
					count: 60,
					text: '1h'
				}, {
					type: 'minute',
					count: 6*60,
					text: '6h'
				}, {
					type: 'minute',
					count: 12*60,
					text: '12h'
				}, {
					type: 'day',
					count: 1,
					text: '1d'
				}, {
					type: 'day',
					count: 2,
					text: '2d'
				}, {
					type: 'day',
					count: 3,
					text: '3d'
				}, {
					type: 'week',
					count: 1,
					text: '1w'
				}, {
					type: 'month',
					count: 1,
					text: '1m'
				}, {
					type: 'all',
					text: 'All'
				}],
				selected: getRangeIndex(timespan)
			},
			yAxis: {
				min: 0,
				title: {
					text: ''
				}
			},
			tooltip: {
				formatter: function(){
					if (this.points) {
						var point = this.points[0];
						var series = point.series;
						var unit = series.unit && series.unit[0];
						var format = series.tooltipHeaderFormat;

						var s = '<b>' + Highcharts.dateFormat(format, this.x) + '</b>';
						for (var i=0; i<this.points.length; i++) {
							s += '<br/><b style="color:' + this.points[i].series.color + '">' + this.points[i].series.name + '</b>: ' + Highcharts.numberFormat(this.points[i].y, 0);
						}
						return s;
					} else {
						return this.series.data[0].text;
					}
				}
			},
			series: [{
				name: '<?php echo specialchars($GLOBALS['TL_LANG']['avisota_tracking'][$this->mode]['sends']); ?>',
				data: sends<?php if ($this->mode == 'recipient'): ?>,
				id: 'newsletter'<?php endif; ?>
			}, {
				name: '<?php echo specialchars($GLOBALS['TL_LANG']['avisota_tracking'][$this->mode]['reads']); ?>',
				data: reads
			}, {
				name: '<?php echo specialchars($GLOBALS['TL_LANG']['avisota_tracking'][$this->mode]['reacts']); ?>',
				data: reacts
			}<?php if ($this->mode == 'recipient'): ?>, {
				type: 'flags',
				data: flags,
				onSeries: 'newsletter',
				shape: 'circlepin',
				width: 16,
				cursor: 'pointer'
			}<?php endif; ?>]
		});
	}

	if (!links.length) {
		$('chart_links').setAttribute('style', '');
		$('chart_links')
			.addClass('empty_stats')
			.set('text', '<?php echo specialchars($GLOBALS['TL_LANG']['avisota_tracking']['empty_stats']); ?>');
	} else {
		<?php if ($this->mode == 'recipient'): ?>

		if (flags[0] && (!links[0].data[0] || flags[0].x < links[0].data[0][0])) {
			links[0].data.splice(0, 0, [flags[0].x, 0]);
		}

		<?php endif; ?>
		var link_arrays = links.map(function(link) {
			return link.data;
		});
		equalize(link_arrays);

		<?php if ($this->mode == 'recipient'): ?>

		links[0].id = 'first';
		links.push({
			type: 'flags',
			data: flags,
			onSeries: 'first',
			shape: 'circlepin',
			width: 16,
			cursor: 'pointer'
		});

		<?php endif; ?>
		$('chart_links').setAttribute('style', '');

		var timespan = (link_arrays[0][link_arrays[0].length-1][0] - link_arrays[0][0][0]) / 1000;

		new Highcharts.StockChart({
			chart: {
				renderTo: 'chart_links'
			},
			rangeSelector: {
				buttons: [{
					type: 'minute',
					count: 60,
					text: '1h'
				}, {
					type: 'minute',
					count: 6*60,
					text: '6h'
				}, {
					type: 'minute',
					count: 12*60,
					text: '12h'
				}, {
					type: 'day',
					count: 1,
					text: '1d'
				}, {
					type: 'day',
					count: 2,
					text: '2d'
				}, {
					type: 'day',
					count: 3,
					text: '3d'
				}, {
					type: 'week',
					count: 1,
					text: '1w'
				}, {
					type: 'month',
					count: 1,
					text: '1m'
				}, {
					type: 'all',
					text: 'All'
				}],
				selected: getRangeIndex(timespan)
			},
			yAxis: {
				min: 0,
				title: {
					text: ''
				}
			},
			tooltip: {
				formatter: function(){
					if (this.points) {
						var point = this.points[0];
						var series = point.series;
						var unit = series.unit && series.unit[0];
						var format = series.tooltipHeaderFormat;

						var s = '<b>' + Highcharts.dateFormat(format, this.x) + '</b>';
						for (var i=0; i<this.points.length; i++) {
							s += '<br/><strong style="color:' + this.points[i].series.color + '">' + this.points[i].series.name + '</strong>: ' + Highcharts.numberFormat(this.points[i].y, 0);
						}

						return s;
					} else {
						return this.series.data[0].text;
					}
				}
			},
			series: links
		});
	}
}
</script>