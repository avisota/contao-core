<div id="tl_buttons">
<a href="<?php echo $this->getReferer(true) ?>" class="header_back" title="<?php echo specialchars($GLOBALS['TL_LANG']['MSC']['backBT']) ?>" accesskey="b"><?php echo $GLOBALS['TL_LANG']['MSC']['backBT'] ?></a>
</div>

<?php echo $this->getMessages(); ?>

<h2 class="sub_headline"><?php echo $this->newsletter ?></h2>

<form action="contao/main.php" id="tl_avisota_newsletter_draft_preview" target="preview" class="tl_form" method="get">
<input name="do" value="avisota_newsletter_draft" type="hidden">
<input name="key" value="render" type="hidden">
<input name="id" value="<?php echo $this->id ?>" type="hidden">
<div class="tl_formbody_edit">

</div>
</form>
