<div class="media popover-user">
	<?php echo $user->avatar() ?>
	<div class="media-body">
		<?php echo $user->profile()->first_name.' '.$user->profile()->last_name ?> <b><?php echo $user->profile()->username ?></b>
		<?php //echo $user->groups() ?>
	</div>
</div>
