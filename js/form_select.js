form.find('select.selectize', function(){
	var data = {};

	if ($(this).data('options')){
		data.options = $(this).data('options');
		data.valueField = 0;
		data.searchField = 1;
	}

	if ($(this).data('search-field')){
		data.searchField = $(this).data('search-field');
	}

	if ($(this).data('optgroups')){
		data.optgroups = $(this).data('optgroups');
		data.optgroupValueField = 0;
		data.optgroupField = 1;
	}

	if ($(this).data('optgroup-field')){
		data.optgroupField = $(this).data('optgroup-field');
	}

	if ($(this).data('render-option') || $(this).data('render-optgroup')){
		data.render = {};
	}

	var render = $(this).data('render-option') ? $(this).data('render-option') : '\'+escape(data[1])+\'';

	data.render = {};

	$.each(['option', 'item'], function(i, value){
		data.render[value] = new Function('data', 'escape', 'return \'<div class="'+value+'">'+render+'</div>\';');
	});

	data.render.optgroup_header = new Function('data', 'escape', 'return \'<div class="optgroup-header">'+($(this).data('render-optgroup') ? $(this).data('render-optgroup') : '\'+escape(data[1])+\'')+'</div>\';');

	if ($(this).data('placeholder')){
		data.placeholder = $(this).data('placeholder');
	}

	if (typeof $(this).data('value') != 'undefined'){
		data.items = String($(this).data('value')).split(',');
	}

	if ($(this).attr('multiple')){
		data.plugins = ['remove_button'];
	}

	$(this).selectize(data);
});
