<?php if ($this->headline): ?><?php echo $this->hl; ?> <?php echo $this->headline; ?> <?php echo $this->hl; ?>

<?php endif; // headline

if ($this->addImage): ?>
[<?php echo $this->src; ?>]
<?php if ($this->caption): ?>
(<?php echo $this->caption; ?>)
<?php
endif; // caption
?>
<?php
endif; // addImage

echo $this->text;
?>


