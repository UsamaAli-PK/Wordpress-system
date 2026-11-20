/**
 * The offcanvas gallery repeater.
 *
 * Rows live in the DOM; the setting lives in one hidden input as JSON. Every
 * edit re-serialises the whole list and fires `change` on that input, which is
 * the event core's Element binding listens for — so the customizer learns about
 * a reordered row the same way it learns about a typed character.
 *
 * Rendering a new row is PHP's job too: the empty row is printed into a
 * text/html script tag by the control, so a row added here and a row loaded
 * from the database cannot drift apart.
 */
( function ( $ ) {
	'use strict';

	var l10n = window.helloAnimationRepeaterL10n || {};

	/**
	 * Walk the rows and write them back to the linked input.
	 *
	 * @param {jQuery} $repeater
	 */
	function serialize( $repeater ) {
		var rows = [];

		$repeater.find( '.ha-repeater__row' ).each( function () {
			var row = {};

			$( this ).find( '[data-key]' ).each( function () {
				row[ $( this ).attr( 'data-key' ) ] = $( this ).val();
			} );

			rows.push( row );
		} );

		$repeater
			.find( '.ha-repeater__value' )
			.val( JSON.stringify( rows ) )
			.trigger( 'change' );
	}

	/**
	 * Open the media library for one image field.
	 *
	 * A frame per click rather than one cached frame: two rows sharing a frame
	 * also share its selection, so the second row opens showing the first row's
	 * image already ticked.
	 *
	 * @param {jQuery} $field
	 * @param {jQuery} $repeater
	 */
	function pickImage( $field, $repeater ) {
		var frame = wp.media( {
			title: l10n.frameTitle || 'Select image',
			button: { text: l10n.frameButton || 'Use this image' },
			library: { type: 'image' },
			multiple: false
		} );

		frame.on( 'select', function () {
			var attachment = frame.state().get( 'selection' ).first().toJSON(),
				$preview = $field.find( '.ha-repeater__preview' );

			$field.find( '[data-key]' ).val( attachment.url );

			$preview
				.removeClass( 'is-empty' )
				.html( $( '<img />', { src: attachment.url, alt: '' } ) );

			serialize( $repeater );
		} );

		frame.open();
	}

	$( document ).on( 'click', '.ha-repeater__add', function ( event ) {
		event.preventDefault();

		var $repeater = $( this ).closest( '.ha-repeater' );

		$repeater.find( '.ha-repeater__rows' ).append(
			$repeater.find( '.ha-repeater__tpl' ).html()
		);

		// Not serialised yet on purpose — a row with no image is dropped by the
		// sanitizer anyway, so writing it now would only mark the customizer
		// dirty for a row that cannot be saved.
	} );

	$( document ).on( 'click', '.ha-repeater__remove', function ( event ) {
		event.preventDefault();

		var $repeater = $( this ).closest( '.ha-repeater' );

		$( this ).closest( '.ha-repeater__row' ).remove();

		serialize( $repeater );
	} );

	$( document ).on( 'click', '.ha-repeater__pick', function ( event ) {
		event.preventDefault();

		pickImage(
			$( this ).closest( '.ha-repeater__field' ),
			$( this ).closest( '.ha-repeater' )
		);
	} );

	$( document ).on( 'change keyup', '.ha-repeater__row [data-key]', function () {
		serialize( $( this ).closest( '.ha-repeater' ) );
	} );

	/**
	 * Sections render their controls lazily, so bind sortable when the section
	 * is expanded rather than on load — at load time the list has no height and
	 * jQuery UI measures its drop targets as zero.
	 */
	$( document ).on( 'click', '.accordion-section-title, .customize-section-back', function () {
		window.setTimeout( function () {
			$( '.ha-repeater__rows' ).each( function () {
				var $rows = $( this );

				if ( $rows.data( 'ha-sortable' ) ) {
					return;
				}

				$rows.data( 'ha-sortable', true ).sortable( {
					handle: '.ha-repeater__handle',
					axis: 'y',
					update: function () {
						serialize( $rows.closest( '.ha-repeater' ) );
					}
				} );
			} );
		}, 250 );
	} );
} )( jQuery );
