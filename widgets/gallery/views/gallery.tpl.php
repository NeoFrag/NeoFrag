<ul class="list-group">
	<?php foreach ($data['gallery'] as $gallery): ?>
	<li class="list-group-item<?php echo ($NeoFrag->config->request_url == 'gallery/album/'.$gallery['gallery_id'].'/'.$gallery['name'].'.html') ? ' active' : '' ; ?>">
		<span class="label label-default pull-right"><?php echo $gallery['images']; ?> {fa-icon photo}</span>
		<a href="{base_url}gallery/album/<?php echo $gallery['gallery_id']; ?>/<?php echo $gallery['name']; ?>.html"><?php echo $gallery['title']; ?></a>
	</li>
	<?php endforeach; ?>
</ul>