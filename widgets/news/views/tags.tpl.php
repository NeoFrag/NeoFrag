<ul class="list-inline mb-0">
<?php foreach ($tags as $tag): ?>
	<li class="list-inline-item"><a href="<?php echo url('news/tag/'.url_title($tag)) ?>"><?php echo $tag ?></a></li>
<?php endforeach ?>
</ul>
