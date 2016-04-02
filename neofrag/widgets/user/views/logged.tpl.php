<div class="panel-body text-center">
	<h4 class="no-margin"><?php echo i18n('welcome'); ?></h4>
	<br />
	<?php echo $NeoFrag->user->avatar(); ?>
</div>
<ul class="list-group">
	<li class="list-group-item">
		<!--<span class="label label-success pull-right"><?php echo 0; //TODO nombre de nouvelles notifications ?></span>-->
		<?php echo icon('fa-user'); ?> <a href="<?php echo url('user.html'); ?>"><?php echo i18n('my_account'); ?></a>
	</li>
	<li class="list-group-item">
		<?php echo icon('fa-cogs'); ?> <a href="<?php echo url('user/edit.html'); ?>"><?php echo i18n('manage_my_account'); ?></a>
	</li>
	<li class="list-group-item">
		<?php echo icon('fa-eye'); ?> <a href="<?php echo url('members/'.$NeoFrag->user('user_id').'/'.url_title($data['username']).'.html'); ?>"><?php echo i18n('view_my_profile'); ?></a>
	</li>
	<li class="list-group-item">
		<?php if ($messages = $NeoFrag->user->get_messages()): ?><span class="label label-danger pull-right"><?php echo $messages; ?></span><?php endif; ?>
		<?php echo icon('fa-envelope-o'); ?> <a href="<?php echo url('user/messages.html'); ?>">Messagerie</a>
	</li>
	<?php if ($this->access->admin()): ?>
	<li class="list-group-item">
		<?php echo icon('fa-dashboard'); ?> <a href="<?php echo url('admin.html'); ?>"><?php echo i18n('administration'); ?></a>
	</li>
	<?php endif; ?>
</ul>