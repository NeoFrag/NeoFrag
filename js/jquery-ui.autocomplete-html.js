/*
 * jQuery UI Autocomplete HTML Extension
 *
 * Copyright 2010, Scott González (http://scottgonzalez.com)
 * Dual licensed under the MIT or GPL Version 2 licenses.
 *
 * http://github.com/scottgonzalez/jquery-ui-extensions
 */
(function( $ ) {

var proto = $.ui.autocomplete.prototype,
	initSource = proto._initSource,
	value = proto._value;

function filter( array, term ) {
	var matcher = new RegExp( $.ui.autocomplete.escapeRegex(term), "i" );
	return $.grep( array, function(value) {
		return matcher.test( $( "<div>" ).html( value.label || value.value || value ).text() );
	});
}

$.extend( proto, {
	_initSource: function() {
		if ( this.options.html && $.isArray(this.options.source) ) {
			this.source = function( request, response ) {
				response( filter( this.options.source, request.term ) );
			};
		} else {
			initSource.call( this );
		}
	},

	_value: function() {
		if ( typeof arguments[0] != 'undefined' && this.options.html ) {
				arguments[0] = $('<div/>').html(arguments[0]).text();
		}

		return value.apply( this, arguments );
	},

	_renderItem: function( ul, item ) {
		return $( "<li>" )
			.append( $( "<div>" )[ this.options.html ? "html" : "text" ]( item.label ) )
			.appendTo( ul );
	}
});

})( jQuery );
