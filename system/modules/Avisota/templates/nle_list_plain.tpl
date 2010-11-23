<?php if ($this->headline): ?><?php echo $this->hl; ?> <?php echo $this->headline; ?> <?php echo $this->hl; ?>

<?php endif; // headline

if ($this->listtype == 'ordered'):
	$index = 0;
else:
	$prefix = '• ';
endif;

foreach ($this->items as $item):
	if ($this->listtype == 'ordered'):
		$index ++;
		$prefix = $index . '. ';
	endif;
	
	echo $prefix . str_replace("\n", "\n  ", $item) . "\n";
endforeach;
?>

