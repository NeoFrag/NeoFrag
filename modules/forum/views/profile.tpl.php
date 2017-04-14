<div class="forum-profile">
	<?php if (!empty($user_id)): ?>
	<h4><?php echo $this->user->link($user_id, $username) ?></h4>
	<p><?php echo icon('fa-circle '.($online ? 'text-green' : 'text-gray')).' '.$this->lang($admin ? 'admin' : 'member').' '.$this->lang($online ? 'online' : 'offline') ?></p>
	<?php echo $this->model2('user', $user_id)->avatar() ?>
	<p><i><?php echo $this->lang('topics', $topics, $topics).' '.$this->lang('messages', $replies, $replies) ?></i></p>
	<div class="forum-groups">
		<?php echo $this->groups->user_groups($user_id) ?>
	</div>
	<?php else: ?>
	<h4><i><?php echo $this->lang('guest') ?></i></h4>
	<?php echo $this->model2('user')->avatar() ?>
	<?php endif ?>
</div>
