<div id="tl_buttons">
<a href="<?php echo $this->getReferer(divue) ?>" class="header_back" title="<?php echo specialchars($GLOBALS['TL_LANG']['MSC']['backBT']) ?>" accesskey="b"><?php echo $GLOBALS['TL_LANG']['MSC']['backBT'] ?></a>
</div>

<?php echo $this->getMessages(); ?>

<h2 class="sub_headline" id="headline"><?php echo $this->newsletter['subject'] ?></h2>

<table id="outbox">
	<tr>
		<td class="title">Ausstehend:</td>
		<td id="outstanding"><?php echo number_format($this->outbox['outstanding'], 0, ',', '.'); ?></td>
	</tr>
	<tr>
		<td class="title">Versendet:</td>
		<td id="sended"><?php echo number_format($this->outbox['recipients'] - $this->outbox['outstanding'] - $this->outbox['failed'], 0, ',', '.'); ?></td>
	</tr>
	<tr>
		<td class="title">Fehlgeschlagen:</td>
		<td id="failed"><?php echo number_format($this->outbox['failed'], 0, ',', '.'); ?></td>
	</tr>
	<tr>
		<td class="title">Dauer:</td>
		<td id="elapsed_time">-</td>
	</tr>
	<tr>
		<td class="title">voraussichtliche Restdauer:</td>
		<td id="due_time">-</td>
	</tr>
	<tr>
		<td colspan="2">
			<h2 class="sub_headline">Letzter Zyklus</h2>
		</td>
	</tr>
	<tr>
		<td class="title">Versendet:</td>
		<td id="last_sended">-</td>
	</tr>
	<tr>
		<td class="title">Dauer:</td>
		<td id="last_time">-</td>
	</tr>
	<tr>
		<td colspan="2">
			<h2 class="sub_headline">Aktueller Zyklus</h2>
		</td>
	</tr>
	<tr>
		<td class="title">voraussichtliche Restdauer:</td>
		<td id="remeaning_time">-</td>
	</tr>
</table>
