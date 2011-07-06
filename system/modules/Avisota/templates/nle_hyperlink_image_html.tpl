<?php if ($this->headline): ?>
<tr class="<?php echo $this->class; ?>"<?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
	<td valign="top">
		<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
	</td>
</tr>
<?php endif; ?>
<tr class="<?php echo $this->class; ?>"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
	<td valign="top" style="line-height: 0;">
		<div class="image_container">
			<?php echo $this->embed_pre; ?><a href="<?php echo $this->href; ?>" class="hyperlink_img" title="<?php echo $this->title; ?>"<?php if ($this->rel): ?> rel="<?php echo $this->rel; ?>"<?php endif; ?><?php echo $this->target; ?>><img src="<?php echo $this->src; ?>"<?php echo $this->imgSize; ?> alt="<?php echo $this->alt; ?>" title="<?php echo $this->title; ?>" /></a><?php echo $this->embed_post; ?>
			<?php if ($this->caption): ?>
			<div class="caption" style="line-height: normal;"><?php echo $this->caption; ?></div>
			<?php endif; ?>
		</div>
	</td>
</tr>
