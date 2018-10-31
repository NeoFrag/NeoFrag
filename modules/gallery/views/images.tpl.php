<?php if ($images): ?>
<div class="card-columns">
	<?php foreach ($images as $image): ?>
	<div class="card">
		<a href="#" data-modal-ajax="<?php echo url('ajax/gallery/image/'.$image['image_id'].'/'.url_title($image['title'])) ?>">
			<img class="img-fluid" src="<?php echo NeoFrag()->model2('file', $image['thumbnail_file_id'])->path() ?>" alt="" />
		</a>
	</div>
	<?php endforeach ?>
</div>
<?php echo $pagination ?>
<?php else: ?>
<div class="card border-info">
	<div class="card-body">
		<div class="text-center"><?php echo $this->lang('Aucune image dans cet album') ?></div>
	</div>
</div>
<?php endif ?>
