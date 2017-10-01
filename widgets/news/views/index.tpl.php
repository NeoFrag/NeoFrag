<?php foreach ($data['news'] as $news): ?>
<div class="media">
	<?php if ($news['user_id']): ?>
		<?php echo $this->user->avatar($news['avatar'], $news['sex'], $news['user_id'], $news['username']) ?>
	<?php else: ?>
		<?php echo $this->user->avatar(NULL) ?>
	<?php endif ?>
	<div class="media-body">
		<a href="<?php echo url('news/'.$news['news_id'].'/'.url_title($news['title'])) ?>"><?php echo $news['title'] ?></a>
		<?php echo icon('fa-clock-o').' '.time_span($news['date']) ?> <a href="<?php echo url('news/'.$news['news_id'].'/'.url_title($news['title'])) ?>#comments"><?php echo icon('fa-comment-o').' '.$this->comments->count_comments('news', $news['news_id']) ?></a>
	</div>
</div>
<?php endforeach ?>
