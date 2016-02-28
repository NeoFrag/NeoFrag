<ul class="nav navbar-nav <?php echo !empty($data['align']) ? $data['align'] : 'navbar-right'; ?>">
	<?php if ($NeoFrag->user()): ?>
		<li><p class="navbar-text"><?php echo i18n('welcome'); ?></p></li>
		<li data-toggle="tooltip" title="Gérer mon compte"><a href="<?php echo url('user/edit.html'); ?>"><?php echo icon('fa-cogs'); ?></a></li>
		<li data-toggle="tooltip" title="Messagerie">
			<?php if ($messages = $NeoFrag->user->get_messages()): ?><span class="label label-danger pull-right"><?php echo $messages; ?></span><?php endif; ?>
			<a href="<?php echo url('user/messages.html'); ?>"><?php echo icon('fa-envelope-o'); ?></a>
		</li>
		<?php if ($NeoFrag->user('admin') == TRUE): ?>
			<li data-toggle="tooltip" title="Adminsitration"><a href="<?php echo url('admin.html'); ?>"><?php echo icon('fa-dashboard'); ?></a></li>
		<?php endif; ?>
		<li data-toggle="tooltip" title="Déconnexion"><a href="<?php echo url('user/logout.html'); ?>"><?php echo icon('fa-close'); ?></a></li>
	<?php else: ?>
		<li><p class="navbar-text"><a href="<?php echo url('user.html'); ?>"><?php echo i18n('create_account'); ?></a></p></li>
		<li><a href="<?php echo url('user.html'); ?>"><?php echo icon('fa-sign-in').' '.i18n('login'); ?></a></li>
	<?php endif; ?>
</ul>
