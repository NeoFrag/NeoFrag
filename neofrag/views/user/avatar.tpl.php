<div class="avatar">
<?php if ($data['user_id']): ?>
	<a href="<?php echo url('user/'.$data['user_id'].'/'.url_title($data['username'])); ?>">
		<img class="img-responsive" src="<?php echo $data['avatar']; ?>" alt="" />
	</a>
<?php else: ?>
	<img class="img-responsive" src="<?php echo $data['avatar']; ?>" alt="" />
<?php endif; ?>
</div>
