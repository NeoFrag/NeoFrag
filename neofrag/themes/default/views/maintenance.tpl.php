<nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-main-collapse">
				<?php echo icon('fa-bars'); ?>
			</button>
			<?php if ($NeoFrag->config->nf_maintenance_logo): ?>
			<img src="<?php echo path($NeoFrag->config->nf_maintenance_logo); ?>" class="logo" alt="" />
			<?php else: ?>
			<span class="navbar-brand"><?php echo $NeoFrag->config->nf_name; ?></span>
			<?php endif; ?>
		</div>
		<div class="collapse navbar-collapse navbar-right navbar-main-collapse">
			<ul class="nav navbar-nav">
				<?php
					foreach (['facebook' => 'Facebook', 'twitter' => 'Twitter', 'google-plus' => 'Google+', 'steam' => 'Steam', 'twitch' => 'Twitch'] as $name => $title)
					{
						if ($url = $NeoFrag->config->{'nf_maintenance_'.$name})
						{
							echo '<li><a href="'.$url.'">'.icon('fa-'.$name).' '.$title.'</a></li>';
						}
					}
				?>
			</ul>
		</div>
	</div>
</nav>
<section class="intro">
	<div class="intro-body">
		<div class="container">
			<h1 class="brand-heading"><?php echo $data['page_title']; ?></h1>
			<p class="intro-text"><?php echo bbcode($NeoFrag->config->nf_maintenance_content) ?: i18n('our_website_is_unavailable'); ?></p>
			<?php if ($NeoFrag->config->nf_maintenance_opening): ?>
				<?php echo i18n('coming_soon'); ?>
				<div id="countdown" class="countdownHolder" data-timestamp="<?php echo date_create($NeoFrag->config->nf_maintenance_opening)->getTimestamp(); ?>"></div>
			<?php endif; ?>
		</div>
	</div>
</section>
<?php if (!$NeoFrag->user()): ?>
	<section id="login">
		<a href="#" class="btn-close"><?php echo icon('fa-caret-square-o-right').' '.i18n('hide'); ?></a>
		<div class="login-body">
			<h2><?php echo i18n('login_title'); ?></h2>
			<form action="<?php echo url($NeoFrag->config->request_url); ?>" method="post">
				<div class="form-group">
					<input type="text" name="dd74f62896869c798933e29305aa9473[login]" class="form-control" placeholder="<?php echo i18n('username'); ?>" />
				</div>
				<div class="form-group">
					<input type="password" name="dd74f62896869c798933e29305aa9473[password]" class="form-control" placeholder="<?php echo i18n('password'); ?>" />
				</div>
				<button type="submit" class="btn btn-default"><?php echo icon('fa-lock').' '.i18n('login_title'); ?></button>
			</form>
		</div>
	</section>
<?php endif; ?>
<footer>
	<?php if ($NeoFrag->user()): ?>
		<a href="<?php echo url('user/logout.html'); ?>" class="btn-login"><?php echo $NeoFrag->user('username').' '.icon('fa-lock'); ?></a>
	<?php else: ?>
		<a href="#" class="btn-login"><?php echo icon('fa-unlock-alt'); ?></a>
	<?php endif; ?>
	<div class="container text-center">
		<p><?php echo i18n('copyright_all_rights_reserved'); ?></p>
	</div>
</footer>
