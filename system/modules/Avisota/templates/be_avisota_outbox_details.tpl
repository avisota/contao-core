<div id="tl_buttons">
<a href="<?php echo $this->getReferer(true) ?>" class="header_back" title="<?php echo specialchars($GLOBALS['TL_LANG']['MSC']['backBT']) ?>" accesskey="b"><?php echo $GLOBALS['TL_LANG']['MSC']['backBT'] ?></a>
</div>

<?php echo $this->getMessages(); ?>

<h2 class="sub_headline"><?php echo $this->newsletter ?></h2>

<div class="tl_formbody_edit">
<table class="prev_header outbox" summary="" cellpadding="0" cellspacing="0" width="100%">
  <colgroup>
    <col width="16px" />
    <col width="140px" />
    <col />
  </colgroup>
  <thead>
  <tr class="head_0">
    <th class="col_0">&nbsp;</th>
    <th class="col_1">&nbsp;<?php echo $GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['sended_on'] ?>&nbsp;</th>
    <th class="col_2">&nbsp;<?php echo $GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['recipient'] ?>&nbsp;</th>
    <th class="col_3">&nbsp;<?php echo $GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['source'] ?>&nbsp;</th>
  </tr>
  </thead>
  <tbody>
  <?php foreach ($this->recipients as $k=>$r): ?>
  <tr class="row_<?php echo $k ?>">
    <td class="col_0"><img src="system/modules/Avisota/html/outbox_<?php echo $r['send'] > 0 ? ($r['failed'] ? 'failed' : 'sended') : 'outstanding' ?>.png" alt="<?php echo specialchars($GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox'][($r['send'] > 0 ? ($r['failed'] ? 'failed' : 'sended') : 'outstanding')]); ?>" title="<?php echo specialchars($GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox'][$r['send'] > 0 ? ($r['failed'] ? 'failed' : 'sended') : 'outstanding']); ?>" /></td>
    <td class="col_1"><?php if ($r['send'] > 0): echo $this->parseDate($GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['dateimsFormat'], $r['send']); endif; ?></td>
    <td class="col_2"><?php echo $r['email'] ?></td>
    <td class="col_3"><?php echo $r['source'] ?></td>
  </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<br/>
</div>
