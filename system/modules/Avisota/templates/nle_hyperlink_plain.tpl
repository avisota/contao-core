<?php if ($this->headline): ?><?php echo $this->hl; ?> <?php echo $this->headline; ?> <?php echo $this->hl; ?>

<?php endif; // headline

echo $this->embed_pre;
if ($this->href != $this->link):
	printf('%s [%s]', $this->link, $this->href);
else:
	echo $this->href;
endif;
echo $this->embed_post;
?>


