<form action="contao/main.php?do=avisota_tracking" class="tl_form" method="post">
<div class="tl_formbody">
<input type="hidden" name="FORM_SUBMIT" value="tl_filters">

<div class="tl_panel">

<div class="tl_submit_panel tl_subpanel">
<input type="image" name="filter" id="filter" src="system/themes/default/images/reload.gif" class="tl_img_submit" title="<?php echo $GLOBALS['TL_LANG']['MSC']['apply']; ?>" value="<?php echo $GLOBALS['TL_LANG']['MSC']['apply']; ?>" />
</div>

<div class="tl_recipient tl_subpanel">
<strong><?php echo $GLOBALS['TL_LANG']['avisota_tracking']['recipient_label']; ?>:</strong>
<select name="recipient" class="tl_select" onchange="this.form.submit()">
<option value="-">-</option>
<?php foreach ($this->recipients as $recipient): ?>
<option value="<?php echo $recipient; ?>"<?php if ($this->recipient == $recipient): ?> selected="selected"<?php endif; ?>><?php echo $recipient; ?></option>
<?php endforeach; ?>
</select>
</div>

<div class="tl_newsletter tl_subpanel">
<strong><?php echo $GLOBALS['TL_LANG']['avisota_tracking']['newsletter_label']; ?>:</strong>
<select name="newsletter" class="tl_select" onchange="this.form.submit()">
<option value="-">-</option>
<?php foreach ($this->newsletters as $id=>$newsletter): ?>
<option value="<?php echo $id; ?>"<?php if ($this->newsletter->id == $id): ?> selected="selected"<?php endif; ?>><?php echo $newsletter; ?></option>
<?php endforeach; ?>
</select>
</div>

<div class="clear"></div>

</div>
</div>
</form>

<div id="tl_buttons"></div>

<?php echo $this->getMessages(); ?>

<h2 class="sub_headline"><?php echo $GLOBALS['TL_LANG']['avisota_tracking']['headline']; ?></h2>

<script type="text/javascript" src="system/modules/Avisota/highstock/js/adapters/mootools-adapter.js"></script>
<script type="text/javascript" src="system/modules/Avisota/highstock/js/highstock.js"></script>

<div id="graphs">
	<h3><?php echo $GLOBALS['TL_LANG']['avisota_tracking'][$this->mode]['graph_overview_legend']; ?></h3>
	<div id="graph_overview"></div>
	<h3><?php echo $GLOBALS['TL_LANG']['avisota_tracking'][$this->mode]['graph_timeline_legend']; ?></h3>
	<div id="graph_timeline"></div>
</div>

<div id="links">
	<h3><?php echo $GLOBALS['TL_LANG']['avisota_tracking'][$this->mode]['graph_links_legend']; ?></h3>
	<table cellpadding="0" cellspacing="0" class="tl_listing">
		<tbody>
			<tr>
				<td class="tl_folder_tlist url"><?php echo $GLOBALS['TL_LANG']['avisota_tracking'][$this->mode]['url']; ?></td>
				<td class="tl_folder_tlist hits"><?php echo $GLOBALS['TL_LANG']['avisota_tracking'][$this->mode]['hits']; ?></td>
			</tr>
			<?php foreach ($this->links as $i=>$link): ?>
			<tr onmouseover="Theme.hoverRow(this, 1);" onmouseout="Theme.hoverRow(this, 0);">
				<td class="tl_file_list url"><a href="<?php echo $link['url']; ?>" onclick="window.open(this.href); return false;"><?php echo $link['url']; ?></a></td>
				<td class="tl_file_list tl_right_nowrap hits"><?php echo $link['hits']; ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

