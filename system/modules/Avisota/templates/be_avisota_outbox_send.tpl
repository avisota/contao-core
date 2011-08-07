<div id="tl_buttons">
&nbsp;
</div>

<?php echo $this->getMessages(); ?>

<h2 class="sub_headline" id="headline"><?php echo $this->newsletter['subject'] ?></h2>

<div id="outbox">
	<table id="outbox_details" class="tl_listing" cellpadding="0" cellspacing="0">
		<colgroup>
			<col width="20%" />
			<col width="20%" />
			<col width="20%" />
			<col width="20%" />
			<col width="20%" />
		</colgroup>
		<thead>
			<tr>
				<th class="tl_folder_tlist">Ausstehend:</th>
				<th class="tl_folder_tlist">Versendet:</th>
				<th class="tl_folder_tlist">Fehlgeschlagen:</th>
				<th class="tl_folder_tlist">Dauer:</th>
				<th class="tl_folder_tlist">Restdauer:</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="tl_file_list" id="outstanding"><?php echo number_format($this->outbox['outstanding'], 0, ',', '.'); ?></td>
				<td class="tl_file_list" id="sended"><?php echo number_format($this->outbox['recipients'] - $this->outbox['outstanding'] - $this->outbox['failed'], 0, ',', '.'); ?></td>
				<td class="tl_file_list" id="failed"><?php echo number_format($this->outbox['failed'], 0, ',', '.'); ?></td>
				<td class="tl_file_list" id="elapsed_time">-</td>
				<td class="tl_file_list" id="due_time">-</td>
			</tr>
		</tbody>
	</table>

	<h2 class="sub_headline">Aktivität</h2>
	<table id="outbox_log" class="tl_listing" cellpadding="0" cellspacing="0">
		<colgroup>
			<col width="33%" />
			<col width="33%" />
			<col width="33%" />
			<col width="28px" />
		</colgroup>
		<thead>
			<tr>
				<th class="tl_folder_tlist">Versendet</th>
				<th class="tl_folder_tlist">Fehlgeschlagen</th>
				<th class="tl_folder_tlist">Dauer</th>
				<th class="tl_folder_tlist"></th>
			</tr>
		</thead>
		<tbody>
			<tr onmouseover="Theme.hoverRow(this, 1);" onmouseout="Theme.hoverRow(this, 0);">
				<td class="tl_file_list sended"></td>
				<td class="tl_file_list failed"></td>
				<td class="tl_file_list time"></td>
				<td class="tl_file_list indicator"><img src="system/modules/Avisota/html/loading.gif" alt="" width="16" height="16" /></td>
			</tr>
		</tbody>
	</table>
</div>

<script type="text/javascript" src="system/modules/Avisota/html/outbox.js"></script>
<script type="text/javascript">
window.addEvent('load', function() {
	new Outbox(<?php echo json_encode($this->outbox); ?>,
		<?php echo json_encode($this->newsletter); ?>,
		<?php echo $this->cycleTimeout; ?>,
		<?php echo $this->sendTimeout; ?>,
		<?php echo $this->expectedTime; ?>);
});
</script>
