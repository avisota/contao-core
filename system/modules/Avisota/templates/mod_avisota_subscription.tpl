
<!-- indexer::stop -->
<div class="<?php echo $this->class; ?> block"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
<?php if ($this->headline): ?>

<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
<?php endif; ?>

<form action="<?php echo $this->formAction ?>" method="post" id="<?php echo $this->formId ?>">
<div class="formbody">
<input type="hidden" name="FORM_SUBMIT" value="<?php echo $this->formId; ?>" />

<p class="preamble"><?php echo $GLOBALS['TL_LANG']['avisota']['subscription']['preamble'] ?></p>

<?php
foreach ($this->messages as $strClass => $arrMessages):
foreach ($arrMessages as $strMessage):
?>
<p class="message <?php echo $strClass; ?>"><?php echo $strMessage ?></p>
<?php
endforeach;
endforeach;
?>

<?php if (!$this->hideForm): ?>
<?php if (!$this->tableless): ?>
<table cellspacing="0" cellpadding="0" summary="">
	<tbody>
		<?php echo $this->fields; ?>
		<tr class="<?php echo $this->rowLast; ?> row_last">
			<td class="col_0 col_1 col_submit col_first col_last" colspan="2">
				<div class="submit_container">
					<input type="submit" name="subscribe" value="<?php echo specialchars($GLOBALS['TL_LANG']['avisota']['subscribe']['submit']); ?>" />
					<input type="submit" name="unsubscribe" value="<?php echo specialchars($GLOBALS['TL_LANG']['avisota']['unsubscribe']['submit']); ?>" />
				</div>
			</td>
		</tr>
	</tbody>
</table>
<?php else: ?>
<div class="fields">
	<?php echo $this->fields; ?>
</div>
<div class="submit_container">
	<input type="submit" name="subscribe" value="<?php echo specialchars($GLOBALS['TL_LANG']['avisota']['subscribe']['submit']); ?>" />
	<input type="submit" name="unsubscribe" value="<?php echo specialchars($GLOBALS['TL_LANG']['avisota']['unsubscribe']['submit']); ?>" />
</div>
<?php endif; ?>
<?php endif; ?>

</div>
</form>

</div>
<!-- indexer::continue -->
