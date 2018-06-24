<?php foreach ($data['replies'] as $i => $reply): ?>
<?php if ($i) echo '<hr />' ?>
<div class="media message">
	<?php echo $this->user->avatar($reply['avatar'], $reply['sex'], $reply['user_id'], $reply['username']) ?>	</div>
	<div class="media-body">
		<small class="pull-right text-muted"><?php echo time_span($reply['date']) ?></small>
		<b><?php echo $this->user->link($reply['user_id'], $reply['username']) ?></b>
		<?php echo bbcode($reply['message']) ?>
	</div>
</div>
<?php endforeach ?>
