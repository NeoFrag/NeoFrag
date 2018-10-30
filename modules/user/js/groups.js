$(function(){
	$('ul.groups input[type=checkbox]').change(function(){
		if ($(this).prop('value') == 'admins' || $(this).prop('value') == 'members'){
			other = $(this).prop('value') == 'admins' ? $('ul.groups input[type=checkbox][value=members]') : $('ul.groups input[type=checkbox][value=admins]');

			if ($(this).prop('checked')){
				other.prop('checked', false);
			}
			else{
				other.prop('checked', 'checked');
			}
		}
	});
});
