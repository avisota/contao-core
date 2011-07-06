<?php if ($this->headline): ?>
<tr class="<?php echo $this->class; ?>"<?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
	<td valign="top">
		<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
	</td>
</tr>
<?php endif; ?>
<tr class="<?php echo $this->class; ?>"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
	<td valign="top">
		<?php echo $this->embed_pre; ?><a href="<?php echo $this->href; ?>" class="hyperlink_txt" title="<?php echo $this->title; ?>"<?php if ($this->rel): ?> rel="<?php echo $this->rel; ?>"<?php endif; ?><?php echo $this->target; ?>><?php echo $this->link; ?></a><?php echo $this->embed_post; ?> 
	</td>
</tr>