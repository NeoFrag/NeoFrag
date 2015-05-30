<!DOCTYPE html>
<html lang="{lang}">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="{css bootstrap.min.css}" type="text/css" media="screen" />
<link rel="stylesheet" href="{css style.css}" type="text/css" media="screen" />
<link rel="stylesheet" href="{css default.css}" type="text/css" media="screen" />
<link rel="stylesheet" href="{css bootstrap.min.css}" type="text/css" media="print" />
<link rel="stylesheet" href="{css print.css}" type="text/css" media="print" />
<script type="text/javascript" src="{js jquery-1.8.2.min.js}"></script>
<script type="text/javascript" src="{js bootstrap.min.js}"></script>
<script type="text/javascript">
$(function(){
	$('[rel=tooltip]').tooltip();
	$('[rel=popover]').popover();
});
</script>
</head>
<body>
	{view emails/body}
</body>
</html>