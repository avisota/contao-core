<ul>
	<?php foreach ($this->newsletters as $newsletter): ?>
	<li>
		<?php if ($newsletter['href']): ?>
		<a href="<?php echo $newsletter['href']; ?>">
		<?php endif; ?>
		<?php echo $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $newsletter['sendOn']); ?> <?php echo $newsletter['subject']; ?>
		<?php if ($newsletter['href']): ?>
		</a>
		<?php endif; ?>
	</li>
	<?php endforeach; ?>
</ul>