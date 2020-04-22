<?php foreach ($topics as $topic): ?>
<div class="media">
	<div class="media-left">
		<?php echo $this->module('user')->model2('user', $topic['user_id'])->avatar() ?>
	</div>
	<div class="media-body">
		<a href="<?php echo url('forum/topic/'.$topic['topic_id'].'/'.url_title($topic['title'])) ?>"><?php echo $topic['title'] ?></a><br />
		<small><?php echo icon('far fa-clock').' '.time_span($topic['date']).' '.icon('far fa-comments').' '.$topic['count_messages'] ?></small>
	</div>
</div>
<?php endforeach ?>
