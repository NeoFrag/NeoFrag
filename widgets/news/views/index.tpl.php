<?php foreach ($news as $news): ?>
<div class="media">
	<?php echo NeoFrag()->model2('user', $news['user_id'])->avatar() ?>
	<div class="media-body">
		<a href="<?php echo url('news/'.$news['news_id'].'/'.url_title($news['title'])) ?>"><?php echo $news['title'] ?></a>
		<?php echo icon('fa-clock-o').' '.time_span($news['date']) ?>
		<?php if (($comments = $this->module('comments')) && $comments->is_enabled()): ?>
			<?php echo $comments->link('news', $news['news_id'], 'news/'.$news['news_id'].'/'.url_title($news['title'])) ?>
		<?php endif ?>
	</div>
</div>
<?php endforeach ?>
