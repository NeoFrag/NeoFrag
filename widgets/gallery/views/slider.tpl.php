<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
	<div class="carousel-inner" role="listbox">
		<?php foreach ($data['images'] as $image): ?>
		<div class="item<?php echo !isset($active) ? $active = ' active' : ''; ?>">
			<a href="{base_url}gallery/image/<?php echo $image['image_id']; ?>/<?php echo url_title($image['title']); ?>.html"><img class="img-responsive" src="{image <?php echo $image['file_id']; ?>}" data-toggle="tooltip" title="<?php echo $image['title']; ?>" alt="" /></a>
		</div>
		<?php endforeach; ?>
	</div>
	<a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
		<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
		<span class="sr-only">Previous</span>
	</a>
	<a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
		<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
		<span class="sr-only">Next</span>
	</a>
</div>