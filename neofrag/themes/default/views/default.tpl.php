<!DOCTYPE html>
<html lang="{lang}">
<head>

<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0" />
<meta content="IE=edge, chrome=1" http-equiv="X-UA-Compatible" />

<link rel="author" href="{base_url}humans.txt" type="text/plain" />

<link rel="shortcut icon" href="{image favicon.png}" type="image/png" />

<link rel="stylesheet" href="{css bootstrap.min.css}" type="text/css" media="screen" />
<link rel="stylesheet" href="{css font-awesome.min.css}" type="text/css" media="screen" />
<link rel="stylesheet" href="{css font-awesome-override.css}" type="text/css" media="screen" />
<link rel="stylesheet" href="{css default.css}" type="text/css" media="screen" />
{css}

<script type="text/javascript" src="{js jquery-1.11.2.min.js}"></script>
<script type="text/javascript" src="{js jquery-ui.min.js}"></script>
<script type="text/javascript" src="{js neofrag.noscript.js}"></script>
<noscript><meta http-equiv="refresh" content="0; URL={base_url}noscript.html" /></noscript>
<title>{page_title}</title>
</head>
<body>
	<?php if ($NeoFrag->module->get_name() == 'live_editor'): ?>
	{module}
	<?php else: ?>
	{view body}
	<?php endif; ?>
	
	<script type="text/javascript" src="{js bootstrap.min.js}"></script>
	{js}
	
	<script type="text/javascript">
	$(function(){
		$('body').popover({
			selector: '[data-toggle=popover]'
		});
		
		$('body').tooltip({
			selector: '[data-toggle=tooltip]',
			container: 'body'
		});
		
		{js_load}
	});
	<?php echo $NeoFrag->config->nf_analytics; ?>
	</script>
	
</body>
</html>