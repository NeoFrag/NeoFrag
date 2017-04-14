<div class="media popover-user">
	<div class="media-left">
		<?php echo $this->model2('user', $user_id)->avatar() ?>
	</div>
	<div class="media-body">
		<h4 class="media-heading"><?php echo $first_name.' '.$last_name ?> <b><?php echo $username ?></b></h4>
		<p><small><?php echo icon('fa-circle '.($online ? 'text-green' : 'text-gray')).' '.$this->lang($admin ? 'admin' : 'member').' '.$this->lang($online ? 'online' : 'offline') ?></small></p>
		<?php echo $this->groups->user_groups($user_id) ?>
	</div>
</div>
