<?php if(strlen($this->headline)):?><?php echo $this->hl; ?> <?php echo $this->headline; ?> <?php echo $this->hl; ?><?php endif;?>


<?php foreach($this->events as $event): ?>
 * <?php echo date('d.m.Y',$event['startTime']);?> - *<?php echo $event['title'];?>* [<?php echo $event['href'];?>">]
   <?php if(strlen($event['teaser'])):?><p><?php echo $event['teaser'];?></p><?php endif;?>
    
<?php endforeach;?>

