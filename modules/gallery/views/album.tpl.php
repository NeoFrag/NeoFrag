<?php if ($image_id): ?>
<img class="card-img-top" src="<?php echo NeoFrag()->model2('file', $image_id)->path() ?>" alt="" />
<?php elseif ($image): ?>
<img class="card-img-top" src="<?php echo NeoFrag()->model2('file', $image)->path() ?>" alt="" />
<?php endif ?>
<div class="card-body">
	<h5 class="card-title mb-0"><?php echo $title ?></h5>
	<p><a href="<?php echo url('gallery/'.$category_id.'/'.$category_name) ?>" class="badge badge-dark"><?php echo $category_title ?></a></p>
	<?php if ($description): ?>
		<p><?php echo bbcode($description) ?></p>
	<?php endif ?>
	<p class="card-text"><small class="text-muted"><?php echo icon('far fa-image').' '.$this->lang('%d image|%d images', $count, $count) ?></small></p>
</div>
