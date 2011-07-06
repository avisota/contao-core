<?php if ($this->headline): ?>
<tr class="<?php echo $this->class; ?>"<?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
	<td valign="top">
		<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
	</td>
</tr>
<?php endif; ?>
<tr class="<?php echo $this->class; ?>"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
	<td valign="top" style="line-height: 0;">
		<div class="image_container"<?php if ($this->margin): ?> style="<?php echo $this->margin; ?>"<?php endif; ?>>
			<?php if ($this->href): ?>
			<a href="<?php echo $this->href; ?>"<?php echo $this->attributes; ?> title="<?php echo $this->alt; ?>">
				<?php endif; ?>
				<img src="<?php echo $this->src; ?>"<?php echo $this->imgSize; ?> alt="<?php echo $this->alt; ?>" />
				<?php if ($this->href): ?>
			</a>
			<?php endif; ?>
			<?php if ($this->caption): ?>
			<div class="caption" style="line-height: normal;">
				<?php echo $this->caption; ?>
			</div>
			<?php endif; ?>
		</div>
	</td>
</tr>
