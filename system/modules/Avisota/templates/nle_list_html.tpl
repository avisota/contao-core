<?php if ($this->headline): ?>
<tr class="<?php echo $this->class; ?>"<?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
	<td valign="top">
		<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
	</td>
</tr>
<?php endif; ?>
<tr class="<?php echo $this->class; ?>"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
	<td valign="top">
		<<?php echo $this->tag; ?>>
			<?php foreach ($this->items as $item): ?>
			<li<?php if ($item['class']): ?> class="<?php echo $item['class']; ?>"<?php endif; ?>><?php echo $item['content']; ?></li>
			<?php endforeach; ?>
		</<?php echo $this->tag; ?>>
	</td>
</tr>
