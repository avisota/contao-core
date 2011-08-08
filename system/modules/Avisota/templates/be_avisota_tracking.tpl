<form action="contao/main.php?do=avisota_tracking" class="tl_form" method="post">
<div class="tl_formbody">
<input type="hidden" name="FORM_SUBMIT" value="tl_filters">

<div class="tl_panel">

<div class="tl_submit_panel tl_subpanel">
<input type="image" name="filter" id="filter" src="system/themes/default/images/reload.gif" class="tl_img_submit" title="<?php echo $GLOBALS['TL_LANG']['MSC']['apply']; ?>" value="<?php echo $GLOBALS['TL_LANG']['MSC']['apply']; ?>" />
</div>

<div class="tl_recipient tl_subpanel">
<strong><?php echo $GLOBALS['TL_LANG']['avisota_tracking']['recipient_label']; ?>:</strong>
<input type="text" id="recipient" name="recipient" class="tl_text" value="<?php echo specialchars($this->recipient); ?>" />
</div>

<div class="tl_newsletter tl_subpanel">
<strong><?php echo $GLOBALS['TL_LANG']['avisota_tracking']['newsletter_label']; ?>:</strong>
<select name="newsletter" class="tl_select" onchange="this.form.submit()">
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

<script type="text/javascript" src="system/modules/Avisota/Meio.Autocomplete/<?php if (VERSION == 2.9): ?>1.0<?php else: ?>2.0<?php endif; ?>/Meio.Autocomplete.js"></script>
<script type="text/javascript" src="system/modules/Avisota/jquery/jquery.min.js"></script>
<script type="text/javascript">jQuery.noConflict();</script>
<script type="text/javascript" src="system/modules/Avisota/highstock/js/highstock.js"></script>

<div id="graphs">
	<h3><?php echo $GLOBALS['TL_LANG']['avisota_tracking'][$this->mode]['graph_overview_legend']; ?></h3>
	<div id="graph_overview" style="position: relative; height: 420px; background: url(system/modules/Avisota/html/loading.gif) no-repeat center center;"></div>
</div>

<?php if (is_array($this->newsletter_reads)): ?>
<div id="newsletters">
	<h3><?php echo $GLOBALS['TL_LANG']['avisota_tracking'][$this->mode]['newsletters_legend']; ?></h3>
	<table cellpadding="0" cellspacing="0" class="tl_listing">
		<colgroup>
			<col width="16" />
			<col />
		</colgroup>
		<tbody>
			<tr>
				<td class="tl_folder_tlist readed"></td>
				<td class="tl_folder_tlist url"><?php echo $GLOBALS['TL_LANG']['avisota_tracking'][$this->mode]['newsletter']; ?></td>
			</tr>
			<?php foreach ($this->newsletter_reads as $i=>$read): ?>
			<tr onmouseover="Theme.hoverRow(this, 1);" onmouseout="Theme.hoverRow(this, 0);">
				<td class="tl_file_list tl_right_nowrap readed"><?php if ($read['readed']): ?><img src="system/modules/Avisota/html/outbox_sended.png" alt="<?php echo specialchars($GLOBALS['TL_LANG']['avisota_tracking'][$this->mode]['readed']); ?>" width="16" height="16" /><?php else: ?><img src="system/modules/Avisota/html/blank.gif" alt="" width="16" height="16" /><?php endif; ?></td>
				<td class="tl_file_list newsletter"><a href="contao/main.php?do=avisota_newsletter&table=tl_avisota_newsletter&key=send&id=<?php echo $read['id']; ?>"><?php echo $read['subject']; ?></a></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
<?php endif; ?>

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
				<td class="tl_file_list tl_right_nowrap hits"><?php echo number_format($link['hits'], 0, ',', '.'); ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

<script>
var sends = false;
var reads = false;
var reacts = false;

$(window).addEvent('domready', function() {
	new Meio.Autocomplete(
		$('recipient'),
		'contao/main.php?do=avisota_tracking&newsletter=<?php echo $this->newsletter['id']; ?>&data=recipients',
		{
			filter: {
				type: 'contains',
				path: 'text'
			}
		}
	);
});
$(window).addEvent('load', function() {
	if (sends || reads || reacts) return;

	function drawChart() {
		if (!sends || !reads || !reacts) return;

		if (!sends.length && !reads.length && !reacts.length) {
			$('graph_overview').setAttribute('style', '');
			$('graph_overview')
				.addClass('empty_stats')
				.set('text', '<?php echo specialchars($GLOBALS['TL_LANG']['avisota_tracking']['empty_stats']); ?>');
			return;
		}

		var a = 0;
		var b = 0;
		var c = 0;
		while (a < sends.length || b < reads.length || c < reacts.length) {
			// send smaller read
			if (sends[a] && (!reads[b] || sends[a][0] < reads[b][0])) {
				// insert into reads
				reads.splice(b, 0, [sends[a][0], b>0 ? reads[b-1][1] : 0]);

				continue;
			}

			// read smaller react
			if (reads[b] && (!reacts[c] || reads[b][0] < reacts[c][0])) {
				// insert into reacts
				reacts.splice(c, 0, [reads[b][0], c>0 ? reacts[c-1][1] : 0]);

				continue;
			}

			// reacts smaller send
			if (reacts[c] && (!sends[a] || reacts[c][0] < sends[a][0])) {
				// insert into sends
				sends.splice(a, 0, [reacts[c][0], a>0 ? sends[a-1][1] : 0]);

				continue;
			}

			a ++;
			b ++;
			c ++;
		}

		$('graph_overview').setAttribute('style', '');

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
					var point = this.points[0];
					var series = point.series;
					var unit = series.unit && series.unit[0];
					var format = series.tooltipHeaderFormat;

					var s = '<b>' + Highcharts.dateFormat(format, this.x) + '</b>';
					for (var i=0; i<this.points.length; i++) {
						s += '<br/><strong style="color:' + this.points[i].series.color + '">' + this.points[i].series.name + '</strong>: ' + Highcharts.numberFormat(this.points[i].y, 0);
					}

					return s;
				}
			},
			series: [{
				name: '<?php echo specialchars($GLOBALS['TL_LANG']['avisota_tracking'][$this->mode]['sends']); ?>',
				data: sends
			}, {
				name: '<?php echo specialchars($GLOBALS['TL_LANG']['avisota_tracking'][$this->mode]['reads']); ?>',
				data: reads
			}, {
				name: '<?php echo specialchars($GLOBALS['TL_LANG']['avisota_tracking'][$this->mode]['reacts']); ?>',
				data: reacts
			}]
		});
	}

	new Request.JSON({
		url: 'contao/main.php?do=avisota_tracking&newsletter=<?php echo $this->newsletter['id']; ?>&recipient=<?php echo urlencode($this->recipient); ?>&data=sends',
		onComplete: function(json) {
			sends = json;
			drawChart()
		}
	}).get();

	new Request.JSON({
		url: 'contao/main.php?do=avisota_tracking&newsletter=<?php echo $this->newsletter['id']; ?>&recipient=<?php echo urlencode($this->recipient); ?>&data=reads',
		onComplete: function(json) {
			reads = json;
			drawChart()
		}
	}).get();

	new Request.JSON({
		url: 'contao/main.php?do=avisota_tracking&newsletter=<?php echo $this->newsletter['id']; ?>&recipient=<?php echo urlencode($this->recipient); ?>&data=reacts',
		onComplete: function(json) {
			reacts = json;
			drawChart()
		}
	}).get();
});
</script>

<br>
