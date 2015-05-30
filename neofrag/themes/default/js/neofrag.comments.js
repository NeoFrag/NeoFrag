$(function(){
	$('[id^=comment-] a.comment-reply').click(function(){
		$('input[type="hidden"][name$="[comment_id]"]').val($(this).data('comment-id'));
		$('label[for$="[comment]"]').html('Votre réponse');
		$('textarea[name$="[comment]"]').focus();
		return false;
	});
});