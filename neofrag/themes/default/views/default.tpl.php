<!DOCTYPE html>
<html lang="<?php echo $data['lang']; ?>">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0" />
<meta content="IE=edge, chrome=1" http-equiv="X-UA-Compatible" />
<link rel="author" href="<?php echo url('humans.txt'); ?>" type="text/plain" />
<link rel="shortcut icon" href="<?php echo image('favicon.png'); ?>" type="image/png" />
<link rel="stylesheet" href="<?php echo css('bootstrap.min.css'); ?>" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo css('font-awesome.min.css'); ?>" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo css('font-awesome-override.css'); ?>" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo css('default.css'); ?>" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo css('neofrag.notify.css'); ?>" type="text/css" media="screen" />
<?php echo $data['css']; ?>
<script type="text/javascript" src="<?php echo js('jquery-1.11.2.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo js('jquery-ui.min.js'); ?>"></script>
<title><?php echo $data['page_title']; ?></title>
</head>
<body>
	<?php if ($this->config->nf_maintenance && !$this->url->admin && isset($this->user) && $this->user('admin') && NeoFrag()->module->name != 'live_editor'): ?>
		<nav class="navbar no-margin bg-danger">
			<div class="container">
				<p class="navbar-text"><?php echo icon('fa-power-off').' '.$this->lang('website_down_for_maintenance'); ?></p>
				<a href="<?php echo url('admin/settings/maintenance'); ?>" class="btn btn-danger navbar-btn navbar-right"><?php echo $this->lang('open_website'); ?></a>
			</div>
		</nav>
	<?php endif; ?>
	<?php echo $data['body']; ?>
	<script type="text/javascript" src="<?php echo js('bootstrap.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo js('bootstrap-notify.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo js('neofrag.notify.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo js('neofrag.user.js'); ?>"></script>
	<?php echo $data['js']; ?>
	<script type="text/javascript">
	$(function(){
		$('body').trigger('nf.load');

		$('body').popover({
			selector: '[data-toggle=popover]',
			container: 'body',
			trigger: 'hover'
		});
		
		$('body').tooltip({
			selector: '[data-toggle=tooltip]'
		});
		
		<?php echo $data['js_load']; ?>
	});
	<?php echo $this->config->nf_analytics; ?>
	</script>
</body>
</html>