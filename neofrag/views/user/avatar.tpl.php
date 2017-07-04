<div class="avatar">
<?php if ($user_id): ?>
	<a href="<?php echo url('user/'.$user_id.'/'.url_title($username)) ?>">
		<img class="img-responsive" src="<?php echo $avatar ?>" alt="" />
	</a>
<?php else: ?>
	<img class="img-responsive" src="<?php echo $avatar ?>" alt="" />
<?php endif ?>
</div>
