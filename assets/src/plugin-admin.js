import './styles/admin.scss';

/**
 * A void function.
 *
 * @param {jQuery} $ The jQuery object to be used in the function body
 */
( ( $ ) => {
	'use strict';
	$( () => {
		// If #_pet_data_special-needs is not checked, hide .cmb2-id--pet-data-special-needs-description
		if ( ! $( '#_pet_data_special-needs' ).is( ':checked' ) ) {
			$( '.cmb2-id--pet-data-special-needs-description' ).hide();
		}

		$( '#_pet_data_special-needs' ).on( 'change', function () {
			// Place your administration-specific JavaScript here
			$( '.cmb2-id--pet-data-special-needs-description' ).toggle();
		} );
	} );
} )( jQuery );
