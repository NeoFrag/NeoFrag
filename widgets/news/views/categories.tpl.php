<ul class="list-group">
	<?php foreach ($data['categories'] as $category): ?>
	<li class="list-group-item">
		<span class="label label-default pull-right"><?php echo $category['nb_news']; ?></span>
		<a href="{base_url}news/category/<?php echo $category['category_id']; ?>/<?php echo $category['name']; ?>.html"><?php echo $category['title']; ?></a>
	</li>
	<?php endforeach; ?>
</ul>