<?php if ($this->empty): ?>
<p class="tl_gerror"><?php echo $GLOBALS['TL_LANG']['avisota_tracking']['empty_stats']; ?></p>
<?php else: ?>
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
<option value="<?php echo $id; ?>"<?php if ($this->newsletter['id'] == $id): ?> selected="selected"<?php endif; ?>><?php echo $newsletter; ?></option>
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

<script type="text/javascript" src="system/modules/Avisota/html/Number.js"></script>
<script type="text/javascript" src="system/modules/Avisota/html/functions.js"></script>
<script type="text/javascript" src="system/modules/Avisota/Meio.Autocomplete/<?php if (VERSION == 2.9): ?>1.0<?php else: ?>2.0<?php endif; ?>/Meio.Autocomplete.js"></script>
<?php
if ($this->chart == 'highstock'):
	include($this->getTemplate('be_avisota_tracking_highstock'));
else:
	include($this->getTemplate('be_avisota_tracking_jqplot'));
endif;
?>

<div id="stats">
	<h3><?php echo $GLOBALS['TL_LANG']['avisota_tracking'][$this->mode]['stats_legend']; ?></h3>
	<div id="chart" style="position: relative; height: 420px; background: url(system/modules/Avisota/html/loading.gif) no-repeat center center;"></div>

	<table cellpadding="0" cellspacing="0" class="tl_listing">
		<colgroup>
			<col />
			<col />
			<col width="120" />
			<col width="120" />
		</colgroup>
		<tbody>
			<tr>
				<td class="tl_folder_tlist">&nbsp;</td>
				<td class="tl_folder_tlist tl_right_nowrap"><?php echo $GLOBALS['TL_LANG']['avisota_tracking']['col_sum']; ?></td>
				<td class="tl_folder_tlist tl_right_nowrap"><?php echo $GLOBALS['TL_LANG']['avisota_tracking']['col_percent']; ?></td>
				<td class="tl_folder_tlist tl_right_nowrap"><?php echo $GLOBALS['TL_LANG']['avisota_tracking']['col_percent2']; ?></td>
			</tr>
			<tr onmouseover="Theme.hoverRow(this, 1);" onmouseout="Theme.hoverRow(this, 0);">
				<td class="tl_file_list"><?php echo $GLOBALS['TL_LANG']['avisota_tracking'][$this->mode]['sends']; ?></td>
				<td class="tl_file_list tl_right_nowrap" id="col_sends">0</td>
				<td class="tl_file_list tl_right_nowrap" id="col_sends_percent"></td>
				<td class="tl_file_list tl_right_nowrap" id="col_sends_percent2"></td>
			</tr>
			<tr onmouseover="Theme.hoverRow(this, 1);" onmouseout="Theme.hoverRow(this, 0);">
				<td class="tl_file_list"><?php echo $GLOBALS['TL_LANG']['avisota_tracking'][$this->mode]['reads']; ?></td>
				<td class="tl_file_list tl_right_nowrap" id="col_reads"></td>
				<td class="tl_file_list tl_right_nowrap" id="col_reads_percent"></td>
				<td class="tl_file_list tl_right_nowrap" id="col_reads_percent2"></td>
			</tr>
			<tr onmouseover="Theme.hoverRow(this, 1);" onmouseout="Theme.hoverRow(this, 0);">
				<td class="tl_file_list"><?php echo $GLOBALS['TL_LANG']['avisota_tracking'][$this->mode]['reacts']; ?></td>
				<td class="tl_file_list tl_right_nowrap" id="col_reacts"></td>
				<td class="tl_file_list tl_right_nowrap" id="col_reacts_percent"></td>
				<td class="tl_file_list tl_right_nowrap" id="col_reacts_percent2"></td>
			</tr>
		</tbody>
	</table>
</div>
<br/><br/>

