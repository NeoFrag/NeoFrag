<?php if (!empty($image_id)): ?>
<img class="img-fluid" src="<?php echo NeoFrag()->model2('file', $image_id)->path() ?>" alt="" />
<?php endif ?>
<?php if (!empty($description)): ?>
<div class="panel-body">
	<?php echo bbcode($description) ?>
</div>
<?php endif ?>
<?php if (!empty($images)): ?>
<div class="panel-footer">
	<ul class="list-inline pull-right">
		<li><a href="#" data-toggle="modal" data-target="#modalGallery"><?php echo icon('fa-play-circle-o').' '.$this->lang('Lancer le diaporama') ?></a></li>
	</ul>
	<ul class="list-inline">
		<li><h4 class="m-0"><?php echo icon('fa-photo').' '.$this->lang('<b>%d</b> image|<b>%d</b> images', $total_images, $total_images) ?></h4></li>
	</ul>
	<div id="gallery-vignettes" class="row">
		<?php foreach ($images as $image): ?>
		<div class="image-item">
			<a class="thumbnail" href="<?php echo url('gallery/image/'.$image['image_id'].'/'.url_title($image['title'])) ?>">
				<img class="img-fluid" src="<?php echo NeoFrag()->model2('file', $image['thumbnail_file_id'])->path() ?>" alt="" />
			</a>
		</div>
		<?php endforeach ?>
	</div>
	<div class="text-right"><?php echo $pagination ?></div>
</div>
<?php endif ?>
<?php if (!empty($carousel_images)): ?>
<div id="modalGallery" class="modal fade modal-fullscreen force-fullscreen" tabindex="-1" role="dialog" aria-labelledby="modalGallery">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<div class="pull-right"><h4 class="modal-title"><?php echo icon('fa-photo').' '.$this->lang('<b>%d</b> image|<b>%d</b> images', $total_images, $total_images) ?></h4></div>
				<h4 class="modal-title"><?php echo icon('fa-play-circle-o') ?> <b><?php echo $this->lang('Diaporama') ?></b> <?php echo $title ?></h4>
			</div>
			<div class="modal-body no-padding">
				<hr class="transition-timer-carousel-progress-bar" />
				<div id="carousel-gallery" class="carousel carousel-fit slide" data-ride="carousel">
					<div class="carousel-inner" role="listbox">
						<?php foreach ($carousel_images as $image): ?>
						<div class="item <?php echo !isset($active) ? $active = ' active' : '' ?>">
							<img src="<?php echo NeoFrag()->model2('file', $image['file_id'])->path() ?>" alt="" />
							<div class="carousel-caption">
								<h3><?php echo $image['title'] ?></h3>
								<?php echo $image['description'] ?>
							</div>
						</div>
						<?php endforeach ?>
					</div>
					<a class="left carousel-control" href="#carousel-gallery" role="button" data-slide="prev">
						<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
						<span class="sr-only"><?php echo $this->lang('Précédente') ?></span>
					</a>
					<a class="right carousel-control" href="#carousel-gallery" role="button" data-slide="next">
						<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
						<span class="sr-only"><?php echo $this->lang('Suivante') ?></span>
					</a>
				</div>
			</div>
			<div class="modal-footer">
				<a href="#carousel-gallery" role="button" data-slide="prev" class="btn btn-default" data-toggle="tooltip" title="<?php echo $this->lang('Précédente') ?>"><?php echo icon('fa-angle-left') ?></a>
				<div class="btn-group" role="group">
					<button type="button" id="playButton" class="btn btn-default" data-toggle="tooltip" data-container="body" title="<?php echo $this->lang('Lecture') ?>"><?php echo icon('fa-play-circle') ?></button>
					<button type="button" id="pauseButton" class="btn btn-default" data-toggle="tooltip" data-container="body" title="<?php echo $this->lang('Pause') ?>"><?php echo icon('fa-pause') ?></button>
				</div>
				<a href="#carousel-gallery" role="button" data-slide="next" class="btn btn-default" data-toggle="tooltip" title="<?php echo $this->lang('Suivante') ?>"><?php echo icon('fa-angle-right') ?></a>
				<button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo icon('fa-sign-out').' '.$this->lang('Quitter') ?></button>
			</div>
		</div>
	</div>
</div>
<?php endif ?>
