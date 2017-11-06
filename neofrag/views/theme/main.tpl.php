<?php $debug_bar = $this->debug->bar() ?>
<!DOCTYPE html>
<html lang="<?php echo $this->config->lang ?>">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0">
<meta content="IE=edge, chrome=1" http-equiv="X-UA-Compatible">
<?php if ($this->config->nf_theme_color): ?>
<meta name="theme-color" content="<?php echo $this->config->nf_theme_color ?>">
<?php endif ?>
<?php if ($this->config->nf_humans_txt): ?>
<link rel="author" href="<?php echo url('humans.txt') ?>" type="text/plain">
<?php endif ?>
<?php if (($module = $this->module('manifest')) && $module->is_enabled()): ?>
<link rel="manifest" href="<?php echo url('manifest.json') ?>">
<?php endif ?>
<link rel="apple-touch-icon-precomposed" sizes="57x57" href="<?php echo image('apple-touch-icon-57x57.png') ?>">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo image('apple-touch-icon-114x114.png') ?>">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo image('apple-touch-icon-72x72.png') ?>">
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo image('apple-touch-icon-144x144.png') ?>">
<link rel="apple-touch-icon-precomposed" sizes="120x120" href="<?php echo image('apple-touch-icon-120x120.png') ?>">
<link rel="apple-touch-icon-precomposed" sizes="152x152" href="<?php echo image('apple-touch-icon-152x152.png') ?>">
<link rel="icon" type="image/png" href="<?php echo image('favicon-32x32.png') ?>" sizes="32x32">
<link rel="icon" type="image/png" href="<?php echo image('favicon-16x16.png') ?>" sizes="16x16">
<link rel="shortcut icon" href="<?php echo image('favicon.ico') ?>" type="image/ico">
<?php echo $this->output->css() ?>
<title><?php echo $title ?></title>
</head>
<body>
<?php if ($this->config->nf_maintenance && !$this->url->admin && isset($this->user) && $this->user->admin && $this->output->module()->name != 'live_editor'): ?>
	<nav class="navbar no-margin bg-danger">
		<div class="container">
			<p class="navbar-text"><?php echo icon('fa-power-off').' '.$this->lang('Site en opération de maintenance') ?></p>
			<a href="<?php echo url('admin/settings/maintenance') ?>" class="btn btn-danger navbar-btn navbar-right"><?php echo $this->lang('Ouvrir le site') ?></a>
		</div>
	</nav>
<?php endif ?>
<?php echo $body ?>
<?php echo $debug_bar ?>
<?php echo $this->output->js() ?>
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

	<?php echo $this->output->js_load() ?>
});
<?php echo $this->config->nf_analytics ?>
</script>
</body>
</html>
