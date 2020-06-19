$(function(){
	$('body').on('click', '.editor-bbcode-html .editor-buttons .btn.btn-secondary', function(){
		$(this).siblings().removeClass('btn-primary').addClass('btn-secondary');
		$(this).removeClass('btn-secondary').addClass('btn-primary');

		var $parent = $(this).parents('.editor-bbcode-html:first');
		$parent.find('.wysibb, .textarea textarea').parent().toggleClass('hidden');
		$parent.find('input[type="hidden"]').val($(this).data('type'));
	});
});
