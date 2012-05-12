<?php if(strlen($this->headline)):?><?php echo $this->hl; ?> <?php echo $this->headline; ?> <?php echo $this->hl; ?><?php endif;?>


<?php foreach($this->events as $event): ?>
 * <?php echo date('d.m.Y',$event['startTime']);?> - *<?php echo $event['title'];?>* [{{event_url::<?php echo $event['id'];?>}}]
   <?php if(strlen($event['teaser'])):?><?php echo $event['teaser'];?><?php endif;?>
    
<?php endforeach;?>

