<div class="list-group">
	<a href="<?php echo url('user'); ?>" class="list-group-item<?php echo !isset($this->url->segments[1]) ? ' active' : ''; ?>"><?php echo icon('fa-user'); ?> Mon espace</a>
	<a href="<?php echo url('user/edit'); ?>" class="list-group-item<?php echo isset($this->url->segments[1]) && $this->url->segments[1] == 'edit' ? ' active' : ''; ?>"><?php echo icon('fa-cogs'); ?> Gérer mon compte</a>
	<a href="<?php echo url('user/messages'); ?>" class="list-group-item">
		<?php if ($messages = $this->user->get_messages()): ?>
		<span class="label label-danger pull-right"><?php echo $messages.' '.icon('fa-envelope-o'); ?></span>
		<?php endif; ?>
		<?php echo icon('fa-envelope-o'); ?> Messagerie privée
	</a>
	<a href="<?php echo url('user/sessions'); ?>" class="list-group-item<?php echo isset($this->url->segments[1]) && $this->url->segments[1] == 'sessions' ? ' active' : ''; ?>"><?php echo icon('fa-globe').' '.$this->lang('manage_my_sessions'); ?></a>
</div>