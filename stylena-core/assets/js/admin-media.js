/* global jQuery, wp, stylenaCoreMedia */
( function ( $ ) {
	'use strict';

	var i18n = window.stylenaCoreMedia || { title: 'Select Image', button: 'Use this image' };

	$( document ).on( 'click', '.stylena-core-image-select', function ( e ) {
		e.preventDefault();
		var $field = $( this ).closest( '.stylena-core-image-field' );
		var frame = wp.media( {
			title: i18n.title,
			button: { text: i18n.button },
			library: { type: 'image' },
			multiple: false
		} );

		frame.on( 'select', function () {
			var attachment = frame.state().get( 'selection' ).first().toJSON();
			var url = attachment.sizes && attachment.sizes.medium ? attachment.sizes.medium.url : attachment.url;
			var $img = $( '<img>' ).attr( 'src', url ).attr( 'alt', '' ).css( { maxWidth: '200px', height: 'auto' } );
			$field.find( 'input[type="hidden"]' ).val( attachment.id );
			$field.find( '.stylena-core-image-preview' ).empty().append( $img );
		} );

		frame.open();
	} );

	$( document ).on( 'click', '.stylena-core-image-remove', function ( e ) {
		e.preventDefault();
		var $field = $( this ).closest( '.stylena-core-image-field' );
		$field.find( 'input[type="hidden"]' ).val( '' );
		$field.find( '.stylena-core-image-preview' ).empty();
	} );
}( jQuery ) );
