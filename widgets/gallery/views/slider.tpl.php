<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
	<div class="carousel-inner" role="listbox">
		<?php foreach ($data['images'] as $image): ?>
		<div class="item<?php echo !isset($active) ? $active = ' active' : ''; ?>">
			<a href="<?php echo url('gallery/image/'.$image['image_id'].'/'.url_title($image['title'])); ?>"><img class="img-responsive" src="<?php echo path($image['file_id']); ?>" data-toggle="tooltip" title="<?php echo $image['title']; ?>" alt="" /></a>
		</div>
		<?php endforeach; ?>
	</div>
	<a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
		<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
		<span class="sr-only"><?php echo $this->lang('previous'); ?></span>
	</a>
	<a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
		<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
		<span class="sr-only"><?php echo $this->lang('next'); ?></span>
	</a>
</div>