<div class="panel-body text-center">
	<h4 class="no-margin">Bienvenue, <a href="{base_url}user.html">{username}</a></h4>
	<br />
	<a href="{base_url}user.html"><img src="<?php echo $NeoFrag->user->avatar(); ?>" style="max-width: 150px;" alt="" /></a>
</div>
<ul class="list-group">
	<li class="list-group-item">
		<!--<span class="label label-success pull-right"><?php echo 0; //TODO nombre de nouvelles notifications ?></span>-->
		<i class="fa fa-user"></i> <a href="{base_url}user.html">Mon espace</a>
	</li>
	<li class="list-group-item">
		<i class="fa fa-cogs"></i> <a href="{base_url}user/edit.html">Gérer mon compte</a>
	</li>
	<li class="list-group-item">
		<i class="fa fa-eye"></i> <a href="{base_url}members/{user user_id}/{url_title(username)}.html">Voir mon profil</a>
	</li>
	<!--<li class="list-group-item">
		<?php if ($NeoFrag->user('messages_unread') > 0): ?><span class="label label-danger pull-right"><?php echo $NeoFrag->user('messages_unread'); ?></span><?php endif; ?>
		<i class="fa fa-envelope-o"></i> <a href="{base_url}user/messages.html">Messagerie</a>
	</li>-->
	<?php if ($NeoFrag->user('admin')): //TODO permission ?>
	<li class="list-group-item">
		<i class="fa fa-dashboard"></i> <a href="{base_url}admin.html">Administration</a>
	</li>
	<?php endif; ?>
</ul>