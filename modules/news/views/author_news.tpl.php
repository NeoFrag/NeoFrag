<?php if (!empty($news)): ?>
<ul class="list-group">
	<?php foreach ($news as $news): ?>
	<li class="list-group-item">
		<span class="label label-default pull-right"><?php echo $news['category_title'] ?></span>
		<a href="<?php echo url('news/'.$news['news_id'].'/'.url_title($news['title'])) ?>"><?php echo str_shortener($news['title'], 35) ?></a>
	</li>
	<?php endforeach ?>
</ul>
<?php else: ?>
<div class="panel-body text-center">
	<?php echo $this->lang('L\'auteur n\'a pas publié d\'autre actualité') ?>
</div>
<?php endif ?>
