<textarea class="form-control editor" name="settings[content]" rows="6"><?php if (isset($data['content'])) echo $data['content']; ?></textarea>
<script type="text/javascript" src="<?php echo js('jquery.wysibb.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo js('jquery.wysibb.fr.js'); ?>"></script>
<script type="text/javascript">
	$(function(){
		$('<link rel="stylesheet" href="<?php echo css('wbbtheme.css'); ?>" type="text/css" media="screen" />').appendTo('head');
		$('#live-editor-settings-form textarea.editor').wysibb({lang: "fr"});
		
		$('#live-editor-settings-form').on('nf.live-editor-settings.submit', function(){
			if ($('#live-editor-settings-form textarea.editor').length){
				$('#live-editor-settings-form textarea.editor').sync();
			}
		});
	});
</script>