<script>
$(window).addEvent('domready', function() {
	try {
	window.overviewChart = new Highcharts.StockChart({
		chart: {
			renderTo: 'graph_overview'
		},
		rangeSelector: {
			buttons: [{
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
				type: 'month',
				count: 6,
				text: '6m'
			}, {
				type: 'year',
				count: 1,
				text: '1y'
			}, {
				type: 'all',
				text: 'All'
			}],
			selected: 0
		},
		xAxis: {
			maxZoom: 14 * 24 * 3600000 // fourteen days
		},
		yAxis: {
			min: 0,
			title: {
				text: ''
			}
		},
		tooltip: {
			formatter: function(){
				var point = this.points[0],
					series = point.series,
					unit = series.unit && series.unit[0],
					format = '%A, %b %e, %Y, %H:%M', // with hours
					s;

				if (unit == 'day') { // skip hours
					format = '%A, %b %e, %Y';
				}

				return '<b>' + Highcharts.dateFormat(format, this.x) + '</b>' +
					'<br/>Reads: ' + Highcharts.numberFormat(this.points[0].y, 0);
			}
		},
		series: [{
			name: 'Reads',
			data: [<?php
			$reads = array();
			foreach ($this->reads as $i=>$read):
				$min = $this->parseDate('i', $read['tstamp']);
				$min -= $min%15; // reduce to 15 minutes
				$reads[strtotime($this->parseDate('d.m.Y H:', $read['tstamp']) . $min)*1000]++;
			endforeach;
			$count = 0;
			$data = array();
			foreach ($reads as $k=>$v):
				$count += $v;
				$data[] = '[' . $k . ',' . $count . ']';
			endforeach;
			echo implode(",\n", $data);
			?>]
		}, {
			name: 'Reacts',
			data: [<?php
			$reacts = array();
			foreach ($this->reads as $i=>$read):
				$times = explode(',', $read['times']);
				foreach ($times as $time):
					$min = $this->parseDate('i', $read['tstamp']);
					$min -= $min%15; // reduce to 15 minutes
					$reacts[strtotime($this->parseDate('d.m.Y H:', $read['tstamp']) . $min)*1000]++;
				endforeach;
			endforeach;
			$count = 0;
			$data = array();
			foreach ($reacts as $k=>$v):
				$count += $v;
				$data[] = '[' . $k . ',' . $count . ']';
			endforeach;
			echo implode(",\n", $data);
			?>]
		}]
	});
	} catch(e) {
		alert(e);
	}
});

/*
jQuery(document).ready(function() {
	/* overview graph * /
	var data = [
		[[<?php echo $this->total; ?>, 0]],
		[[<?php echo $this->reads; ?>, 0]],
		[[<?php echo $this->reacts; ?>, 0]]
	];
	var overview = jQuery.jqplot('graph_overview', data, {
		height: 160,
		axes: {
			xaxis: {
				min: 0
			},
			yaxis: {
				showTicks: false
			}
		},
		seriesDefaults: {
			shadow: false,
			renderer: jQuery.jqplot.BarRenderer,
			pointLabels: { show: true, location: 'e', edgeTolerance: -15, fontSize: '10pt' },
			rendererOptions: { barDirection: 'horizontal' }
		},
		series:
		[
			{ label: '<?php echo $GLOBALS['TL_LANG']['avisota_tracking'][$this->mode]['total'];  ?>' },
			{ label: '<?php echo $GLOBALS['TL_LANG']['avisota_tracking'][$this->mode]['reads'];  ?>' },
			{ label: '<?php echo $GLOBALS['TL_LANG']['avisota_tracking'][$this->mode]['reacts']; ?>' }
		],
		legend: {
			show: true,
			location: 'e',
			placement: 'outsideGrid'
		}
	});
	/* links graph * /
	var data = [
		<?php foreach ($this->links as $i=>$link): if ($i>0): ?>,<?php endif; ?>
		['<?php echo $link['url']; ?>', <?php echo $link['hits']; ?>]
		<?php endforeach; ?>
	];
	var overview = jQuery.jqplot('graph_links', [data], {
		height: 0,
		axesDefaults: {
			tickRenderer: jQuery.jqplot.CanvasAxisTickRenderer,
			tickOptions: { angle: -90, fontSize: '10pt' }
		},
		axes: {
			xaxis: {
				renderer: jQuery.jqplot.CategoryAxisRenderer
			},
			yaxis: {
				min: 0,
			}
		},
		seriesDefaults: {
			shadow: false,
			renderer: jQuery.jqplot.BarRenderer,
			pointLabels: { show: true, location: 'n', edgeTolerance: -15 }
		}
	});
});
*/
</script>