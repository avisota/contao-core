<div id="tl_buttons">
<a href="<?php echo $this->getReferer(true) ?>" class="header_back" title="<?php echo specialchars($GLOBALS['TL_LANG']['MSC']['backBT']) ?>" accesskey="b"><?php echo $GLOBALS['TL_LANG']['MSC']['backBT'] ?></a>
</div>

<?php echo $this->getMessages(); ?>

<h2 class="sub_headline"><?php echo $GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['headline'] ?></h2>

<div class="tl_formbody_edit">
<?php if (count($this->outbox)): ?>
<table class="prev_header" summary="" cellpadding="0" cellspacing="0">
  <thead>
  <tr class="head_0">
    <th class="col_0">&nbsp;<?php echo $GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['newsletter'] ?>&nbsp;</th>
    <th class="col_1">&nbsp;<?php echo $GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['count'] ?>&nbsp;</th>
    <?php if ($this->beSend): ?><th class="col_2"></th><?php endif ?>
  </tr>
  </thead>
  <tbody>
  <?php foreach ($this->outbox as $k=>$outbox): ?>
  <tr class="row_<?php echo $k ?>">
    <td class="col_0"><?php echo $outbox['newsletter'] ?></td>
    <td class="col_1" align="center"><?php echo $outbox['recipients'] ?></td>
    <?php if ($this->beSend): ?><td class="col_2"><a href="<?php echo $GLOBALS['TL_CONFIG']['websitePath'] ?>/contao/main.php?do=avisota_outbox&amp;id=<?php echo $outbox['id'] ?>&amp;token=<?php echo $outbox['token'] ?>" title="<?php echo specialchars($GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['send'][1]) ?>"><img src="system/modules/Avisota/html/send.png" alt="<?php echo specialchars($GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['send'][0]) ?>" /></a></td><?php endif ?>
  </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<br/>
<?php else: ?>
  <p><?php echo $GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['empty'] ?></p>
<?php endif ?>
</div>
