<?php if(strlen($this->headline)):?><?php echo $this->hl; ?> <?php echo $this->headline; ?> <?php echo $this->hl; ?><?php endif;?>


<?php foreach($this->news as $item): ?>
 * <?php echo date('d.m.Y',$item['time']);?> - *<?php echo $item['headline'];?>* [<?php echo $item['href'];?>]
   <?php if(strlen($item['teaser'])):?><?php echo $item['teaser'];?><?php endif;?>
    
<?php endforeach;?>

