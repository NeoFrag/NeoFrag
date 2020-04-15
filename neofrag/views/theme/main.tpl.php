<!DOCTYPE html>
<html lang="<?php echo $this->config->lang->info()->name ?>">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0">
<meta content="IE=edge, chrome=1" http-equiv="X-UA-Compatible">
<?php if ($this->config->nf_theme_color): ?>
<meta name="theme-color" content="<?php echo $this->config->nf_theme_color ?>">
<?php endif ?>
<?php if ($this->config->nf_analytics) echo $this->view('theme/analytics') ?>
<?php if ($this->config->nf_humans_txt): ?>
<link rel="author" href="<?php echo url('humans.txt') ?>" type="text/plain">
<?php endif ?>
<link rel="shortcut icon" href="<?php echo $path = ($this->config->nf_favicon && ($favicon = NeoFrag()->model2('file', $this->config->nf_favicon)->path())) ? $favicon : image('favicon.png') ?>" type="<?php echo get_mime_by_extension(extension($path)) ?>">
<?php echo $this->output->css() ?>
<?php foreach ($this->config->langs as $lang): ?>
<link rel="alternate" href="<?php echo $this->url->base.implode('/', array_merge([$lang->info()->name], $this->url->segments)).$this->url->query ?>" hreflang="<?php echo $lang->info()->name ?>">
<?php endforeach ?>
<title><?php echo $title ?></title>
</head>
<body>
<?php if ($this->config->nf_maintenance && !$this->url->admin && isset($this->user) && $this->user->admin && $this->output->module()->name != 'live_editor'): ?>
	<div class="bg-danger py-2">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-6 text-white"><?php echo icon('fas fa-power-off').' '.$this->lang('Site en opération de maintenance') ?></div>
				<div class="col-6 text-right"><a href="<?php echo url('admin/settings/maintenance') ?>" class="btn btn-outline-light"><?php echo $this->lang('Ouvrir le site') ?></a></div>
			</div>
		</div>
	</div>
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
</script>
</body>
</html>
