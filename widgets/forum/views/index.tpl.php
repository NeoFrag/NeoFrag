<?php foreach ($messages as $message): ?>
<div class="media">
	<?php echo NeoFrag()->model2('user', $message['user_id'])->avatar() ?>
	<div class="media-body">
		<p><a href="<?php echo url('forum/topic/'.$message['topic_id'].'/'.url_title($message['topic_title'])) ?>#<?php echo $message['message_id'] ?>" data-toggle="tooltip" title="<?php echo $message['topic_title'] ?>"><?php echo str_shortener(strip_tags(str_replace('<br />', ' ', bbcode($message['message']))), 150) ?></a></p>
		<?php echo icon('fa-clock-o').' '.time_span($message['date']) ?>
	</div>
</div>
<?php endforeach ?>
