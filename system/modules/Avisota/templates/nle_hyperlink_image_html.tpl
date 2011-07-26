<tr>
	<td valign="top" class="<?php echo $this->class; ?>"<?php echo $this->cssID; ?> style="line-height: 0; <?php echo $this->style; ?>">
		<?php if ($this->headline): ?>
		<<?php echo $this->hl; ?> style="line-height: normal;"><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
		<?php endif; ?>
		<div class="image_container">
			<?php echo $this->embed_pre; ?><a href="<?php echo $this->href; ?>" class="hyperlink_img" title="<?php echo $this->title; ?>"<?php if ($this->rel): ?> rel="<?php echo $this->rel; ?>"<?php endif; ?><?php echo $this->target; ?>><img src="<?php echo $this->src; ?>"<?php echo $this->imgSize; ?> alt="<?php echo $this->alt; ?>" title="<?php echo $this->title; ?>" /></a><?php echo $this->embed_post; ?>
			<?php if ($this->caption): ?>
			<p class="caption" style="line-height: normal;"><?php echo $this->caption; ?></p>
			<?php endif; ?>
		</div>
	</td>
</tr>
