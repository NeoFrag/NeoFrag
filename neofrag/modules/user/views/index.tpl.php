<div class="media">
	<div class="media-left">
		<a href="<?php echo url('members/'.$NeoFrag->user('user_id').'/'.url_title($NeoFrag->user('username')).'.html'); ?>">
			<img style="width: 64px; height: 64px;" src="<?php echo $NeoFrag->user->avatar(); ?>" data-toggle="tooltip" title="Voir mon profil" alt="" />
		</a>
	</div>
	<div class="media-body">
		<h4 class="media-heading"><?php echo $NeoFrag->user('first_name').' '.$NeoFrag->user('first_name').' <b>'.$NeoFrag->user('username').'</b>'; ?></h4>
		<?php echo $NeoFrag->groups->user_groups($NeoFrag->user('user_id')); ?>
		<hr />
		<dl class="dl-horizontal">
			<dt>Inscrit depuis le</dt>
			<dd><?php echo time_span($NeoFrag->user('registration_date')); ?></dd>
			<dt>Dernière activité</dt>
			<dd><?php echo time_span($NeoFrag->user('last_activity_date')); ?>. <a href="<?php echo url('user/sessions.html'); ?>"><?php echo icon('fa-globe'); ?> Gérer mes sessions</a></dd>
		</dl>
	</div>
</div>