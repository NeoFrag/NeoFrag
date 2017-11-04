$(function(){
	$('body').on('click', '.editor-bbcode-html .editor-buttons .btn.btn-default', function(){
		$(this).siblings().removeClass('btn-primary').addClass('btn-default');
		$(this).removeClass('btn-default').addClass('btn-primary');

		var $parent = $(this).parents('.editor-bbcode-html:first');
		console.log($parent.find('.wysibb, .textarea textarea').parent());
		$parent.find('.wysibb, .textarea textarea').parent().toggleClass('hidden');
		$parent.find('input[type="hidden"]').val($(this).data('type'));
	});
});
