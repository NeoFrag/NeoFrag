<ul class="list-unstyled text-center">
	<li>
		<?php echo $this->user->avatar() ?>
	</li>
	<li>
		<h4 class="m-0"><b><?php echo $this->user->link($user_id, $username) ?></b></h4>
		<?php echo in_array('admins', $this->groups($user_id)) ? 'Administrateur' : 'Membre' ?>
	</li>
</ul>
<a href="<?php echo url('user/logout') ?>" class="btn btn-primary btn-block"><?php echo icon('fa-power-off') ?> Se déconnecter</a>
