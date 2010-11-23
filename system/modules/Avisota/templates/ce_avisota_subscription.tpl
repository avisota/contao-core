
<!-- indexer::stop -->
<div class="<?php echo $this->class; ?> block"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
<?php if ($this->headline): ?>

<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
<?php endif; ?>

<form action="<?php echo $this->formAction ?>" method="post" id="<?php echo $this->formId ?>">
<div class="formbody">

<p class="preamble"><?php echo $GLOBALS['TL_LANG']['avisota']['subscription']['preamble'] ?></p>

<?php if (count($this->messages)): foreach ($this->messages as $strMessage): ?>
<p class="message"><?php echo $strMessage ?></p>
<?php endforeach; endif ?>

<?php if ($this->lists): ?>
<div class="widget widget-checkbox">
<div class="checkbox_container">
	<label><?php echo $GLOBALS['TL_LANG']['avisota']['subscription']['lists'] ?></label>
	<ul>
	<?php foreach ($this->lists as $list): ?>
		<li><input type="checkbox" name="list" value="<?php echo specialchars($list['alias']) ?>" /> <?php echo $list['title'] ?></li>
	<?php endforeach; ?>
	</ul>
</div>
</div>
<?php endif ?>

<div class="widget widget-text">
	<label for="ctrl_email"><?php echo $GLOBALS['TL_LANG']['avisota']['subscription']['email'] ?></label>
	<input type="text" class="text" name="email" id="ctrl_email" value="<?php echo $this->Input->post('email') ?>" />
</div>

<div class="submit_container">
	<input type="submit" name="subscribe" value="<?php echo specialchars($GLOBALS['TL_LANG']['avisota']['subscribe']['submit']); ?>" />
	<input type="submit" name="unsubscribe" value="<?php echo specialchars($GLOBALS['TL_LANG']['avisota']['unsubscribe']['submit']); ?>" />
</div>

</div>
</form>

</div>
<!-- indexer::continue -->
