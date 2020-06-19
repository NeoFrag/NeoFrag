<ul class="list-group list-group-flush">
	<?php foreach ($gallery as $gallery): ?>
	<li class="list-group-item<?php echo ($this->url->request == 'gallery/album/'.$gallery['gallery_id'].'/'.$gallery['name']) ? ' active' : ''  ?>">
		<span class="badge badge-secondary float-right"><?php echo $gallery['images'].' '.icon('far fa-image') ?></span>
		<a href="<?php echo url('gallery/album/'.$gallery['gallery_id'].'/'.$gallery['name']) ?>"><?php echo $gallery['title'] ?></a>
	</li>
	<?php endforeach ?>
</ul>
