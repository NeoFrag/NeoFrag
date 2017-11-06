<div class="media popover-user">
	<?php echo $this->user->avatar($data['avatar'], $data['sex'], $data['user_id'], $data['username']) ?>
	<div class="media-body">
		<?php echo $data['first_name'].' '.$data['last_name'] ?> <b><?php echo $data['username'] ?></b>
		<p><small><?php echo icon('fa-circle '.($data['online'] ? 'text-green' : 'text-gray')).' '.$this->lang($data['admin'] ? 'Administrateur' : 'Membre').' '.$this->lang($data['online'] ? 'en ligne' : 'hors ligne') ?></small></p>
		<?php echo $this->groups->user_groups($data['user_id']) ?>
	</div>
</div>
