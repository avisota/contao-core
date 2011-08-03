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

<!--[if IE]><script language="javascript" type="text/javascript" src="excanvas.js"></script><![endif]-->
<script type="text/javascript" src="system/modules/Avisota/jqplot/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="system/modules/Avisota/jqplot/jquery.jqplot.min.js"></script>
<script type="text/javascript">jQuery.noConflict();</script>
<script type="text/javascript" src="system/modules/Avisota/jqplot/plugins/jqplot.highlighter.min.js"></script>
<script type="text/javascript" src="system/modules/Avisota/jqplot/plugins/jqplot.cursor.min.js"></script>
<script type="text/javascript" src="system/modules/Avisota/jqplot/plugins/jqplot.dateAxisRenderer.min.js"></script>
<script type="text/javascript" src="system/modules/Avisota/jqplot/plugins/jqplot.canvasTextRenderer.min.js"></script>
<script type="text/javascript" src="system/modules/Avisota/jqplot/plugins/jqplot.canvasAxisTickRenderer.min.js"></script>
<script type="text/javascript" src="system/modules/Avisota/jqplot/plugins/jqplot.categoryAxisRenderer.min.js"></script>
<script type="text/javascript" src="system/modules/Avisota/jqplot/plugins/jqplot.barRenderer.min.js"></script>
<script type="text/javascript" src="system/modules/Avisota/jqplot/plugins/jqplot.pieRenderer.min.js"></script>
<script type="text/javascript" src="system/modules/Avisota/jqplot/plugins/jqplot.donutRenderer.min.js"></script>
<script type="text/javascript" src="system/modules/Avisota/jqplot/plugins/jqplot.pointLabels.min.js"></script>

<div id="graphs">
	<h3><?php echo $GLOBALS['TL_LANG']['avisota_tracking'][$this->mode]['graph_overview_legend']; ?></h3>
	<div id="graph_overview"></div>
	<h3><?php echo $GLOBALS['TL_LANG']['avisota_tracking'][$this->mode]['graph_timeline_legend']; ?></h3>
	<div id="graph_timeline"></div>
</div>

<div id="links">
	<h3><?php echo $GLOBALS['TL_LANG']['avisota_tracking'][$this->mode]['graph_links_legend']; ?></h3>
	<table cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<th class="url"><?php echo $GLOBALS['TL_LANG']['avisota_tracking'][$this->mode]['url']; ?></th>
				<th class="hits"><?php echo $GLOBALS['TL_LANG']['avisota_tracking'][$this->mode]['hits']; ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($this->links as $i=>$link): ?>
			<tr>
				<td class="url"><?php echo $link['url']; ?></td>
				<td class="hits"><?php echo $link['hits']; ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

<script>
jQuery(document).ready(function() {
	/* overview graph */
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
	/* links graph */
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
</script>