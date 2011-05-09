<div id="tl_buttons">
<a href="<?php echo $this->getReferer(true) ?>" class="header_back" title="<?php echo specialchars($GLOBALS['TL_LANG']['MSC']['backBT']) ?>" accesskey="b"><?php echo $GLOBALS['TL_LANG']['MSC']['backBT'] ?></a>
&nbsp; :: &nbsp;
<a href="<?php echo TL_PATH ?>/contao/main.php?do=avisota_newsletter&amp;table=tl_avisota_newsletter&amp;act=edit&amp;id=<?php echo $this->id ?>" accesskey="e" title="<?php echo specialchars(sprintf($GLOBALS['TL_LANG']['tl_avisota_newsletter']['editheader'][1], $this->id)) ?>" style="padding:2px 0 3px 18px; background:url('<?php echo TL_PATH ?>/system/themes/<?php echo $this->User->backendTheme ? $this->User->backendTheme : 'default' ?>/images/header.gif') no-repeat left center;"><?php echo $GLOBALS['TL_LANG']['tl_avisota_newsletter']['editheader'][0] ?></a>
&nbsp; :: &nbsp;
<a href="<?php echo TL_PATH ?>/contao/main.php?do=avisota_newsletter&amp;table=tl_avisota_newsletter_content&amp;id=<?php echo $this->id ?>" accesskey="b" title="<?php echo specialchars(sprintf($GLOBALS['TL_LANG']['tl_avisota_newsletter']['edit'][1], $this->id)) ?>" style="padding:2px 0 3px 16px; background:url('<?php echo TL_PATH ?>/system/themes/<?php echo $this->User->backendTheme ? $this->User->backendTheme : 'default' ?>/images/edit.gif') no-repeat left center;"><?php echo $GLOBALS['TL_LANG']['tl_avisota_newsletter']['edit'][0] ?></a>
</div>

<?php echo $this->getMessages(); ?>

<h2 class="sub_headline"><?php echo $GLOBALS['TL_LANG']['tl_avisota_newsletter']['headline'] ?></h2>

<div class="tl_listing_container">
<div class="tl_formbody_edit tl_header">
<table class="tl_header_table" summary="" cellpadding="0" cellspacing="0">
  <tbody><tr class="row_0">
    <td class="col_0"><span class="tl_label"><?php echo $GLOBALS['TL_LANG']['tl_avisota_newsletter']['subject'][0] ?>:</span></td>
    <td class="col_1"><?php echo $this->newsletter->subject ?></td>
  </tr>
  <tr class="row_2">
    <td class="col_0"><span class="tl_label"><?php echo $GLOBALS['TL_LANG']['tl_avisota_newsletter']['template_html'][0] ?>:</span>&nbsp;</td>
    <td class="col_1"><?php echo $this->newsletter->getCont ?></td>
  </tr>
  <tr class="row_3">
    <td class="col_0"><span class="tl_label"><?php echo $GLOBALS['TL_LANG']['tl_avisota_newsletter']['template_plain'][0] ?>:</span>&nbsp;</td>
    <td class="col_1"><?php echo $this->template_plain ? $this->template_plain : '-' ?></td>
  </tr>
</tbody></table>
</div>
</div>

<form action="contao/main.php" id="tl_avisota_newsletter_preview" target="preview" class="tl_form" method="get">
<input name="do" value="avisota_newsletter" type="hidden" />
<input name="key" value="render" type="hidden" />
<input name="id" value="<?php echo $this->id ?>" type="hidden" />
<div class="tl_formbody_edit preview">

