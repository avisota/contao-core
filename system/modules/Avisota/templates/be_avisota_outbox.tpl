<div id="tl_buttons">
<a href="<?php echo $this->getReferer(true) ?>" class="header_back" title="<?php echo specialchars($GLOBALS['TL_LANG']['MSC']['backBT']) ?>" accesskey="b"><?php echo $GLOBALS['TL_LANG']['MSC']['backBT'] ?></a>
</div>

<?php echo $this->getMessages(); ?>

<?php if ($this->outbox): foreach (array('open', 'incomplete', 'complete') as $strGroup): if (count($this->outbox[$strGroup]) > 0): ?>
<h2 class="sub_headline"><?php echo $GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox'][$strGroup] ?></h2>

<div class="tl_formbody_edit">
<table class="prev_header outbox" summary="" cellpadding="0" cellspacing="0" width="100%">
  <colgroup>
    <col width="120px" />
    <col />
    <col width="1%" />
    <col width="1%" />
    <?php if ($this->display_failed): ?><col width="1%" /><?php endif; ?>
    <col width="76px" />
  </colgroup>
  <thead>
  <tr class="head_0">
    <th class="col_1" rowspan="2">&nbsp;<?php echo $GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['date'] ?>&nbsp;</th>
    <th class="col_1" rowspan="2">&nbsp;<?php echo $GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['newsletter'] ?>&nbsp;</th>
    <th class="col_2" colspan="<?php if ($this->display_failed): ?>3<?php else: ?>2<?php endif; ?>">&nbsp;<?php echo $GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['count'] ?>&nbsp;</th>
    <th class="col_5" rowspan="2"></th>
  </tr>
  <tr class="head_1">
    <th class="col_2"><?php echo $GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['outstanding'] ?></th>
    <th class="col_3"><?php echo $GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['total'] ?></th>
    <?php if ($this->display_failed): ?><th class="col_4"><?php echo $GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['failed'] ?></th><?php endif; ?>
  </tr>
  </thead>
  <tbody>
  <?php foreach ($this->outbox[$strGroup] as $k=>$outbox): ?>
  <tr class="row_<?php echo $k ?><?php if ($outbox['token'] == $this->Input->get('highlight')): ?> row_highlight<?php endif ?>">
    <td class="col_0"><?php echo $this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $outbox['date']) ?></td>
    <td class="col_1"><?php echo $outbox['newsletter'] ?></td>
    <td class="col_2" align="center"><span class="outstanding"><?php echo $outbox['outstanding'] ?></span></td>
    <td class="col_3" align="center"><span class="total"><?php echo $outbox['recipients']; ?></span></td>
    <?php if ($this->display_failed): ?><td class="col_4" align="center"><?php if ($outbox['failed'] > 0): ?><span class="failed"><?php echo $outbox['failed']; ?></span><?php endif; ?></td><?php endif; ?>
    <td class="col_5"><a href="<?php echo $GLOBALS['TL_CONFIG']['websitePath'] ?>/contao/main.php?do=avisota_outbox&amp;act=details&amp;id=<?php echo $outbox['id'] ?>&amp;token=<?php echo $outbox['token'] ?>" title="<?php echo specialchars($GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['details'][1]) ?>"><img src="system/modules/Avisota/html/outbox_details.png" alt="<?php echo specialchars($GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['details'][0]) ?>" /></a>&nbsp;<a href="<?php echo $GLOBALS['TL_CONFIG']['websitePath'] ?>/contao/main.php?do=avisota_outbox&amp;act=remove&amp;id=<?php echo $outbox['id'] ?>&amp;token=<?php echo $outbox['token'] ?>" title="<?php echo specialchars($GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['remove'][1]) ?>" onclick="return confirm('<?php echo specialchars($GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['remove'][2]) ?>');"><img src="system/modules/Avisota/html/outbox_remove.png" alt="<?php echo specialchars($GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['remove'][0]) ?>" /></a><?php if ($this->beSend && $outbox['outstanding'] > 0): ?>&nbsp;<a href="<?php echo $GLOBALS['TL_CONFIG']['websitePath'] ?>/contao/main.php?do=avisota_outbox&amp;id=<?php echo $outbox['id'] ?>&amp;token=<?php echo $outbox['token'] ?>" title="<?php echo specialchars($GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['send'][1]) ?>"><img src="system/modules/Avisota/html/send_go.png" alt="<?php echo specialchars($GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['send'][0]) ?>" /></a><?php endif ?></td>
  </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<br/>
</div>
<?php endif; endforeach; else: ?>
<div class="tl_formbody_edit">
<p><?php echo $GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['empty']; ?></p>
</div>
<?php endif; ?>
