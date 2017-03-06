<?php if (!empty($data['news'])): ?>
<ul class="list-group">
	<?php foreach ($data['news'] as $news): ?>
	<li class="list-group-item">
		<span class="label label-default pull-right"><?php echo $news['category_title']; ?></span>
		<a href="<?php echo url('news/'.$news['news_id'].'/'.url_title($news['title'])); ?>"><?php echo str_shortener($news['title'], 35); ?></a>
	</li>
	<?php endforeach; ?>
</ul>
<?php else: ?>
<div class="panel-body text-center">
	<?php echo $this->lang('no_news_from_author'); ?>
</div>
<?php endif; ?>
