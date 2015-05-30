<div class="media">
	<div class="media-left">
		<a href="{base_url}members/{user user_id}/<?php echo url_title($NeoFrag->user('username')); ?>.html">
			<img style="width: 64px; height: 64px;" src="<?php echo $NeoFrag->user->avatar(); ?>" data-toggle="tooltip" title="Voir mon profil" alt="" />
		</a>
	</div>
	<div class="media-body">
		<h4 class="media-heading">{user first_name} {user last_name} <b>{user username}</b></h4>
		<?php echo $NeoFrag->groups->user_groups($NeoFrag->user('user_id')); ?>
		<hr />
		<dl class="dl-horizontal">
			<dt>Inscrit depuis le</dt>
			<dd><?php echo time_span($NeoFrag->user('registration_date')); ?></dd>
			<dt>Dernière activité</dt>
			<dd><?php echo time_span($NeoFrag->user('last_activity_date')); ?>. <a href="{base_url}user/sessions.html"><i class="fa fa-globe"></i> Gérer mes sessions</a></dd>
		</dl>
	</div>
</div>