<?php if (!empty($images)): ?>
	<h5><?php echo icon('far fa-image') ?> <small><?php echo $this->lang('<b>%d</b> image|<b>%d</b> images', $count = count($images), $count) ?></small></h5>
	<?php echo $gallery_table ?>
<?php else: ?>
	<div class="alert alert-info text-center m-0"><?php echo $this->lang('Il n\'y a pas encore d\'image') ?></div>
<?php endif ?>
