<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 3.2//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->charset; ?>">
<meta name="Generator" content="Contao Open Source CMS">
<title><?php echo $this->title; ?></title>
<?php echo $this->head; ?>
</head>
<body>
<?php if (TL_MODE != 'FE'): ?><div id="onlinelink"><a href="{{newsletter::href}}"><?php echo $GLOBALS['TL_LANG']['tl_avisota_newsletter']['online'] ?></a></div><?php endif ?>
<div id="wrapper">
  <div id="main">
    <?php echo $this->body; ?>
  </div>
</div>
<?php if (TL_MODE != 'FE'): ?><div id="unsubscribe">{{newsletter::unsubscribe::html}}</div><?php endif ?>
</body>
</html>