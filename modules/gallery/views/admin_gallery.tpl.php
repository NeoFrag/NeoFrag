<?php if (!empty($data['images'])): ?>
	<div class="pull-right">
		<div class="btn-group" role="group">
			<button type="button" class="btn btn-default active" id="gallery-display-vignettes" data-toggle="tooltip" data-container="body" title="<?php echo i18n('thumbnails'); ?>"><?php echo icon('fa-photo'); ?></button>
			<button type="button" class="btn btn-default" id="gallery-display-tableau" data-toggle="tooltip" data-container="body" title="<?php echo i18n('table'); ?>"><?php echo icon('fa-navicon'); ?></button>
		</div>
	</div>
	<div class="text-left">
		<h4><?php echo icon('fa-photo').' '.i18n('list'); ?> <small><?php echo i18n('images', $count = count($data['images']), $count); ?></small></h4>
	</div>
	<div class="vignettes-content">
		<div id="gallery-table">
			<?php echo $data['gallery_table']; ?>
		</div>
		<div id="gallery-vignettes" class="row">
			<?php foreach ($data['images'] as $image): ?>
				<div class="gallery-item">
					<div class="thumbnail">
					<a class="thumbnail-link" data-toggle="tooltip" title="<?php echo i18n('view'); ?>" data-image="<?php echo path($image['file_id']); ?>" data-title="<?php echo $image['title']; ?>" data-description="<?php echo $image['description']; ?>"><img class="img-responsive" src="<?php echo path($image['thumbnail_file_id']); ?>" alt="" /></a>
						<div class="actions">
						<a href="<?php echo url('admin/gallery/image/'.$image['image_id'].'/'.url_title($image['title']).'.html'); ?>" class="btn btn-outline btn-info btn-xs" data-toggle="tooltip" title="<?php echo i18n('edit'); ?>"><?php echo icon('fa-pencil'); ?></a>
						<?php echo $this->button_delete('admin/gallery/image/delete/'.$image['image_id'].'/'.url_title($image['title']).'.html'); ?>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
<?php else: ?>
	<div class="alert alert-info text-center no-margin"><?php echo i18n('no_images'); ?></div>
<?php endif; ?>