<?php foreach ($data['messages'] as $message): ?>
<div class="media">
	<a href="{base_url}members/<?php echo $message['user_id']; ?>/<?php echo url_title($message['username']); ?>.html" class="media-left">
		<img style="width: 48px; height: 48px;" src="<?php echo $NeoFrag->user->avatar($message['avatar'], $message['sex']); ?>" data-toggle="tooltip" title="<?php echo $message['username']; ?>" alt="" />
	</a>
	<div class="media-body">
		<p class="media-heading"><a href="{base_url}forum/topic/<?php echo $message['topic_id']; ?>/<?php echo url_title($message['topic_title']); ?>.html#<?php echo $message['message_id']; ?>" data-toggle="tooltip" title="<?php echo $message['topic_title']; ?>"><?php echo str_shortener(strip_tags(str_replace('<br />', ' ', bbcode($message['message']))), 150); ?></a></p>
		{fa-icon clock-o} <?php echo time_span($message['date']); ?>
	</div>
</div>
<?php endforeach; ?>