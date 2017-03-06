<?php foreach ($data['messages'] as $message): ?>
<div class="media">
	<div class="media-left">
	<?php if ($message['user_id']): ?>
		<?php echo $this->user->avatar($message['avatar'], $message['sex'], $message['user_id'], $message['username']); ?>
	<?php else: ?>
		<?php echo $this->user->avatar(NULL); ?>
	<?php endif; ?>
	</div>
	<div class="media-body">
		<p class="media-heading"><a href="<?php echo url('forum/topic/'.$message['topic_id'].'/'.url_title($message['topic_title'])); ?>#<?php echo $message['message_id']; ?>" data-toggle="tooltip" title="<?php echo $message['topic_title']; ?>"><?php echo str_shortener(strip_tags(str_replace('<br />', ' ', bbcode($message['message']))), 150); ?></a></p>
		<?php echo icon('fa-clock-o').' '.time_span($message['date']); ?>
	</div>
</div>
<?php endforeach; ?>