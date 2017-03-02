<?php foreach ($data['replies'] as $i => $reply): ?>
<?php if ($i) echo '<hr />'; ?>
<div class="media message">
	<div class="media-left">
		<?php echo $this->user->avatar($reply['avatar'], $reply['sex'], $reply['user_id'], $reply['username']); ?>
	</div>
	<div class="media-body">
		<small class="pull-right text-muted"><?php echo time_span($reply['date']); ?></small>
		<h4 class="media-heading" style="margin-top: 3px;"><b><?php echo $this->user->link($reply['user_id'], $reply['username']) ?></b></h4>
		<?php echo bbcode($reply['message']); ?>
	</div>
</div>
<?php endforeach; ?>