<?php foreach ($news as $news): ?>
<div class="media">
	<?php echo NeoFrag()->model2('user', $news['user_id'])->avatar() ?>
	<div class="media-body">
		<a href="<?php echo url('news/'.$news['news_id'].'/'.url_title($news['title'])) ?>"><?php echo $news['title'] ?></a>
		<?php echo icon('fa-clock-o').' '.time_span($news['date']) ?> <a href="<?php echo url('news/'.$news['news_id'].'/'.url_title($news['title'])) ?>#comments"><?php echo icon('fa-comment-o').' '.$this->comments->count_comments('news', $news['news_id']) ?></a>
	</div>
</div>
<?php endforeach ?>