<div id="links">
	<h3><?php echo $GLOBALS['TL_LANG']['avisota_tracking'][$this->mode]['links_legend']; ?></h3>
	<div id="chart_links" style="position: relative; height: 420px; background: url(system/modules/Avisota/html/loading.gif) no-repeat center center;"></div>
	<table cellpadding="0" cellspacing="0" class="tl_listing">
		<colgroup>
			<col />
			<col />
			<col width="45" />
		</colgroup>
		<tbody>
			<tr>
				<td class="tl_folder_tlist"><?php echo $GLOBALS['TL_LANG']['avisota_tracking'][$this->mode]['url']; ?></td>
				<td class="tl_folder_tlist tl_right_nowrap"><?php echo $GLOBALS['TL_LANG']['avisota_tracking'][$this->mode]['hits']; ?></td>
				<td class="tl_folder_tlist"></td>
			</tr>
			<?php foreach ($this->links as $i=>$link): ?>
			<tr onmouseover="Theme.hoverRow(this, 1);" onmouseout="Theme.hoverRow(this, 0);">
				<td class="tl_file_list"><a href="<?php echo $link['url']; ?>" onclick="window.open(this.href); return false;"><?php echo $link['url']; ?></a></td>
				<td class="tl_file_list tl_right_nowrap"><?php echo number_format($link['hits'], 0, ',', '.'); ?></td>
				<td class="tl_file_list tl_right_nowrap"><?php echo $link['percent']; ?>&nbsp;%</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

<?php if (is_array($this->newsletter_reads)): ?>
<br/><br/>
<div id="newsletters">
	<h3><?php echo $GLOBALS['TL_LANG']['avisota_tracking'][$this->mode]['newsletters_legend']; ?></h3>
	<table cellpadding="0" cellspacing="0" class="tl_listing">
		<colgroup>
			<col width="16" />
			<col />
		</colgroup>
		<tbody>
			<tr>
				<td class="tl_folder_tlist">&nbsp;</td>
				<td class="tl_folder_tlist"><?php echo $GLOBALS['TL_LANG']['avisota_tracking'][$this->mode]['newsletter']; ?></td>
			</tr>
			<?php foreach ($this->newsletter_reads as $i=>$read): ?>
			<tr onmouseover="Theme.hoverRow(this, 1);" onmouseout="Theme.hoverRow(this, 0);">
				<td class="tl_file_list"><?php if ($read['readed']): ?><img src="system/modules/Avisota/html/outbox_sended.png" alt="<?php echo specialchars($GLOBALS['TL_LANG']['avisota_tracking'][$this->mode]['readed']); ?>" width="16" height="16" /><?php else: ?><img src="system/modules/Avisota/html/blank.gif" alt="" width="16" height="16" /><?php endif; ?></td>
				<td class="tl_file_list"><a href="contao/main.php?do=avisota_newsletter&table=tl_avisota_newsletter&key=send&id=<?php echo $read['id']; ?>"><?php echo $read['subject']; ?></a></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
<?php endif; ?>

<script>
<?php if ($this->chart == 'highstock' && $this->mode == 'recipient'): ?>var flags = false;
<?php endif; ?>
var sends = false;
var reads = false;
var reacts = false;
var links = false;

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

	<?php if ($this->chart == 'highstock' && $this->mode == 'recipient'): ?>
	new Request.JSON({
		url: 'contao/main.php?do=avisota_tracking&newsletter=<?php echo $this->newsletter['id']; ?>&recipient=<?php echo urlencode($this->recipient); ?>&data=flags',
		onComplete: function(json) {
			flags = json;
			drawChart();
		}
	}).get();

	<?php endif; ?>
	new Request.JSON({
		url: 'contao/main.php?do=avisota_tracking&newsletter=<?php echo $this->newsletter['id']; ?>&recipient=<?php echo urlencode($this->recipient); ?>&data=sends',
		onComplete: function(json) {
			sends = json;
			drawChart();
		}
	}).get();

	new Request.JSON({
		url: 'contao/main.php?do=avisota_tracking&newsletter=<?php echo $this->newsletter['id']; ?>&recipient=<?php echo urlencode($this->recipient); ?>&data=reads',
		onComplete: function(json) {
			reads = json;
			drawChart();
		}
	}).get();

	new Request.JSON({
		url: 'contao/main.php?do=avisota_tracking&newsletter=<?php echo $this->newsletter['id']; ?>&recipient=<?php echo urlencode($this->recipient); ?>&data=reacts',
		onComplete: function(json) {
			reacts = json;
			drawChart();
		}
	}).get();

	new Request.JSON({
		url: 'contao/main.php?do=avisota_tracking&newsletter=<?php echo $this->newsletter['id']; ?>&recipient=<?php echo urlencode($this->recipient); ?>&data=links',
		onComplete: function(json) {
			links = json;
			drawChart();
		}
	}).get();
});
</script>

<br>
<?php endif; ?>
