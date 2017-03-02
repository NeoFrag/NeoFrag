<nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-main-collapse">
				<?php echo icon('fa-bars'); ?>
			</button>
			<?php if ($this->config->nf_maintenance_logo): ?>
			<img src="<?php echo path($this->config->nf_maintenance_logo); ?>" class="logo" alt="" />
			<?php else: ?>
			<span class="navbar-brand"><?php echo $this->config->nf_name; ?></span>
			<?php endif; ?>
		</div>
		<div class="collapse navbar-collapse navbar-right navbar-main-collapse">
			<ul class="nav navbar-nav">
				<?php
					foreach (['facebook' => 'Facebook', 'twitter' => 'Twitter', 'google-plus' => 'Google+', 'steam' => 'Steam', 'twitch' => 'Twitch'] as $name => $title)
					{
						if ($url = $this->config->{'nf_maintenance_'.$name})
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
			<p class="intro-text"><?php echo bbcode($this->config->nf_maintenance_content) ?: $this->lang('our_website_is_unavailable'); ?></p>
			<?php if ($this->config->nf_maintenance_opening): ?>
				<?php echo $this->lang('coming_soon'); ?>
				<div id="countdown" class="countdownHolder" data-timestamp="<?php echo date_create($this->config->nf_maintenance_opening)->getTimestamp(); ?>"></div>
			<?php endif; ?>
		</div>
	</div>
</section>
<section id="login">
	<a href="#" class="btn-close"><?php echo icon('fa-caret-square-o-right').' '.$this->lang('hide'); ?></a>
	<div class="login-body">
		<h2><?php echo $this->lang('login_title'); ?></h2>
		<form action="<?php echo url($this->url->request); ?>" method="post">
			<div class="form-group">
				<input type="text" name="<?php echo $token = $this->form->token('dd74f62896869c798933e29305aa9473'); ?>[login]" class="form-control" placeholder="<?php echo $this->lang('username'); ?>" />
			</div>
			<div class="form-group">
				<input type="password" name="<?php echo $token; ?>[password]" class="form-control" placeholder="<?php echo $this->lang('password'); ?>" />
			</div>
			<button type="submit" class="btn btn-default"><?php echo icon('fa-lock').' '.$this->lang('login_title'); ?></button>
		</form>
	</div>
</section>
<footer>
	<a href="#" class="btn-login"><?php echo icon('fa-unlock-alt'); ?></a>
	<div class="container text-center">
		<p><?php echo $this->lang('copyright_all_rights_reserved'); ?></p>
	</div>
</footer>
