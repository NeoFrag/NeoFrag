$(function(){

	var history = [];

	update = function(jqxhr){

		output = jqxhr.responseText;

		if (output != ''){

			$('#catalog .catalog-loader').nextAll().remove();
			$('#catalog .catalog-loader').after(output).fadeOut();
		}
		else {

			$('#catalog .catalog-loader').html('<h4>Une erreur est survenue, essayez de recharger la page à nouveau</h4>');
		}
	}

	refresh = function(url){

		if (history[history.length-1] == url){

			return;
		}
		else {

			history.push(url);
		}

		var output = '';

		$('#catalog .catalog-loader').show();

		if ($.browser.msie && window.XDomainRequest){

			var xdr = new XDomainRequest();
			xdr.open('POST', url);
			xdr.onload = function(){

				update(xdr.responseText);
			};
			xdr.send('lang={config lang}');
		} else {

			$.ajax({
				url: url,
				type: 'POST',
				data: 'lang={config lang}',
				complete: update
			});
		}
	};

	refresh('http://catalog.neofrag.com/');
});