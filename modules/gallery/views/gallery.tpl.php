<?php if (!empty($data['image_id'])): ?>
<img class="img-responsive" src="<?php echo path($data['image_id']); ?>" alt="" />
<?php endif; ?>
<?php if (!empty($data['description'])): ?>
<div class="panel-body">
	<?php echo bbcode($data['description']); ?>
</div>
<?php endif; ?>
<?php if (!empty($data['images'])): ?>
<div class="panel-footer">
	<ul class="list-inline pull-right">
		<li><a href="#" data-toggle="modal" data-target="#modalGallery"><?php echo icon('fa-play-circle-o'); ?> Lancer le diaporama</a></li>
	</ul>
	<ul class="list-inline">
		<li><h4 class="no-margin"><?php echo icon('fa-photo').' '.$data['total_images'].' '.($data['total_images'] > 1 ? 'images' : 'image'); ?></h4></li>
	</ul>
	<div id="gallery-vignettes" class="row">
		<?php foreach ($data['images'] as $image): ?>
		<div class="image-item">
			<a class="thumbnail" href="<?php echo url('gallery/image/'.$image['image_id'].'/'.url_title($image['title']).'.html'); ?>">
				<img class="img-responsive" src="<?php echo path($image['thumbnail_file_id']); ?>" alt="" />
			</a>
		</div>
		<?php endforeach; ?>
	</div>
	<div class="text-right"><?php echo $data['pagination']; ?></div>
</div>
<?php endif; ?>
<?php if (!empty($data['carousel_images'])): ?>
<div id="modalGallery" class="modal fade modal-fullscreen force-fullscreen" tabindex="-1" role="dialog" aria-labelledby="modalGallery">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<div class="pull-right"><h4 class="modal-title"><?php echo icon('fa-photo').' '.($data['total_images'] > 1 ? $data['total_images'].' images' : $data['total_images'].' image'); ?></h4></div>
				<h4 class="modal-title"><?php echo icon('fa-play-circle-o fa-fw'); ?> <b>Diaporama</b> <?php echo $data['title']; ?></h4>
			</div>
			<div class="modal-body no-padding">
				<hr class="transition-timer-carousel-progress-bar" />
				<div id="carousel-gallery" class="carousel carousel-fit slide" data-ride="carousel">
					<div class="carousel-inner" role="listbox">
						<?php foreach ($data['carousel_images'] as $image): ?>
						<div class="item <?php echo !isset($active) ? $active = ' active' : ''; ?>">
							<img src="<?php echo path($image['file_id']); ?>" alt="" />
							<div class="carousel-caption">
								<h3><?php echo $image['title']; ?></h3>
								<?php echo $image['description']; ?>
							</div>
						</div>
						<?php endforeach; ?>
					</div>
					<a class="left carousel-control" href="#carousel-gallery" role="button" data-slide="prev">
						<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
						<span class="sr-only">Previous</span>
					</a>
					<a class="right carousel-control" href="#carousel-gallery" role="button" data-slide="next">
						<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
						<span class="sr-only">Next</span>
					</a>
				</div>
			</div>
			<div class="modal-footer">
				<a href="#carousel-gallery" role="button" data-slide="prev" class="btn btn-default" data-toggle="tooltip" title="Précédente"><?php echo icon('fa-angle-left'); ?></a>
				<div class="btn-group" role="group">
					<button type="button" id="playButton" class="btn btn-default" data-toggle="tooltip" title="Lecture"><?php echo icon('fa-play-circle'); ?></button>
					<button type="button" id="pauseButton" class="btn btn-default" data-toggle="tooltip" title="Pause"><?php echo icon('fa-pause'); ?></button>
				</div>
				<a href="#carousel-gallery" role="button" data-slide="next" class="btn btn-default" data-toggle="tooltip" title="Suivante"><?php echo icon('fa-angle-right'); ?></a>
				<button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo icon('fa-sign-out fa-fw'); ?> Quitter</button>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>