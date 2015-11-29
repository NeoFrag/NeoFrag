<?php foreach ($data['messages'] as $message): ?>
<div class="media">
	<?php if ($message['user_id']): ?>
	<a href="<?php echo url('members/'.$message['user_id'].'/'.url_title($message['username']).'.html'); ?>" class="media-left">
		<img style="width: 48px; height: 48px;" src="<?php echo $NeoFrag->user->avatar($message['avatar'], $message['sex']); ?>" data-toggle="tooltip" title="<?php echo $message['username']; ?>" alt="" />
	</a>
	<?php else: ?>
	<div class="media-left">
		<img style="width: 48px; height: 48px;" src="<?php echo $NeoFrag->user->avatar(); ?>" data-toggle="tooltip" title="<?php echo i18n('guest'); ?>" alt="" />
	</div>
	<?php endif; ?>
	<div class="media-body">
		<p class="media-heading"><a href="<?php echo url('forum/topic/'.$message['topic_id'].'/'.url_title($message['topic_title']).'.html'); ?>#<?php echo $message['message_id']; ?>" data-toggle="tooltip" title="<?php echo $message['topic_title']; ?>"><?php echo str_shortener(strip_tags(str_replace('<br />', ' ', bbcode($message['message']))), 150); ?></a></p>
		<?php echo icon('fa-clock-o').' '.time_span($message['date']); ?>
	</div>
</div>
<?php endforeach; ?>