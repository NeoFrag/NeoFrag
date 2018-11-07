<?php foreach ($topics as $topic): ?>
<div class="media">
	<div class="media-left">
		<?php echo NeoFrag()->model2('user', $topic['user_id'])->avatar() ?>
	</div>
	<div class="media-body">
		<a href="<?php echo url('forum/topic/'.$topic['topic_id'].'/'.url_title($topic['title'])) ?>"><?php echo $topic['title'] ?></a><br />
		<small><?php echo icon('fa-clock-o').' '.time_span($topic['date']).' '.icon('fa-comments-o').' '.$topic['count_messages'] ?></small>
	</div>
</div>
<?php endforeach ?>
