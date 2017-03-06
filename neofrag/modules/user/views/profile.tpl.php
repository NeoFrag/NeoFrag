<ul class="list-unstyled text-center">
	<li>
		<?php echo $this->user->avatar($data['avatar'], $data['sex']); ?>
	</li>
	<li>
		<h4 class="no-margin"><b><?php echo $this->user->link($data['user_id'], $data['username']) ?></b></h4>
		<?php echo in_array('admins', $this->groups($data['user_id'])) ? 'Administrateur' : 'Membre'; ?>
	</li>
</ul>
<a href="<?php echo url('user/logout'); ?>" class="btn btn-primary btn-block"><?php echo icon('fa-power-off'); ?> Se déconnecter</a>