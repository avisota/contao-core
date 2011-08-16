<?php
$GLOBALS['TL_CSS'][] = 'system/modules/Avisota/jqplot/jquery.jqplot.min.css';
?>
<script type="text/javascript" src="system/modules/Avisota/jqplot/jquery-1.4.2.min.js"></script>
<script type="text/javascript">jQuery.noConflict();</script>
<script type="text/javascript" src="system/modules/Avisota/jqplot/jquery.jqplot.min.js"></script>
<script type="text/javascript" src="system/modules/Avisota/jqplot/plugins/jqplot.highlighter.min.js"></script>
<script type="text/javascript" src="system/modules/Avisota/jqplot/plugins/jqplot.cursor.min.js"></script>
<script type="text/javascript" src="system/modules/Avisota/jqplot/plugins/jqplot.dateAxisRenderer.min.js"></script>
<script>
function drawChart() {
	if (!sends || !reads || !reacts || !links) return;

	if (!sends.length && !reads.length && !reacts.length) {
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

		$('chart').setAttribute('style', '');

		jQuery.jqplot('chart', [sends, reads, reacts], {
			height: 420,
			axes: {
				xaxis: {
					renderer: jQuery.jqplot.DateAxisRenderer,
					tickOptions: {
						formatString:'%#d.&nbsp;%b<br>%H:%M'
					}
				},
				yaxis: {
					min: 0,
					tickOptions: {
						formatString: '%d'
					}
				}
			},
			seriesDefaults: {
				showMarker: false
			},
			highlighter: {
				show: true
			},
			cursor: {
				show: true,
				tooltipLocation:'nw'
			}
		});
	}

	if (!links.length) {
		$('chart_links').setAttribute('style', '');
		$('chart_links')
			.addClass('empty_stats')
			.set('text', '<?php echo specialchars($GLOBALS['TL_LANG']['avisota_tracking']['empty_stats']); ?>');
	} else {
		$('chart_links').setAttribute('style', '');

		var data = links.map(function(link) {
			return link.data;
		});
		equalize(data);

		jQuery.jqplot('chart_links', data, {
			height: 420,
			axes: {
				xaxis: {
					renderer: jQuery.jqplot.DateAxisRenderer,
					tickOptions: {
						formatString:'%#d.&nbsp;%b<br>%H:%M'
					}
				},
				yaxis: {
					min: 0,
					tickOptions: {
						formatString: '%d'
					}
				}
			},
			seriesDefaults: {
				showMarker: false
			},
			series: links.map(function(link) {
				return {
					label: link.name
				};
			}),
			legend: {
				show: true,
				location: 'nw'
			},
			highlighter: {
				show: true
			},
			cursor: {
				show: true,
				tooltipLocation:'nw'
			}
		});
	}
}
</script>