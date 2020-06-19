<ul class="list-group list-group-flush">
	<?php foreach ($categories as $category): ?>
	<li class="list-group-item">
		<span class="badge badge-secondary float-right"><?php echo $category['nb_news'] ?></span>
		<a href="<?php echo url('news/category/'.$category['category_id'].'/'.$category['name']) ?>"><?php echo $category['title'] ?></a>
	</li>
	<?php endforeach ?>
</ul>
