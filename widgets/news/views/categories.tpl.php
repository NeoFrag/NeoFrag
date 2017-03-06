<ul class="list-group">
	<?php foreach ($data['categories'] as $category): ?>
	<li class="list-group-item">
		<span class="label label-default pull-right"><?php echo $category['nb_news']; ?></span>
		<a href="<?php echo url('news/category/'.$category['category_id'].'/'.$category['name']); ?>"><?php echo $category['title']; ?></a>
	</li>
	<?php endforeach; ?>
</ul>