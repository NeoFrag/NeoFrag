<div class="media popover-user">
	<?php echo $this->user->avatar($avatar, $sex, $user_id, $username) ?>
	<div class="media-body">
		<?php echo $first_name.' '.$last_name ?> <b><?php echo $username ?></b>
		<p><small><?php echo icon('fa-circle '.($online ? 'text-green' : 'text-gray')).' '.$this->lang($admin ? 'Administrateur' : 'Membre').' '.$this->lang($online ? 'en ligne' : 'hors ligne') ?></small></p>
		<?php echo $this->groups->user_groups($user_id) ?>
	</div>
</div>
