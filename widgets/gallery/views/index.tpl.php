<ul class="list-group">
	<?php foreach ($data['categories'] as $category): ?>
	<li class="list-group-item<?php echo ($NeoFrag->config->request_url == 'gallery/'.$category['category_id'].'/'.$category['name'].'.html') ? ' active' : '' ; ?>">
		<span class="label label-default pull-right"><?php echo $category['nb_gallery']; ?></span>
		<a href="{base_url}gallery/<?php echo $category['category_id']; ?>/<?php echo $category['name']; ?>.html"><?php echo $category['title']; ?></a>
	</li>
	<?php endforeach; ?>
</ul>