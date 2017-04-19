<?php if (!empty($images)): ?>
	<div class="pull-right">
		<div class="btn-group" role="group">
			<button type="button" class="btn btn-default active" id="gallery-display-vignettes" data-toggle="tooltip" data-container="body" title="<?php echo $this->lang('Vignettes') ?>"><?php echo icon('fa-photo') ?></button>
			<button type="button" class="btn btn-default" id="gallery-display-tableau" data-toggle="tooltip" data-container="body" title="<?php echo $this->lang('Tableau') ?>"><?php echo icon('fa-navicon') ?></button>
		</div>
	</div>
	<div class="text-left">
		<h4><?php echo icon('fa-photo').' '.$this->lang('Liste') ?> <small><?php echo $this->lang('<b>%d</b> image|<b>%d</b> images', $count = count($images), $count) ?></small></h4>
	</div>
	<div class="vignettes-content">
		<div id="gallery-table">
			<?php echo $gallery_table ?>
		</div>
		<div id="gallery-vignettes" class="row">
			<?php foreach ($images as $image): ?>
				<div class="gallery-item">
					<div class="thumbnail">
					<a class="thumbnail-link" data-toggle="tooltip" title="<?php echo $this->lang('Visualiser') ?>" data-image="<?php echo NeoFrag()->model2('file', $image['file_id'])->path() ?>" data-title="<?php echo $image['title'] ?>" data-description="<?php echo $image['description'] ?>"><img class="img-fluid" src="<?php echo NeoFrag()->model2('file', $image['thumbnail_file_id'])->path() ?>" alt="" /></a>
						<div class="actions">
						<a href="<?php echo url('admin/gallery/image/'.$image['image_id'].'/'.url_title($image['title'])) ?>" class="btn btn-outline btn-info btn-xs" data-toggle="tooltip" title="<?php echo $this->lang('Éditer') ?>"><?php echo icon('fa-pencil') ?></a>
						<?php echo $this->button_delete('admin/gallery/image/delete/'.$image['image_id'].'/'.url_title($image['title'])) ?>
						</div>
					</div>
				</div>
			<?php endforeach ?>
		</div>
	</div>
<?php else: ?>
	<div class="alert alert-info text-center m-0"><?php echo $this->lang('Il n\'y a pas encore d\'image') ?></div>
<?php endif ?>
