<ul class="nav navbar-nav navbar-right">
	<?php if ($NeoFrag->user()): ?>
		<li><p class="navbar-text">Bienvenue <a href="{base_url}user.html"><?php echo $NeoFrag->user('username'); ?></a></p></li>
		<li><a href="{base_url}user/edit.html"><i class="fa fa-cogs"></i></a></li>
		<li><a href="{base_url}members/<?php echo $this->user('user_id'); ?>/<?php echo url_title($NeoFrag->user('username')); ?>.html"><i class="fa fa-eye"></i></a></li>
		<?php if ($NeoFrag->user('admin') == TRUE): ?>
			<li><a href="{base_url}admin.html"><i class="fa fa-dashboard"></i></a></li>
		<?php endif; ?>
		<li><a href="{base_url}user/logout.html"><i class="fa fa-close"></i></a></li>
	<?php else: ?>
		<li><p class="navbar-text"><a href="{base_url}user.html">Créer un compte</a></p></li>
		<li><a href="{base_url}user.html"><i class="fa fa-sign-out"></i>&nbsp;&nbsp;Connexion</a></li>
	<?php endif; ?>
</ul>
