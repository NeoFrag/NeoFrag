<div class="media">
	<div class="media-left">
		<img class="img-avatar-members-profil" style="max-height: 80px; max-width: 80px;" src="<?php echo $NeoFrag->user->avatar($data['avatar'], $data['sex']); ?>" title="{username}" alt="" />
	</div>
	<div class="media-body">
		<h4 class="media-heading">{first_name} {last_name} <b>{username}</b></h4>
		<p><small><i class="fa fa-circle <?php echo $data['online'] ? 'text-green' : 'text-gray'; ?>"></i> <?php echo $data['admin'] ? 'Admin' : 'Membre'; ?> <?php echo $data['online'] ? 'en ligne' : 'hors ligne'; ?></small></p>
		<?php echo $NeoFrag->groups->user_groups($data['user_id']); ?>
	</div>
</div>
