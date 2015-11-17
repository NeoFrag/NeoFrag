<div class="media">
	<div class="media-left">
		<a href="<?php echo url('members/'.$NeoFrag->user('user_id').'/'.url_title($NeoFrag->user('username')).'.html'); ?>">
			<img style="width: 64px; height: 64px;" src="<?php echo $NeoFrag->user->avatar(); ?>" data-toggle="tooltip" title="<?php echo i18n('view_my_profile'); ?>" alt="" />
		</a>
	</div>
	<div class="media-body">
		<h4 class="media-heading"><?php echo $NeoFrag->user('first_name').' '.$NeoFrag->user('last_name').' <b>'.$NeoFrag->user('username').'</b>'; ?></h4>
		<?php echo $NeoFrag->groups->user_groups($NeoFrag->user('user_id')); ?>
		<hr />
		<dl class="dl-horizontal">
			<dt><?php echo i18n('registration_date'); ?></dt>
			<dd><?php echo time_span($NeoFrag->user('registration_date')); ?></dd>
			<dt><?php echo i18n('last_activity'); ?></dt>
			<dd><?php echo time_span($NeoFrag->user('last_activity_date')); ?>. <a href="<?php echo url('user/sessions.html'); ?>"><?php echo icon('fa-globe').' '.i18n('manage_my_sessions'); ?></a></dd>
		</dl>
	</div>
</div>