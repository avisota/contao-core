<div class="<?php echo $this->class; ?> block"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
<?php //var_dump($this->news); ?>
<?php if(strlen($this->headline)):?><<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>><?php endif;?>
<ul>
<?php foreach($this->news as $item): ?>
	<li>
		<?php echo $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'],$item['time']);?> - <b><a href="<?php echo $item['href'];?>"><?php echo $item['headline'];?></a></b>
		<?php if(strlen($item['teaser'])):?><p><?php echo $item['teaser'];?></p><?php endif;?>
	</li>
<?php endforeach;?>
</ul>
</div>