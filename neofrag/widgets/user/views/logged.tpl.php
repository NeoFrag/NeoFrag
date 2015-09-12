<div class="panel-body text-center">
	<h4 class="no-margin">Bienvenue, <a href="<?php echo url('user.html'); ?>"><?php echo $data['username']; ?></a></h4>
	<br />
	<a href="<?php echo url('user.html'); ?>"><img src="<?php echo $NeoFrag->user->avatar(); ?>" style="max-width: 150px;" alt="" /></a>
</div>
<ul class="list-group">
	<li class="list-group-item">
		<!--<span class="label label-success pull-right"><?php echo 0; //TODO nombre de nouvelles notifications ?></span>-->
		<?php echo icon('fa-user'); ?> <a href="<?php echo url('user.html'); ?>">Mon espace</a>
	</li>
	<li class="list-group-item">
		<?php echo icon('fa-cogs'); ?> <a href="<?php echo url('user/edit.html'); ?>">Gérer mon compte</a>
	</li>
	<li class="list-group-item">
		<?php echo icon('fa-eye'); ?> <a href="<?php echo url('members/'.$NeoFrag->user('user_id').'/'.url_title($data['username']).'.html'); ?>">Voir mon profil</a>
	</li>
	<!--<li class="list-group-item">
		<?php if ($NeoFrag->user('messages_unread') > 0): ?><span class="label label-danger pull-right"><?php echo $NeoFrag->user('messages_unread'); ?></span><?php endif; ?>
		<?php echo icon('fa-envelope-o'); ?> <a href="<?php echo url('user/messages.html'); ?>">Messagerie</a>
	</li>-->
	<?php if ($NeoFrag->user('admin')): //TODO permission ?>
	<li class="list-group-item">
		<?php echo icon('fa-dashboard'); ?> <a href="<?php echo url('admin.html'); ?>">Administration</a>
	</li>
	<?php endif; ?>
</ul>