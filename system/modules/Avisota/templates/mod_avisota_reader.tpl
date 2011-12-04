<div class="<?php echo $this->class; ?> block"<?php echo $this->cssID; ?><?php if ($this->style): ?>
     style="<?php echo $this->style; ?>"<?php endif; ?>>
	<?php if ($this->newsletter): ?>
	<h1 class="subject"><?php echo $this->newsletter['subject']; ?></h1>
	<div class="newsletter"><?php echo $this->html; ?></div>
	<?php else: ?>
	<p class="notFound"><?php echo $GLOBALS['TL_LANG']['avisota']['reader']['notFound']; ?></p>
	<?php endif; ?>
</div>
