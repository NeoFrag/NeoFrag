<ul class="nav navbar-nav <?php echo !empty($data['align']) ? $data['align'] : 'navbar-right'; ?>">
	<?php if ($this->user()): ?>
		<li><p class="navbar-text"><?php echo $this->lang('welcome'); ?></p></li>
		<li data-toggle="tooltip" title="Gérer mon compte"><a href="<?php echo url('user/edit'); ?>"><?php echo icon('fa-cogs'); ?></a></li>
		<li data-toggle="tooltip" title="Messagerie">
			<?php if ($messages = $this->user->get_messages()): ?><span class="label label-danger pull-right"><?php echo $messages; ?></span><?php endif; ?>
			<a href="<?php echo url('user/messages'); ?>"><?php echo icon('fa-envelope-o'); ?></a>
		</li>
		<?php if ($this->access->admin()): ?>
			<li data-toggle="tooltip" title="Administration"><a href="<?php echo url('admin'); ?>"><?php echo icon('fa-dashboard'); ?></a></li>
		<?php endif; ?>
		<li data-toggle="tooltip" title="Déconnexion"><a href="<?php echo url('user/logout'); ?>"><?php echo icon('fa-close'); ?></a></li>
	<?php else: ?>
		<li><p class="navbar-text"><a href="<?php echo url('user'); ?>"><?php echo $this->lang('create_account'); ?></a></p></li>
		<li><a href="<?php echo url('user'); ?>"><?php echo icon('fa-sign-in').' '.$this->lang('login'); ?></a></li>
	<?php endif; ?>
</ul>
