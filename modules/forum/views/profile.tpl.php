<div class="forum-profile">
	<?php if (!empty($data['user_id'])): ?>
	<h4><?php echo $this->user->link($data['user_id'], $data['username']);?></h4>
	<p><?php echo icon('fa-circle '.($data['online'] ? 'text-green' : 'text-gray')).' '.$this->lang($data['admin'] ? 'admin' : 'member').' '.$this->lang($data['online'] ? 'online' : 'offline'); ?></p>
	<?php echo $this->user->avatar($data['avatar'], $data['sex'], $data['user_id'], $data['username']); ?>
	<p><i><?php echo $this->lang('topics', $data['topics'], $data['topics']).' '.$this->lang('messages', $data['replies'], $data['replies']); ?></i></p>
	<div class="forum-groups">
		<?php echo $this->groups->user_groups($data['user_id']); ?>
	</div>
	<?php else: ?>
	<h4><i><?php echo $this->lang('guest'); ?></i></h4>
	<?php echo $this->user->avatar(NULL); ?>
	<?php endif; ?>
</div>
