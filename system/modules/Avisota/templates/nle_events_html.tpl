<div class="<?php echo $this->class; ?> block"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
<?php // var_dump($this->events); ?>
<?php if(strlen($this->headline)):?><<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>><?php endif;?>
<ul>
<?php foreach($this->events as $event): ?>
	<li>
		<?php echo $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'],$event['startTime']);?> - <b><a href="<?php echo $event['href'];?>"><?php echo $event['title'];?></a></b>
		<?php if(strlen($event['teaser'])):?><p><?php echo $event['teaser'];?></p><?php endif;?>
	</li>
<?php endforeach;?>
</ul>
</div>