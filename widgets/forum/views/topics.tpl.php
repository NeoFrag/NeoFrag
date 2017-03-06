<?php foreach ($data['topics'] as $topic): ?>
<div class="media">
	<div class="media-left">
	<?php if ($topic['user_id']): ?>
		<?php echo $this->user->avatar($topic['avatar'], $topic['sex'], $topic['user_id'], $topic['username']); ?>
	<?php else: ?>
		<?php echo $this->user->avatar(NULL); ?>"
	<?php endif; ?>
	</div>
	<div class="media-body">
		<p class="media-heading"><a href="<?php echo url('forum/topic/'.$topic['topic_id'].'/'.url_title($topic['title'])); ?>"><?php echo $topic['title']; ?></a></p>
		<?php echo icon('fa-clock-o').' '.time_span($topic['date']).' '.icon('fa-comments-o').' '.$topic['count_messages']; ?></div>
</div>
<?php endforeach; ?>