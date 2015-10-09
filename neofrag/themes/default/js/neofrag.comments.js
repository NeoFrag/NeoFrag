$(function(){
	$('[id^=comment-] a.comment-reply').click(function(){
		$('input[type="hidden"][name$="[comment_id]"]').val($(this).data('comment-id'));
		$('label[for$="[comment]"]').html('<?php echo i18n('your_response'); ?>');
		$('textarea[name$="[comment]"]').focus();
		return false;
	});
});