<div class="panel-body text-center">
	<h4 class="no-margin"><?php echo $this->lang('welcome'); ?></h4>
	<br />
	<?php echo $this->user->avatar(); ?>
</div>
<ul class="list-group">
	<li class="list-group-item">
		<!--<span class="label label-success pull-right"><?php echo 0; //TODO nombre de nouvelles notifications ?></span>-->
		<?php echo icon('fa-user'); ?> <a href="<?php echo url('user'); ?>"><?php echo $this->lang('my_account'); ?></a>
	</li>
	<li class="list-group-item">
		<?php echo icon('fa-cogs'); ?> <a href="<?php echo url('user/edit'); ?>"><?php echo $this->lang('manage_my_account'); ?></a>
	</li>
	<li class="list-group-item">
		<?php echo icon('fa-eye'); ?> <a href="<?php echo url('user/'.$this->user('user_id').'/'.url_title($data['username'])); ?>"><?php echo $this->lang('view_my_profile'); ?></a>
	</li>
	<li class="list-group-item">
		<?php if ($messages = $this->user->get_messages()): ?><span class="label label-danger pull-right"><?php echo $messages; ?></span><?php endif; ?>
		<?php echo icon('fa-envelope-o'); ?> <a href="<?php echo url('user/messages'); ?>">Messagerie</a>
	</li>
	<?php if ($this->access->admin()): ?>
	<li class="list-group-item">
		<?php echo icon('fa-dashboard'); ?> <a href="<?php echo url('admin'); ?>"><?php echo $this->lang('administration'); ?></a>
	</li>
	<?php endif; ?>
</ul>