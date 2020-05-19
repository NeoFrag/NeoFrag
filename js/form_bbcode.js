form.find('textarea.editor', function(){
	$(this).wysibb({lang: "<?php echo $this->config->lang->info()->name ?>"});
});
