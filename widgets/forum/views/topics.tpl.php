<?php foreach ($data['topics'] as $topic): ?>
<div class="media">
	<a href="<?php echo url('members/'.$topic['user_id'].'/'.url_title($topic['username']).'.html'); ?>" class="media-left">
		<img style="width: 48px; height: 48px;" src="<?php echo $NeoFrag->user->avatar($topic['avatar'], $topic['sex']); ?>" data-toggle="tooltip" title="<?php echo $topic['username']; ?>" alt="" />
	</a>
	<div class="media-body">
		<p class="media-heading"><a href="<?php echo url('forum/topic/'.$topic['topic_id'].'/'.url_title($topic['title']).'.html'); ?>"><?php echo $topic['title']; ?></a></p>
		<?php echo icon('fa-clock-o').' '.time_span($topic['date']).' '.icon('fa-comments-o').' '.$topic['count_messages']; ?></div>
</div>
<?php endforeach; ?>