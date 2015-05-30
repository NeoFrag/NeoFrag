<?php foreach ($data['topics'] as $topic): ?>
<div class="media">
	<a href="{base_url}members/<?php echo $topic['user_id']; ?>/<?php echo url_title($topic['username']); ?>.html" class="media-left">
		<img style="width: 48px; height: 48px;" src="<?php echo $NeoFrag->user->avatar($topic['avatar'], $topic['sex']); ?>" data-toggle="tooltip" title="<?php echo $topic['username']; ?>" alt="" />
	</a>
	<div class="media-body">
		<p class="media-heading"><a href="{base_url}forum/topic/<?php echo $topic['topic_id']; ?>/<?php echo url_title($topic['title']); ?>.html"><?php echo $topic['title']; ?></a></p>
		{fa-icon clock-o} <?php echo time_span($topic['date']); ?> {fa-icon comments-o} <?php echo $topic['count_messages']; ?></div>
</div>
<?php endforeach; ?>