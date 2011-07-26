<?php if ($this->addImage): ?>
<tr>
	<td class="<?php echo $this->class; ?>"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
		<table cellpadding="0" cellspacing="0">
			<?php if ($this->headline): ?>
			<thead>
				<tr class="<?php echo $this->class; ?>"<?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
					<td valign="top">
						<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
					</td>
				</tr>
			</thead>
			<?php endif; ?>
			<tbody>
				<tr>
					<?php if ($this->floating == 'left'): ?>
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
							<p class="caption" style="line-height: normal;"><?php echo $this->caption; ?></p>
							<?php endif; ?>
						</div>
					</td>
					<td valign="top">
						<?php echo $this->text ?>
					</td>
					<?php endif; ?>
					<?php if ($this->floating == 'right'): ?>
					<td valign="top">
						<?php echo $this->text ?>
					</td>
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
							<p class="caption" style="line-height: normal;"><?php echo $this->caption; ?></p>
							<?php endif; ?>
						</div>
					</td>
					<?php endif; ?>
				</tr>
			</tbody>
		</table>
	</td>
</tr>
<?php else: ?>
<tr>
	<td valign="top" class="<?php echo $this->class; ?>"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
		<?php if ($this->headline): ?>
		<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
		<?php endif; ?>
		<?php echo $this->text ?>
	</td>
</tr>
<?php endif; ?>
