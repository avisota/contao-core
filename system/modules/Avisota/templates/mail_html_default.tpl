<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 3.2//EN">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="Generator" content="Contao Open Source CMS">
	<title><?php echo $this->title; ?></title>
	<?php echo $this->head; ?>
</head>
<body>
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td>
				<?php if (TL_MODE != 'FE'): ?><div id="onlinelink"><a href="{{newsletter::href}}"><?php echo $GLOBALS['TL_LANG']['tl_avisota_newsletter']['online'] ?></a></div><?php endif ?>
			</td>
		</tr>
	</table>
	<div id="wrapper">
	<table cellpadding="0" cellspacing="0" border="0">
		<tbody>
			<tr>
				<td class="body">
					<table cellspacing="0" cellpadding="0" border="0" width="100%">
						<tbody>
							<?php echo $this->body; ?>
						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
	</div>
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td align="center">
				<?php if (TL_MODE != 'FE'): ?><div id="unsubscribe">{{newsletter::unsubscribe::html}}</div><?php endif ?>
			</td>
		</tr>
	</table>
</body>
</html>