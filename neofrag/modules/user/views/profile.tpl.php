<ul class="list-unstyled text-center">
	<li>
		<img src="<?php echo $NeoFrag->user->avatar($data['avatar'], $data['sex']); ?>" class="avatar" />
	</li>
	<li>
		<h4 class="no-margin"><b><?php echo $NeoFrag->user->link($data['user_id'], $data['username']) ?></b></h4>
		<?php echo in_array('admins', $this->groups($data['user_id'])) ? 'Administrateur' : 'Membre'; ?>
	</li>
</ul>
<a href="<?php echo url('user/logout.html'); ?>" class="btn btn-primary btn-block"><?php echo icon('fa-power-off'); ?> Se déconnecter</a>