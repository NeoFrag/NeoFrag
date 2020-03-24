form.find('input[type="text"].autocomplete', function(){
	$(this).autocomplete({
		source: $(this).data('source'),
		html: true
	});
});
