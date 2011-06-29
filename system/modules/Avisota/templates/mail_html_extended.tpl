<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 3.2//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="Generator" content="Contao Open Source CMS">
<title><?php echo $this->title; ?></title>
<?php echo $this->head; ?>
</head>
<body>
<?php if (TL_MODE != 'FE'): ?><div id="onlinelink"><a href="{{newsletter::href}}"><?php echo $GLOBALS['TL_LANG']['tl_avisota_newsletter']['online'] ?></a></div><?php endif ?>
<div id="wrapper">
<table>
	<?php if ($this->header): ?>
	<thead>
		<tr>
			<td class="header"<?php if ($this->left && $this->right): ?> colspan="3"<?php elseif ($this->left || $this->right): ?> colspan="2"<?php endif; ?>>
				<?php echo $this->header; ?>
			</td>
		</tr>
	</thead>
	<?php endif; ?>
	<tbody>
		<tr>
			<?php if ($this->left): ?>
			<td class="left">
				<?php echo $this->left; ?>
			</td>
			<?php endif; ?>
			<td class="body">
				<?php echo $this->body; ?>
			</td>
			<?php if ($this->right): ?>
			<td class="right">
				<?php echo $this->right; ?>
			</td>
			<?php endif; ?>
		</tr>
	</tbody>
	<?php if ($this->footer): ?>
	<tfoot>
		<tr>
			<td class="footer"<?php if ($this->left && $this->right): ?> colspan="3"<?php elseif ($this->left || $this->right): ?> colspan="2"<?php endif; ?>>
				<?php echo $this->footer; ?>
			</td>
		</tr>
	</tfoot>
	<?php endif; ?>
</table>
</div>
<?php if (TL_MODE != 'FE'): ?><div id="unsubscribe">{{newsletter::unsubscribe::html}}</div><?php endif ?>
</body>
</html>