$(function(){
	$modal = $('#c2dac90bb0731401a293d27ee036757a');

	$modal.find('.panel-group .panel-title input[type="checkbox"]').click(function(){
		var checked = this.checked || this.indeterminate;
		$(this).parents('.panel:first').find('.panel-collapse input[type="checkbox"]').filter(function(){
			return checked != this.checked;
		}).trigger('click');
	});

	$modal.find('.panel-collapse input[type="checkbox"]').click(function(){
		var $checkbox = $(this);

		$modal.find('.panel-collapse input[type="checkbox"][value="'+$checkbox.val()+'"]').each(function(){
			if (this != $checkbox[0]){
				$(this).prop('checked', $checkbox[0].checked);
			}

			var checked = total = 0;

			$(this).parents('.panel:first').find('.panel-collapse input[type="checkbox"]').each(function(){
				total ++;

				if (this.checked){
					checked++;
				}
			});

			var $title_checkbox = $(this).parents('.panel:first').find('.panel-title input[type="checkbox"]');

			if (!checked){
				$title_checkbox.prop('checked', false);
				$title_checkbox.prop('indeterminate', false);
			}
			else if (checked == total){
				$title_checkbox.prop('checked', true);
				$title_checkbox.prop('indeterminate', false);
			}
			else{
				$title_checkbox.prop('checked', false);
				$title_checkbox.prop('indeterminate', true);
			}
		});
	});
});