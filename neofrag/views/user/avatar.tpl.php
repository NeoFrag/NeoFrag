<div class="avatar">
<?php if ($data['user_id']): ?>
	<a href="<?php echo url('user/'.$data['user_id'].'/'.url_title($data['username'])) ?>">
		<img class="img-fluid" src="<?php echo $data['avatar'] ?>" alt="" />
	</a>
<?php else: ?>
	<img class="img-fluid" src="<?php echo $data['avatar'] ?>" alt="" />
<?php endif ?>
</div>
