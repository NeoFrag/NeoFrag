<div class="media">
	<div class="media-left">
		<img class="img-avatar-members-profil" style="max-height: 80px; max-width: 80px;" src="<?php echo $NeoFrag->user->avatar($data['avatar'], $data['sex']); ?>" title="<?php echo $data['username']; ?>" alt="" />
	</div>
	<div class="media-body">
		<h4 class="media-heading"><?php echo $data['first_name'].' '.$data['last_name']; ?> <b><?php echo $data['username']; ?></b></h4>
		<p><small><?php echo icon('fa-circle '.($data['online'] ? 'text-green' : 'text-gray')).' '.i18n($data['admin'] ? 'admin' : 'member').' '.i18n($data['online'] ? 'online' : 'offline'); ?></small></p>
		<?php echo $NeoFrag->groups->user_groups($data['user_id']); ?>
	</div>
</div>
