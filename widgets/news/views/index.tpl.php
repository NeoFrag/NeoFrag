<?php foreach ($news as $news): ?>
<div class="media">
	<div class="media-left">
		<?php echo $this->model2('user', $news['user_id'])->avatar() ?>
	</div>
	<div class="media-body">
		<h4 class="media-heading"><a href="<?php echo url('news/'.$news['news_id'].'/'.url_title($news['title'])) ?>"><?php echo $news['title'] ?></a></h4>
		<?php echo icon('fa-clock-o').' '.time_span($news['date']) ?> <a href="<?php echo url('news/'.$news['news_id'].'/'.url_title($news['title'])) ?>#comments"><?php echo icon('fa-comment-o').' '.$this->comments->count_comments('news', $news['news_id']) ?></a>
	</div>
</div>
<?php endforeach ?>
