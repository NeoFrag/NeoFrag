<ul class="list-group">
	<?php foreach ($data['gallery'] as $gallery): ?>
	<li class="list-group-item<?php echo ($this->url->request == 'gallery/album/'.$gallery['gallery_id'].'/'.$gallery['name'].'.html') ? ' active' : '' ; ?>">
		<span class="label label-default pull-right"><?php echo $gallery['images'].' '.icon('fa-photo'); ?></span>
		<a href="<?php echo url('gallery/album/'.$gallery['gallery_id'].'/'.$gallery['name'].'.html'); ?>"><?php echo $gallery['title']; ?></a>
	</li>
	<?php endforeach; ?>
</ul>