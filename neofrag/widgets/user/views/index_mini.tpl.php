<ul class="nav navbar-nav navbar-right">
	<?php if ($NeoFrag->user()): ?>
		<li><p class="navbar-text"><?php echo i18n('welcome'); ?></p></li>
		<li><a href="<?php echo url('user/edit.html'); ?>"><?php echo icon('fa-cogs'); ?></a></li>
		<li><a href="<?php echo url('members/'.$this->user('user_id').'/'.url_title($NeoFrag->user('username')).'.html'); ?>"><?php echo icon('fa-eye'); ?></a></li>
		<?php if ($NeoFrag->user('admin') == TRUE): ?>
			<li><a href="<?php echo url('admin.html'); ?>"><?php echo icon('fa-dashboard'); ?></a></li>
		<?php endif; ?>
		<li><a href="<?php echo url('user/logout.html'); ?>"><?php echo icon('fa-close'); ?></a></li>
	<?php else: ?>
		<li><p class="navbar-text"><a href="<?php echo url('user.html'); ?>"><?php echo i18n('create_account'); ?></a></p></li>
		<li><a href="<?php echo url('user.html'); ?>"><?php echo icon('fa-sign-out'); ?>&nbsp;&nbsp;<?php echo i18n('login'); ?></a></li>
	<?php endif; ?>
</ul>
