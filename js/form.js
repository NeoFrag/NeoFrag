var form = new function(){
	var _forms = [];
	var _load = {};

	this.load = function($form, force){
		if (typeof force != 'undefined' && force){
			load = true;
		} else if ($.inArray($form, _forms) == -1){
			_forms.push($form);
			load = true;
		}

		if (load){
			$.each(_load, function(find, callback){
				$form.find(find).each(function(){
					callback.apply(this, [$form]);
				});
			});
		}
	};

	this.submit = function($form){
		var d = $.Deferred();

		$.ajax({
			url: $form[0].action,
			type: $form[0].method,
			data: new FormData($form[0]),
			processData: false,
			contentType: false,
			success: modal.exec(function(data){
				if (typeof data.form != 'undefined'){
					$form.find('.modal-body').html(data.form);
					form.load($form, true);
				}

				d.resolve(data);
			})
		});


		return d.promise();
	};

	this.find = function(find, callback){
		_load[find] = callback;
	};

	return this;
};

$(function(){
	$('form').each(function(){
		form.load($(this));
	});
});
