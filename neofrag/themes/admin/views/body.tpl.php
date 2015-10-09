<div id="wrapper">
	<nav class="navbar navbar-default navbar-static-top" style="margin-bottom: 0">
		<a href="<?php echo url(); ?>" class="btn btn-default back-to visible-xs"><?php echo icon('fa-sign-out'); ?></a>
		<a href="#" class="btn btn-default touch-menu visible-xs"><?php echo icon('fa-bars'); ?></a>
		<div class="navbar-header">
			<a class="navbar-brand" href="<?php echo url('admin.html'); ?>"><b>NeoFrag</b> CMS<span class="nf-version"><?php echo NEOFRAG_VERSION.($NeoFrag->config->nf_pro ? ' Pro' : ''); ?></span></a>
		</div>
		<div class="collapse navbar-collapse" id="main-navbar-collapse-1">
			<ul class="nav nav navbar-nav navbar-left">
				<li><a href="#" class="toggle-menu" data-toggle="sidebar"><?php echo icon('fa-bars'); ?></a></li>
				<li><a href="//www.neofrag.com/support.html"><?php echo icon('fa-support'); ?><span class="hidden-xs hidden-sm"> <?php echo i18n('support'); ?></span></a></li>
				<li><a href="//www.neofrag.com/forum.html"><?php echo icon('fa-comment'); ?><span class="hidden-xs hidden-sm"> <?php echo i18n('forum'); ?></span></a></li>
				<li><a href="//www.neofrag.com/download.html"><?php echo icon('fa-download'); ?><span class="hidden-xs hidden-sm"> <?php echo i18n('downloads'); ?></span></a></li>
				<li><a href="//www.neofrag.com/documentation.html"><?php echo icon('fa-list-alt'); ?><span class="hidden-xs hidden-sm"> <?php echo i18n('documentation'); ?></span></a></li>
			</ul>
			<a class="btn btn-default navbar-btn navbar-right" href="<?php echo url(); ?>"><?php echo icon('fa-sign-out'); ?><span class="hidden-xs hidden-sm"> <?php echo i18n('back_front'); ?></span></a>
		</div>
	</nav>
	<nav class="navbar-default sidebar">
		<div class="sidebar-nav navbar-collapse">
			<ul class="nav" id="side-menu">
				<li class="sidebar-user">
					<div class="row">
						<div class="col-md-3 col-xs-12">
							<img class="img-circle" src="<?php echo $NeoFrag->user->avatar(); ?>" alt="" />
						</div>
						<div class="col-md-9 col-xs-12">
							<span class="user-name"><b><?php echo $NeoFrag->user('username'); ?></b></span>
						</div>
						<div class="col-md-9 col-xs-12">
							<div class="btn-group">
								<button type="button" class="btn btn-user btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
									<?php echo icon('fa-user').' '.icon('fa-angle-down'); ?>
								</button>
								<ul class="dropdown-menu" role="menu">
									<li><a href="<?php echo url('user.html'); ?>"><?php echo icon('fa-user').i18n('my_account'); ?></a></li>
									<li><a href="<?php echo url('user/edit.html'); ?>"><?php echo icon('fa-cogs').i18n('edit_account'); ?></a></li>
									<li><a href="<?php echo url('members/'.$NeoFrag->user('user_id').'/'.url_title($NeoFrag->user('username')).'.html'); ?>"><?php echo icon('fa-eye').i18n('view_my_profile'); ?></a></li>
								</ul>
							</div>
							<!--<div class="btn-group">
								<button type="button" class="btn btn-user btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
									<span class="alert-badge">3</span>
									<?php echo icon('fa-envelope-o').' '.icon('fa-angle-down'); ?>
								</button>
								<ul class="dropdown-menu" role="menu">
									<li><a href="#"><?php echo icon('fa-inbox'); ?>Boîte de réception</a></li>
									<li><a href="#"><?php echo icon('fa-sign-out'); ?>Messages envoyés</a></li>
									<li><a href="#"><?php echo icon('fa-file-text-o'); ?>Rédiger</a></li>
								</ul>
							</div>-->
							<a href="<?php echo url('user/logout.html'); ?>" class="btn btn-user-logout" ><?php echo icon('fa-close'); ?></a>
						</div>
					</div>
				</li>
<?php
	$actives  = array();

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

				$submenu .= '<li><a'.(!empty($class) ? ' class="'.implode(' ', $class).'"' : '').' href="'.url($sublink['url']).'">'.icon($sublink['icon']).$sublink['title'].'</a></li>';
			}

			echo '<li'.($active ? ' class="active"' : '').'><a data-toggle="collapse" href="#menu_'.url_title($link['title']).'">'.icon($link['icon']).' <span class="hidden-xs">'.$link['title'].'</span><span class="fa arrow"></span></a><ul class="nav nav-second-level'.(!$active ? ' collapse' : '').'">'.$submenu.'</ul></li>';
		}
		else
		{
			echo '<li><a'.($NeoFrag->config->request_url == $link['url'] ? ' class="active"' : '').' href="'.url($link['url']).'">'.icon($link['icon']).' <span class="hidden-xs">'.$link['title'].'</span></a></li>';
		}
	}
?>
			</ul>
		</div>
	</nav>
	<div id="page-wrapper">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header"><?php echo $NeoFrag->output->data['module_title']; ?> <small><?php echo $NeoFrag->output->data['module_subtitle']; ?></small></h1>
				<div class="page-actions pull-right">
					<?php if ($data['module_method'] == 'index' && $NeoFrag->module->get_access('default')): ?>
						<a class="btn btn-outline btn-success btn-sm" href="<?php echo url('admin/access/'.$NeoFrag->module->name.'.html'); ?>"><?php echo icon('fa-unlock-alt'); ?><span class="hidden-sm"> Permissions</span></a>
					<?php endif; ?>
					
					<!--<a class="btn btn-outline btn-warning btn-sm"><?php echo icon('fa-wrench'); ?><span class="hidden-sm"> Configuration</span></a> -->
					
					<?php if (!is_null($help = $NeoFrag->module->load->controller('admin_help')) && method_exists($help, $data['module_method'])): ?>
					<?php NeoFrag::loader()->js('neofrag.help'); ?>
					<a class="btn btn-outline btn-info btn-sm" href="<?php echo url($NeoFrag->config->request_url); ?>" data-help="<?php echo 'admin/help/'.$NeoFrag->module->name.'/'.$data['module_method'].'.html'; ?>"><?php echo icon('fa-life-bouy'); ?><span class="hidden-sm"> <?php echo i18n('help'); ?></span></a>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<div class="row">
			<div id="alerts"></div>
			<?php echo $loader->view('actions', $data); ?>
		</div>
		<?php echo $NeoFrag->output->data['module']; ?>
	</div>
</div>