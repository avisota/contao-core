<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 3.2//EN">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->charset; ?>">
	<meta name="Generator" content="Contao Open Source CMS">
	<title><?php echo $this->title; ?></title>
	<?php echo $this->head; ?>
</head>
<body>
<table class="before" align="center" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td>
			<?php if (TL_MODE != 'FE'): ?><div id="onlinelink"><a href="{{newsletter::href}}"><?php echo $GLOBALS['TL_LANG']['tl_avisota_newsletter']['online'] ?></a></div><?php endif ?>
		</td>
	</tr>
</table>
<table class="wrapper" align="center" cellpadding="0" cellspacing="0" border="0">
	<?php if ($this->header): ?>
	<thead>
		<tr>
			<td class="header"<?php if ($this->left && $this->right): ?> colspan="3"<?php elseif ($this->left || $this->right): ?> colspan="2"<?php endif; ?> >
				<table cellspacing="0" cellpadding="0" border="0">
					<tbody>
						<?php echo $this->header; ?>
					</tbody>
				</table>
			</td>
		</tr>
	</table>
	<div id="wrapper">
	<table cellpadding="0" cellspacing="0" border="0">
		<?php if ($this->header): ?>
		<thead>
			<tr>
				<td class="header"<?php if ($this->left && $this->right): ?> colspan="3"<?php elseif ($this->left || $this->right): ?> colspan="2"<?php endif; ?> >
					<table cellspacing="0" cellpadding="0" border="0" width="100%">
						<tbody>
							<?php echo $this->header; ?>
						</tbody>
					</table>
				</td>
			</tr>
		</thead>
		<?php endif; ?>
		<tbody>
			<tr>
				<?php if ($this->left): ?>
				<td class="left">
					<table cellspacing="0" cellpadding="0" border="0" width="100%">
						<tbody>
							<?php echo $this->left; ?>
						</tbody>
					</table>
				</td>
				<?php endif; ?>
				<td class="body">
					<table cellspacing="0" cellpadding="0" border="0" width="100%">
						<tbody>
							<?php echo $this->body; ?>
						</tbody>
					</table>
				</td>
				<?php if ($this->right): ?>
				<td class="right">
					<table cellspacing="0" cellpadding="0" border="0" width="100%">
						<tbody>
							<?php echo $this->right; ?>
						</tbody>
					</table>
				</td>
				<?php endif; ?>
			</tr>
		</tbody>
		<?php if ($this->footer): ?>
		<tfoot>
			<tr>
				<td class="footer"<?php if ($this->left && $this->right): ?> colspan="3"<?php elseif ($this->left || $this->right): ?> colspan="2"<?php endif; ?>>
					<table cellspacing="0" cellpadding="0" border="0" width="100%">
						<tbody>
							<?php echo $this->footer; ?>
						</tbody>
					</table>
				</td>
			</tr>
		</tfoot>
		<?php endif; ?>
	</table>
	</div>
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<?php if ($this->left): ?>
			<td class="left">
				<table cellspacing="0" cellpadding="0" border="0">
					<tbody>
						<?php echo $this->left; ?>
					</tbody>
				</table>
			</td>
			<?php endif; ?>
			<td class="body">
				<table cellspacing="0" cellpadding="0" border="0">
					<tbody>
						<?php echo $this->body; ?>
					</tbody>
				</table>
			</td>
			<?php if ($this->right): ?>
			<td class="right">
				<table cellspacing="0" cellpadding="0" border="0">
					<tbody>
						<?php echo $this->right; ?>
					</tbody>
				</table>
			</td>
			<?php endif; ?>
		</tr>
	</tbody>
	<?php if ($this->footer): ?>
	<tfoot>
		<tr>
			<td class="footer"<?php if ($this->left && $this->right): ?> colspan="3"<?php elseif ($this->left || $this->right): ?> colspan="2"<?php endif; ?>>
				<table cellspacing="0" cellpadding="0" border="0">
					<tbody>
						<?php echo $this->footer; ?>
					</tbody>
				</table>
			</td>
		</tr>
	</tfoot>
	<?php endif; ?>
</table>
<table class="after" align="center" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td>
			<?php if (TL_MODE != 'FE'): ?><div id="unsubscribe">{{newsletter::unsubscribe::html}}</div><?php endif ?>
		</td>
	</tr>
</table>
</body>
</html>