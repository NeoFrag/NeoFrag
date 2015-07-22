<div id="wrapper">
	<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
		<div class="navbar-header">
			<a class="navbar-brand" href="{base_url}admin.html">NEOFRAG CMS <small><?php echo NEOFRAG_VERSION.($NeoFrag->config->nf_pro ? ' Pro' : ''); ?></small></a>
		</div>
		<div class="collapse navbar-collapse" id="main-navbar-collapse-1">
			<ul class="nav nav navbar-nav navbar-left">
				<li><a href="#" class="toggle-menu" data-toggle="sidebar"><i class="fa fa-bars"></i></a></li>
				<li><a href="//www.neofrag.com/support.html">{fa-icon support}<span class="hidden-xs hidden-sm"> Support</span></a></li>
				<li><a href="//www.neofrag.com/forum.html">{fa-icon comment}<span class="hidden-xs hidden-sm"> Forum</span></a></li>
				<li><a href="//www.neofrag.com/download.html">{fa-icon download}<span class="hidden-xs hidden-sm"> Téléchargements</span></a></li>
				<li><a href="//www.neofrag.com/documentation.html">{fa-icon list-alt}<span class="hidden-xs hidden-sm"> Documentation</span></a></li>
			</ul>
			<a class="btn btn-default navbar-btn navbar-right" href="{base_url}"><i class="fa fa-sign-out"></i><span class="hidden-xs hidden-sm"> Retour sur le site</span></a>
		</div>
		<div class="navbar-default sidebar" role="navigation">
			<div class="sidebar-nav navbar-collapse">
				<ul class="nav" id="side-menu">
					<li class="sidebar-user">
						<div class="row">
							<div class="col-md-3 col-xs-12">
								<img class="img-circle" src="<?php echo $NeoFrag->user->avatar(); ?>" alt="" />
							</div>
							<div class="col-md-9 col-xs-12">
								<span class="user-name"><b>{user username}</b></span>
							</div>
							<div class="col-md-9 col-xs-12">
								<div class="btn-group">
									<button type="button" class="btn btn-user btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
										{fa-icon user} {fa-icon angle-down}
									</button>
									<ul class="dropdown-menu" role="menu">
										<li><a href="{base_url}user.html"><i class="fa fa-user"></i>Mon espace</a></li>
										<li><a href="{base_url}user/edit.html"><i class="fa fa-cogs"></i>Gérer mon compte</a></li>
										<li><a href="{base_url}members/<?php echo $NeoFrag->user('user_id'); ?>/<?php echo url_title($NeoFrag->user('username')); ?>.html"><i class="fa fa-eye"></i>Voir mon profil</a></li>
									</ul>
								</div>
								<!--<div class="btn-group">
									<button type="button" class="btn btn-user btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
										<span class="alert-badge">3</span>
										{fa-icon envelope-o} {fa-icon angle-down}
									</button>
									<ul class="dropdown-menu" role="menu">
										<li><a href="#"><i class="fa fa-inbox"></i>Boîte de réception</a></li>
										<li><a href="#"><i class="fa fa-sign-out"></i>Messages envoyés</a></li>
										<li><a href="#"><i class="fa fa-file-text-o"></i>Rédiger</a></li>
									</ul>
								</div>-->
								<a href="{base_url}user/logout.html" class="btn btn-user-logout" >{fa-icon close}</a>
							</div>
						</div>
					</li>
<?php
	$actives  = array();
	$base_url = $NeoFrag->config->base_url;

	foreach ($data['menu'] as $link)
	{
		if (is_array($link['url']))
		{
			foreach ($link['url'] as $sublink)
			{
				if (preg_match('#^'.substr($sublink['url'], 0, -5).'(?:\.|/)#', $NeoFrag->config->request_url))
				{
					$actives[] = $sublink['url'];
				}
			}
		}
	}

	usort($actives, create_function('$a, $b', 'return strlen($a) < strlen($b);'));

	foreach ($data['menu'] as $link)
	{
		if (is_array($link['url']))
		{
			$active  = FALSE;
			$submenu = '';
			foreach ($link['url'] as $sublink)
			{
				$class = array();

				if (!empty($sublink['pro']))
				{
					$class[] = 'forbidden';
				}

				if ($actives && $actives[0] == $sublink['url'])
				{
					$active  = TRUE;
					$class[] = 'active';
				}

				$submenu .= '<li><a'.(!empty($class) ? ' class="'.implode(' ', $class).'"' : '').' href="'.$base_url.$sublink['url'].'">'.$NeoFrag->assets->icon($sublink['icon']).$sublink['title'].'</a></li>';
			}

			echo '<li'.(($active) ? ' class="active"' : '').'><a data-toggle="collapse" href="#menu_'.url_title($link['title']).'">'.$NeoFrag->assets->icon($link['icon']).' <span class="hidden-xs">'.$link['title'].'</span><span class="fa arrow"></span></a><ul class="nav nav-second-level">'.$submenu.'</ul></li>';
		}
		else
		{
			echo '<li><a'.(($NeoFrag->config->request_url == $link['url']) ? ' class="active"' : '').' href="'.$base_url.$link['url'].'">'.$NeoFrag->assets->icon($link['icon']).' <span class="hidden-xs">'.$link['title'].'</span></a></li>';
		}
	}
?>
				</ul>
			</div>
		</div>
	</nav>
	<div id="page-wrapper">
		<div class="row">
			<div class="col-lg-12">
				<!-- //TODO -->
				<div class="pull-right" style="margin-top: 25px;">
					<!-- <a class="btn btn-outline btn-success btn-sm">{fa-icon unlock-alt} Permissions</a> 
					<a class="btn btn-outline btn-info btn-sm">{fa-icon wrench} Configuration</a> -->
					<?php
						foreach ($data['menu_tabs'] as $tab)
						{
							if (isset($tab['help']))
							{
								echo '<a class="btn btn-outline btn-warning btn-sm" href="'.$base_url.$tab['url'].'" data-help="'.$tab['help'].'">{fa-icon life-bouy} Aide</a> ';
							}
						}
					?>
					
				</div>
				<h1 class="page-header">{module_title} <small>{module_subtitle}</small></h1>
			</div>
		</div>
		<div class="row">
			<?php if ($data['menu_tabs']): ?>
				<nav class="navbar navbar-default box-shadow" role="navigation">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#main-navbar-collapse2">
							<span class="sr-only">Actions</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
					</div>
					<div class="collapse navbar-collapse" id="main-navbar-collapse2">
						<ul class="nav navbar-nav">
						<?php
							$actives = array();
							
							foreach ($data['menu_tabs'] as $tab)
							{
								if (strpos($NeoFrag->config->request_url, substr($tab['url'], 0, -5)) === 0 && !isset($tab['help']))
								{
									$actives[] = $tab['url'];
								}
							}

							usort($actives, create_function('$a, $b', 'return strlen($a) < strlen($b);'));

							foreach ($data['menu_tabs'] as $tab)
							{
								if (!isset($tab['help']))
								{
									echo '<li'.(($actives && $actives[0] == $tab['url']) ? ' class="active"' : '').'><a href="'.$base_url.$tab['url'].'"><img src="{image '.$tab['icon'].'}" alt="" /> <span class="hidden-phone">'.$tab['title'].'</span></a></li>';
								}
							}
						?>
						</ul>
					</div>
				</nav>
			<?php endif; ?>
			<div id="alerts"></div>
			{view actions}
		</div>
		{module}
	</div>
</div>