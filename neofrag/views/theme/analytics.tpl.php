<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $this->config->nf_analytics ?>"></script>
<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());
	gtag('config', '<?php echo $this->config->nf_analytics ?>');
</script>
