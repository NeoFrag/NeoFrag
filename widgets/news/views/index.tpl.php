<?php foreach ($news as $news): ?>
<div class="media">
	<?php if ($news['image']): ?>
	<div class="media-left">
		<div class="img-cover" style="background-image: url(<?php echo NeoFrag()->model2('file', $news['image'])->path() ?>);"></div>
	</div>
	<?php endif ?>
	<div class="media-body">
		<a href="<?php echo url('news/category/'.$news['category_id'].'/'.$news['category_name']) ?>" class="badge badge-dark"><?php echo $news['category_title'] ?></a><br />
		<a href="<?php echo url('news/'.$news['news_id'].'/'.url_title($news['title'])) ?>"><?php echo $news['title'] ?></a><br />
	</div>
</div>
<?php endforeach ?>
