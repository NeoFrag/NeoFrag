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
<?php echo $data['css']; ?>
<script type="text/javascript" src="<?php echo js('jquery-1.11.2.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo js('jquery-ui.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo js('neofrag.noscript.js'); ?>"></script>
<noscript><meta http-equiv="refresh" content="0; URL=<?php echo url('noscript.html'); ?>" /></noscript>
<title><?php echo $data['page_title']; ?></title>
</head>
<body>
	<?php echo $data[$NeoFrag->module->name == 'live_editor' ? 'module' : 'body']; ?>
	<script type="text/javascript" src="<?php echo js('bootstrap.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo js('neofrag.user.js'); ?>"></script>
	<?php echo $data['js']; ?>
	<script type="text/javascript">
	$(function(){
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
	<?php echo $NeoFrag->config->nf_analytics; ?>
	</script>
</body>
</html>