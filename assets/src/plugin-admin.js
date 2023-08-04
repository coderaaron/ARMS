import './styles/admin.scss';

/**
 * A void function.
 *
 * @param {jQuery} $ The jQuery object to be used in the function body
 */
( ( $ ) => {
	'use strict';
	$( () => {} );
} )( jQuery );

// When the value of _arms_pet_data_pet-type changes, fetch a list of the child terms
// and populate the _arms_pet_data_pet-breed select field.
// Use the WordPress BackboneJS library to make the AJAX request.
if ( wp.apiFetch ) {
	// If _arms_pet_data_pet-type is equal to cat, change the label for _arms_pet_data_housebroken to Litter Trained.
	const petType = document.getElementById( '_arms_pet_data_pet-type' );
	const housebroken = document.getElementById( '_arms_pet_data_housebroken' );

	const changeHousebrokenLabel = () => {
		if ( petType?.options[ petType.selectedIndex ].value === 'cat' ) {
			housebroken.labels[ 0 ].innerText = 'Litter Trained';
		} else {
			housebroken.labels[ 0 ].innerText = 'Housebroken';
		}
	};

	petType?.addEventListener( 'change', changeHousebrokenLabel );
	changeHousebrokenLabel();
}

wp.domReady( () => {
	// Only allow the input named _arms_pet_data_primary-cat-breed to have at most two values selected
	// at any given time.
	const primaryBreeds = document.querySelectorAll(
		'.breed-select .cmb2-option'
	);

	// get the number of selected options
	let selectedOptions = 0;

	const changePetType = () => {
		selectedOptions = 0;
		primaryBreeds?.forEach( ( primaryBreed ) => {
			primaryBreed.checked = false;
		} );
	};

	if ( primaryBreeds?.length > 0 ) {
		primaryBreeds?.forEach( ( primaryBreed ) => {
			selectedOptions += primaryBreed.checked ? 1 : 0;
			primaryBreed.addEventListener( 'change', ( e ) => {
				selectedOptions += 2 * e.target.checked - 1;
				if ( selectedOptions > 2 ) {
					e.target.checked = false;
					selectedOptions -= 1;
				}
			} );
		} );
	}

	const petType = document.getElementById( '_arms_pet_data_pet-type' );
	petType?.addEventListener( 'change', changePetType );
} );
