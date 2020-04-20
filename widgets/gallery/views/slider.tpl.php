<div id="gallery_Carousel<?php echo $id ?>" class="carousel slide" data-ride="carousel">
	<div class="carousel-inner">
		<?php foreach ($images as $image): ?>
		<div class="carousel-item<?php echo !isset($active) ? $active = ' active' : '' ?>">
			<a href="<?php echo url('gallery/image/'.$image['image_id'].'/'.url_title($image['title'])) ?>"><img class="d-block w-100" src="<?php echo NeoFrag()->model2('file', $image['file_id'])->path() ?>" data-toggle="tooltip" title="<?php echo $image['title'] ?>" alt="" /></a>
		</div>
		<?php endforeach ?>
	</div>
	<a class="carousel-control-prev" href="#gallery_Carousel<?php echo $id ?>" role="button" data-slide="prev">
		<span class="carousel-control-prev-icon" aria-hidden="true"></span>
		<span class="sr-only"><?php echo $this->lang('Précédent') ?></span>
	</a>
	<a class="carousel-control-next" href="#gallery_Carousel<?php echo $id ?>" role="button" data-slide="next">
		<span class="carousel-control-next-icon" aria-hidden="true"></span>
		<span class="sr-only"><?php echo $this->lang('Suivant') ?></span>
	</a>
</div>
