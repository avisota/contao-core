<?php if ($this->headline): ?>
<tr class="<?php echo $this->class; ?>"<?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
	<td valign="top">
		<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
	</td>
</tr>
<?php endif; ?>
<?php if ($this->addImage): ?>
<tr class="<?php echo $this->class; ?>"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
<td>
<table border="0" cellpadding="0" cellspacing="0" width="100%"><tbody>
<tr class="<?php echo $this->class; ?>"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
	<?php if ($this->floating == 'left' || $this->floating == 'above'): ?>
		<td valign="top" style="line-height: 0;">
			<div class="image_container<?php echo $this->floatClass; ?>"<?php if ($this->margin || $this->float): ?> style="<?php echo trim($this->margin . $this->float); ?>"<?php endif; ?>>
				<?php if ($this->href): ?>
				<a href="<?php echo $this->href; ?>"<?php echo $this->attributes; ?> title="<?php echo $this->alt; ?>">
				<?php endif; ?>
					<img src="<?php echo $this->src; ?>"<?php echo $this->imgSize; ?> alt="<?php echo $this->alt; ?>">
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
	<?php if ($this->floating == 'above'): ?>
	</tr>
	<tr>
	<?php endif; ?>
	<?php endif; ?>
		<td valign="top">
			<?php echo $this->text ?>
		</td>
	<?php if ($this->floating == 'right' || $this->floating == 'below'): ?>
	<?php if ($this->floating == 'below'): ?>
	</tr>
	<tr>
	<?php endif; ?>
		<td valign="top" style="line-height: 0;">
			<div class="image_container<?php echo $this->floatClass; ?>"<?php if ($this->margin || $this->float): ?> style="<?php echo trim($this->margin . $this->float); ?>"<?php endif; ?>>
				<?php if ($this->href): ?>
				<a href="<?php echo $this->href; ?>"<?php echo $this->attributes; ?> title="<?php echo $this->alt; ?>">
				<?php endif; ?>
					<img src="<?php echo $this->src; ?>"<?php echo $this->imgSize; ?> alt="<?php echo $this->alt; ?>">
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
	<?php endif; ?>
</tr>
</tbody></table>
</td>
</tr>
<?php else: ?>
<tr class="<?php echo $this->class; ?>"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
	<td valign="top">
		<?php echo $this->text ?>
	</td>
</tr>
<?php endif; ?>
