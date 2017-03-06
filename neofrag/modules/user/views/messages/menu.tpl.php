<div class="list-group">
	<a href="<?php echo url('user/messages/compose'); ?>" class="list-group-item<?php echo ($this->url->segments[0] == 'user' && $this->url->segments[1] == 'messages' && isset($this->url->segments[2]) && $this->url->segments[2] == 'compose') ? ' active' : ''; ?>"><?php echo icon('fa-edit'); ?> Rédiger un message</a>
	<a href="<?php echo url('user/messages'); ?>" class="list-group-item<?php echo ($this->url->segments[0] == 'user' && $this->url->segments[1] == 'messages' && !isset($this->url->segments[2])) ? ' active' : ''; ?>">
		<?php if ($messages = $this->user->get_messages()): ?>
		<span class="label label-danger pull-right"><?php echo $messages.' '.icon('fa-envelope-o'); ?></span>
		<?php endif; ?>
		<?php echo icon('fa-inbox'); ?>Boîte de réception
	</a>
	<a href="<?php echo url('user/messages/sent'); ?>" class="list-group-item<?php echo ($this->url->segments[0] == 'user' && $this->url->segments[1] == 'messages' && isset($this->url->segments[2]) && $this->url->segments[2] == 'sent') ? ' active' : ''; ?>"><?php echo icon('fa-send-o'); ?> Messages envoyés</a>
	<a href="<?php echo url('user/messages/archives'); ?>" class="list-group-item<?php echo ($this->url->segments[0] == 'user' && $this->url->segments[1] == 'messages' && isset($this->url->segments[2]) && $this->url->segments[2] == 'archives') ? ' active' : ''; ?>"><?php echo icon('fa-archive'); ?> Archives</a>
</div>