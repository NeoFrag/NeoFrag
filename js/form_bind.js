form.find('[data-bind]', function($form){
	$(this).change(function(){
		form.submit($form);
	});
});
