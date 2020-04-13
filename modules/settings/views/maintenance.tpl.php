<div class="cover-container text-center d-flex w-100 h-100 p-3 mx-auto flex-column">
	<header class="masthead mb-auto">
		<div class="inner">
			<h3 class="masthead-brand">
				<?php if ($this->config->nf_maintenance_logo): ?>
					<img src="<?php echo NeoFrag()->model2('file', $this->config->nf_maintenance_logo)->path() ?>" class="logo" alt="" />
				<?php else: ?>
					<?php echo $this->config->nf_name ?>
				<?php endif ?>
			</h3>
			<nav class="nav nav-masthead justify-content-center">
				<?php
				foreach ([
					'behance'    => 'Behance',
					'deviantart' => 'DeviantArt',
					'dribble'    => 'Dribble',
					'facebook'   => 'Facebook',
					'flickr'     => 'Flickr',
					'github'     => 'GitHub',
					'google'     => 'Google+',
					'instagram'  => 'Instagram',
					'steam'      => 'Steam',
					'twitch'     => 'Twitch',
					'twitter'    => 'Twitter',
					'youtube'    => 'Youtube'
				] as $name => $title)
				{
					if ($url = $this->config->{'nf_social_'.$name})
					{
						echo '<a class="nav-link" href="'.$url.'" data-toggle="tooltip" title="'.$title.'">'.icon('fab fa-'.$name).'</a>';
					}
				}
				?>
				<?php if ($this->user()): ?>
				<div class="nav-item">
					<?php echo $this->user->username ?>
				</div>
				<?php endif ?>
				<?php echo $this->user() ? '<a href="'.url('user/logout').'" class="nav-link">'.icon('fas fa-times').' Déconnexion'.'</a>' : '<a href="#" class="nav-link ml-5" data-modal-ajax="'.url('ajax/user/login').'">'.icon('fas fa-sign-in-alt').' Se connecter'.'</a>' ?>
			</nav>
		</div>
	</header>
	<main role="main" class="inner cover">
		<?php if ($page_title = $this->config->nf_maintenance_title): ?>
		<h1 class="cover-heading"><?php echo $page_title ?></h1>
		<?php endif ?>
		<?php if ($content = $this->config->nf_maintenance_content): ?>
		<p class="lead"><?php echo bbcode($content) ?></p>
		<?php endif ?>
		<?php if ($this->config->nf_maintenance_opening): ?>
			<div id="countdown" class="countdownHolder" data-timestamp="<?php echo $this->date($this->config->nf_maintenance_opening)->timestamp() ?>"></div>
		<?php endif ?>
	</main>
	<footer class="mastfoot mt-auto">
		<div class="inner text-left">
			<?php echo $this->widget('copyright')->output()->style('card-transparent') ?>
		</div>
	</footer>
</div>
