<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="<?php echo css('bootstrap.min.css'); ?>" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo css('style.css'); ?>" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo css('default.css'); ?>" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo css('bootstrap.min.css'); ?>" type="text/css" media="print" />
<link rel="stylesheet" href="<?php echo css('print.css'); ?>" type="text/css" media="print" />
<script type="text/javascript" src="<?php echo js('jquery-1.8.2.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo js('bootstrap.min.js'); ?>"></script>
<script type="text/javascript">
$(function(){
	$('[rel=tooltip]').tooltip();
	$('[rel=popover]').popover();
});
</script>
</head>
<body>
	<?php echo $this->view('emails/body', $data); ?>
</body>
</html>