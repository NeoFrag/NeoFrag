<div class="media">
	<div class="media-left">
		<img class="img-avatar-members-profil" style="max-height: 80px; max-width: 80px;" src="<?php echo $NeoFrag->user->avatar($data['avatar'], $data['sex']); ?>" title="<?php echo $data['username']; ?>" alt="" />
	</div>
	<div class="media-body">
		<h4 class="media-heading"><?php echo $data['first_name']; ?> <?php echo $data['last_name']; ?> <b><?php echo $data['username']; ?></b></h4>
		<p><small><?php echo icon('fa-circle fa-fw '.($data['online'] ? 'text-green' : 'text-gray')).' '.($data['admin'] ? 'Admin' : 'Membre').' '.($data['online'] ? 'en ligne' : 'hors ligne'); ?></small></p>
		<?php echo $NeoFrag->groups->user_groups($data['user_id']); ?>
	</div>
</div>
