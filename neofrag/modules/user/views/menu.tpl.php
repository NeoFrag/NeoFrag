<div class="list-group">
	<a href="<?php echo url('user.html'); ?>" class="list-group-item<?php echo !isset($this->config->segments_url[1]) ? ' active' : ''; ?>"><?php echo icon('fa-user'); ?> Mon espace</a>
	<a href="<?php echo url('user/edit.html'); ?>" class="list-group-item<?php echo isset($this->config->segments_url[1]) && $this->config->segments_url[1] == 'edit' ? ' active' : ''; ?>"><?php echo icon('fa-cogs'); ?> Gérer mon compte</a>
	<a href="<?php echo url('user/messages.html'); ?>" class="list-group-item">
		<?php if ($messages = $this->user->get_messages()): ?>
		<span class="label label-danger pull-right"><?php echo $messages.' '.icon('fa-envelope-o'); ?></span>
		<?php endif; ?>
		<?php echo icon('fa-envelope-o'); ?> Messagerie privée
	</a>
	<a href="<?php echo url('user/sessions.html'); ?>" class="list-group-item<?php echo isset($this->config->segments_url[1]) && $this->config->segments_url[1] == 'sessions' ? ' active' : ''; ?>"><?php echo icon('fa-globe').' '.i18n('manage_my_sessions'); ?></a>
</div>