<div class="tl_tbox block">
<div class="w50">
  <h3><label for="ctrl_preview_mode"><?php echo $GLOBALS['TL_LANG']['tl_avisota_newsletter']['preview_mode'][0] ?></label></h3>
  <select name="mode" id="ctrl_preview_mode" class="tl_select" onfocus="Backend.getScrollOffset();" onchange="this.form.submit();">
    <option value="<?php echo NL_HTML ?>"<?php if ($this->Session->get('tl_avisota_preview_mode') == NL_HTML): ?> selected="selected"<?php endif ?>><?php echo $GLOBALS['TL_LANG']['tl_avisota_newsletter']['preview_mode'][2] ?></option>
    <option value="<?php echo NL_PLAIN ?>"<?php if ($this->Session->get('tl_avisota_preview_mode') == NL_PLAIN): ?> selected="selected"<?php endif ?>><?php echo $GLOBALS['TL_LANG']['tl_avisota_newsletter']['preview_mode'][3] ?></option>
  </select>
  <p class="tl_help tl_tip"><?php echo $GLOBALS['TL_LANG']['tl_avisota_newsletter']['preview_mode'][1] ?></p>
</div>

<div class="w50">
  <h3><label for="ctrl_preview_personalized"><?php echo $GLOBALS['TL_LANG']['tl_avisota_newsletter']['preview_personalized'][0] ?></label></h3>
  <select name="personalized" id="ctrl_preview_personalized" class="tl_select" onfocus="Backend.getScrollOffset();" onchange="this.form.submit();">
    <option value="anonymous"<?php if ($this->Session->get('tl_avisota_preview_personalized') == 'anonymous'): ?> selected="selected"<?php endif ?>><?php echo $GLOBALS['TL_LANG']['tl_avisota_newsletter']['preview_personalized'][3] ?></option>
    <option value="private"<?php if ($this->Session->get('tl_avisota_preview_personalized') == 'private'): ?> selected="selected"<?php endif ?>><?php echo $GLOBALS['TL_LANG']['tl_avisota_newsletter']['preview_personalized'][4] ?></option>
  </select>
  <p class="tl_help tl_tip"><?php echo $GLOBALS['TL_LANG']['tl_avisota_newsletter']['preview_personalized'][1] ?></p>
</div>

<?php if (!$this->template_html): ?>
<div class="w50">
  <h3><label for="ctrl_template_html"><?php echo $GLOBALS['TL_LANG']['tl_avisota_newsletter']['template_html'][0] ?></label></h3>
  <select name="template_html" id="ctrl_template_html" class="tl_select" onfocus="Backend.getScrollOffset();" onchange="this.form.submit();">
    <?php foreach ($this->html_templates as $strTemplate): ?>
    <option value="<?php echo $strTemplate ?>"<?php if ($this->Session->get('tl_avisota_preview_template_html') == $strTemplate): ?> selected="selected"<?php endif ?>><?php echo $strTemplate; ?></option>
    <?php endforeach; ?>
  </select>
  <p class="tl_help tl_tip"><?php echo $GLOBALS['TL_LANG']['tl_avisota_newsletter']['template_html'][1] ?></p>
</div>
<?php endif; ?>

<?php if (!$this->template_plain): ?>
<div class="w50">
  <h3><label for="ctrl_template_plain"><?php echo $GLOBALS['TL_LANG']['tl_avisota_newsletter']['template_plain'][0] ?></label></h3>
  <select name="template_plain" id="ctrl_template_plain" class="tl_select" onfocus="Backend.getScrollOffset();" onchange="this.form.submit();">
    <?php foreach ($this->plain_templates as $strTemplate): ?>
    <option value="<?php echo $strTemplate ?>"<?php if ($this->Session->get('tl_avisota_preview_template_plain') == $strTemplate): ?> selected="selected"<?php endif ?>><?php echo $strTemplate; ?></option>
    <?php endforeach; ?>
  </select>
  <p class="tl_help tl_tip"><?php echo $GLOBALS['TL_LANG']['tl_avisota_newsletter']['template_plain'][1] ?></p>
</div>
<?php endif; ?>
</div>

<iframe class="tl_avisota_newsletter_preview" id="preview" name="preview" scrolling="auto" width="100%" height="600"
	src="contao/main.php?do=avisota_newsletter&amp;key=render&amp;id=<?php echo $this->id ?>"></iframe>
</div>

<noscript>
<div class="tl_formbody_submit">

<div class="tl_submit_container">
<input class="tl_submit" accesskey="p" value="<?php echo $GLOBALS['TL_LANG']['tl_avisota_newsletter']['preview'] ?>" type="submit" />
</div>

</div>
</noscript>
</form>
