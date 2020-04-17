<?php if ($category): ?>
<div class="card mb-4">
	<div class="card-body">
		<h6 class="card-title mb-0"><?php echo ($category['icon_id'] ? '<img src="'.NeoFrag()->model2('file', $category['icon_id'])->path().'" class="img-icon mr-2" alt="" />' : icon('fas fa-book')).' '.$category['title'] ?></h6>
	</div>
	<?php if ($category['image_id']): ?>
	<img class="img-fluid" src="<?php echo NeoFrag()->model2('file', $category['image_id'])->path() ?>" alt="" />
	<?php endif ?>
</div>
<?php endif ?>
<?php if ($gallery): ?>
<div class="card-columns">
	<?php foreach ($gallery as $gallerie): ?>
	<div class="card">
		<?php if ($gallerie['image_id']): ?>
		<a href="<?php echo url('gallery/album/'.$gallerie['gallery_id'].'/'.url_title($gallerie['name'])) ?>"><img class="card-img-top" src="<?php echo NeoFrag()->model2('file', $gallerie['image_id'])->path() ?>" alt="" /></a>
		<?php endif ?>
		<div class="card-body">
			<h6 class="card-title mb-0"><a href="<?php echo url('gallery/album/'.$gallerie['gallery_id'].'/'.url_title($gallerie['name'])) ?>"><?php echo $gallerie['title'] ?></a></h6>
			<p class="card-text"><small class="text-muted"><?php echo icon('far fa-image').' '.$this->lang('%d image|%d images', $gallerie['images'], $gallerie['images']) ?></small></p>
		</div>
	</div>
	<?php endforeach ?>
</div>
<?php else: ?>
<div class="card border-info">
	<div class="card-body">
		<div class="text-center"><?php echo $category == NULL ? $this->lang('Aucun album') : $this->lang('Aucun album dans cette catégorie') ?></div>
	</div>
</div>
<?php endif ?>
