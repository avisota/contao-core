<?php
foreach ($this->body as $class=>$row):
foreach ($row as $col):
if ($col->addImage):
?>[<?php echo $col->src; ?>]
<?php
if ($col->alt): ?>(<?php echo $col->alt; ?>)
<?php
endif;
if ($col->href): ?>(<?php echo $col->href; ?>)
<?php
endif;
?>
<?php
if ($col->caption):
?>
<?php echo $col->caption; ?>
<?php
endif;
?>

<?php
endif;
endforeach;
endforeach;
?>