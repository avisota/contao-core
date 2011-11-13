<tr>
	<td valign="top" class="<?php echo $this->class; ?>"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
		<?php if(strlen($this->headline)):?>
		<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
		<?php endif;?>
		<ul>
		<?php foreach($this->events as $event): ?>
			<li>
				<?php echo $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'],$event['startTime']);?> - <b><a href="{{event_url::<?php echo $event['id'];?>}}"><?php echo $event['title'];?></a></b>
				<?php if(strlen($event['teaser'])):?><p><?php echo $event['teaser'];?></p><?php endif;?>
			</li>
		<?php endforeach;?>
		</ul>
	</td>
</tr>
