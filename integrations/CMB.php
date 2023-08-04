<?php

/**
 * ARMS
 *
 * @package   ARMS
 * @author    Aaron Graham <aaron@coderaaron.com>
 * @copyright 2023 Aaron Graham
 * @license   GPL 2.0+
 * @link      http://coderaaron.com
 */

namespace ARMS\Integrations;

use ARMS\Engine\Base;

/**
 * All the CMB related code.
 */
class CMB extends Base {

	/**
	 * Initialize class.
	 *
	 * @since 1.0.0
	 * @return void|bool
	 */
	public function initialize() {
		parent::initialize();

		require_once A_PLUGIN_ROOT . 'vendor/cmb2/init.php';
		require_once A_PLUGIN_ROOT . 'vendor/cmb2-grid/Cmb2GridPluginLoad.php';

		\add_action( 'cmb2_init', array( $this, 'arms_cmb_metaboxes' ) );
	}

	public function only_one_top_level_type() {
		// Get the current post taxonomy value for pet-type
		$pet_type = \wp_get_post_terms( \get_the_ID(), 'pet-type', array( 'parent' => 0 ) );

		// If the current post has more than one pet-type return false
		return \count( $pet_type ) > 1 ? false : true;
	}

	public function cmb2_get_breeds( $field ) {
		// Get the current post taxonomy value for pet-type
		$pet_type     = \wp_get_post_terms( \get_the_ID(), 'pet-type', array( 'parent' => 0 ) );
		$top_level_id = $pet_type[0]->term_id;
		// Get all of the pet-types with the parent $pet_type->term_id
		$breeds = \get_terms(
			array(
				'taxonomy'   => 'pet-type',
				'hide_empty' => false,
				'parent'     => $top_level_id,
			)
		);
		// Map breeds to an array of breed names with the key being the breed slug
		return \array_map(
			function ( $breed ) use ( $top_level_id ) {
				return $breed->name;
			},
			$breeds
		);
	}

	/**
	 * Your metabox on pet CPT
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function arms_cmb_metaboxes() { // phpcs:ignore
		// Start with an underscore to hide fields from custom fields list
		$prefix   = 'arms_user_data';
		$cmb_user = \new_cmb2_box(
			array(
				'id'           => $prefix . 'metabox',
				'title'        => \__( 'User Fields', A_TEXTDOMAIN ),
				'object_types' => array( 'user' ),
				'context'      => 'normal',
				'priority'     => 'high',
				'show_names'   => true, // Show field names on the left
			)
		);
		$cmb2Grid = new \Cmb2Grid\Grid\Cmb2Grid( $cmb_user ); //phpcs:ignore WordPress.NamingConventions

		$cmb_user->add_field(
			array(
				'id'   => $prefix . '_legacy-user-id',
				'name' => 'Legacy User ID',
				'type' => 'hidden',
			)
		);

		$address1     = $cmb_user->add_field(
			array(
				'id'   => $prefix . '_street',
				'name' => 'Street',
				'type' => 'text',
			)
		);
		$address2     = $cmb_user->add_field(
			array(
				'id'   => $prefix . '_city',
				'name' => 'City',
				'type' => 'text_medium',
			)
		);
		$address3     = $cmb_user->add_field(
			array(
				'id'   => $prefix . '_zip',
				'name' => 'ZIP',
				'type' => 'text_small',
			)
		);
		$address_row1 = $cmb2Grid->addRow(); //phpcs:ignore WordPress.NamingConventions
		$address_row1->addColumns(
			array(
				array(
					$address1,
					'class' => 'col-md-4',
				),
			),
		);
		$address_row2 = $cmb2Grid->addRow(); //phpcs:ignore WordPress.NamingConventions
		$address_row2->addColumns(
			array(
				array(
					$address2,
					'class' => 'col-md-3',
				),
				array(
					$address3,
					'class' => 'col-md-1',
				),
			),
		);

		$phone1    = $cmb_user->add_field(
			array(
				'id'         => $prefix . '_phone1',
				'name'       => 'Phone 1',
				'type'       => 'text_medium',
				'attributes' => array(
					'type' => 'tel',
				),
			)
		);
		$phone2    = $cmb_user->add_field(
			array(
				'id'         => $prefix . '_phone2',
				'name'       => 'Phone 2',
				'type'       => 'text_medium',
				'attributes' => array(
					'type' => 'tel',
				),
			)
		);
		$phone_row = $cmb2Grid->addRow(); //phpcs:ignore WordPress.NamingConventions
		$phone_row->addColumns(
			array(
				array(
					$phone1,
					'class' => 'col-md-2',
				),
				array(
					$phone2,
					'class' => 'col-md-2',
				),
			),
		);

		$agreements1    = $cmb_user->add_field(
			array(
				'id'   => $prefix . 'cat_foster_agreement',
				'name' => 'Cat Foster Agreement',
				'type' => 'checkbox',
			)
		);
		$agreements2    = $cmb_user->add_field(
			array(
				'id'   => $prefix . 'dog_foster_agreement',
				'name' => 'Dog Foster  Agreement',
				'type' => 'checkbox',
			)
		);
		$agreements3    = $cmb_user->add_field(
			array(
				'id'   => $prefix . 'minor_waiver',
				'name' => 'Minor Waiver',
				'type' => 'checkbox',
			)
		);
		$agreements4    = $cmb_user->add_field(
			array(
				'id'   => $prefix . 'ethics_agreement',
				'name' => 'Ethics Agreement',
				'type' => 'checkbox',
			)
		);
		$agreements_row = $cmb2Grid->addRow(); //phpcs:ignore WordPress.NamingConventions
		$agreements_row->addColumns(
			array(
				array(
					$agreements1,
					'class' => 'col-md-2',
				),
				array(
					$agreements2,
					'class' => 'col-md-2',
				),
				array(
					$agreements3,
					'class' => 'col-md-2',
				),
				array(
					$agreements4,
					'class' => 'col-md-2',
				),
			),
		);

		$mentor1    = $cmb_user->add_field(
			array(
				'name'    => 'Mentor',
				'desc'    => 'Start typing to see list of available mentors.',
				'id'      => 'mentor',
				'type'    => 'user_select_text',
				'options' => array(
					'user_roles' => array( 'volunteer' ), // Specify which roles to query for.
				),
			)
		);
		$mentor2    = $cmb_user->add_field(
			array(
				'name'    => 'Dog Mentor',
				'desc'    => 'Start typing to see list of available mentors.',
				'id'      => 'dog_mentor',
				'type'    => 'user_select_text',
				'options' => array(
					'user_roles' => array( 'volunteer' ), // Specify which roles to query for.
				),
			)
		);
		$mentor_row = $cmb2Grid->addRow(); //phpcs:ignore WordPress.NamingConventions
		$mentor_row->addColumns(
			array(
				array(
					$mentor1,
					'class' => 'col-md-3',
				),
				array(
					$mentor2,
					'class' => 'col-md-3',
				),
			),
		);

		// Put extra_jobs in User Description?
		// Are email_pref, confirmed, or date_added used?

		/* Pet Metaboxes */

		// Start with an underscore to hide fields from custom fields list
		$prefix   = '_arms_pet_data';
		$cmb_pet  = \new_cmb2_box(
			array(
				'id'           => $prefix . 'metabox',
				'title'        => \__( 'Pet Fields', A_TEXTDOMAIN ),
				'object_types' => array( 'pet' ),
				'context'      => 'normal',
				'priority'     => 'high',
				'show_names'   => true, // Show field names on the left
			)
		);
		$cmb2Grid = new \Cmb2Grid\Grid\Cmb2Grid( $cmb_pet ); //phpcs:ignore WordPress.NamingConventions

		$basics1 = $cmb_pet->add_field(
			array(
				'name'             => 'Pet Type',
				'show_option_none' => false,
				'id'               => $prefix . '_pet-type',
				'taxonomy'         => 'pet-type', //Enter Taxonomy Slug
				'type'             => 'taxonomy_select',
				'required'         => true,
				'default'          => 'cat',
				'remove_default'   => 'true', // Removes the default metabox provided by WP core.
				// Optionally override the args sent to the WordPress get_terms function.
				'query_args'       => array(
					'parent' => 0,
				),
			)
		);

		$basics2 = $cmb_pet->add_field(
			array(
				'id'   => $prefix . '_tag-number',
				'name' => 'Tag Number',
				'type' => 'text_small',
			)
		);

		$basics3 = $cmb_pet->add_field(
			array(
				'id'   => $prefix . '_date-of-birth',
				'name' => 'Date of Birth',
				'type' => 'text_date',
			)
		);

		$basics4 = $cmb_pet->add_field(
			array(
				'id'   => $prefix . '_adoption-fee',
				'name' => 'Adoption Fee',
				'type' => 'text_money',
			)
		);

		$basics_row = $cmb2Grid->addRow(); //phpcs:ignore WordPress.NamingConventions
		$basics_row->addColumns( array( $basics1, $basics2, $basics3, $basics4 ) );

		$mc1 = $cmb_pet->add_field(
			array(
				'id'   => $prefix . '_microchipped',
				'name' => 'Microchipped',
				'type' => 'checkbox',
			)
		);
		$mc2 = $cmb_pet->add_field(
			array(
				'id'         => $prefix . '_microchip-brand',
				'name'       => 'Microchip brand',
				'type'       => 'text',
				'attributes' => array(
					'data-conditional-id' => $prefix . '_microchipped',
					// Works too: 'data-conditional-value' => 'on'.
				),
			)
		);
		$mc3 = $cmb_pet->add_field(
			array(
				'id'         => $prefix . '_microchip-id',
				'name'       => 'Microchip ID',
				'type'       => 'text',
				'attributes' => array(
					'data-conditional-id' => $prefix . '_microchipped',
					// Works too: 'data-conditional-value' => 'on'.
				),
			)
		);
		$mc_row = $cmb2Grid->addRow(); //phpcs:ignore WordPress.NamingConventions
		$mc_row->addColumns(
			array(
				array(
					$mc1,
					'class' => 'col-md-2',
				),
				array(
					$mc2,
					'class' => 'col-md-3',
				),
				array(
					$mc3,
					'class' => 'col-md-3',
				),
			)
		);

		$cmb_pet->add_field(
			array(
				'name'              => 'Breed(s)',
				'before'            => function () {
					echo '<strong>Choose two at most.</strong>'; },
				'id'                => $prefix . '_primary-cat-breed',
				'classes'           => 'breed-select',
				'taxonomy'          => 'pet-type', //Enter Taxonomy Slug
				'type'              => 'taxonomy_multicheck',
				'select_all_button' => false,
				'remove_default'    => 'true', // Removes the default metabox provided by WP core.
				// Optionally override the args sent to the WordPress get_terms function.
				'query_args'        => array(
					'child_of' => \get_term_by( 'slug', 'cat', 'pet-type' )->term_id,
				),
				'attributes'        => array(
					'data-conditional-id'    => $prefix . '_pet-type',
					'data-conditional-value' => 'cat',
				),
			)
		);

		$cmb_pet->add_field(
			array(
				'name'              => 'Breed(s)',
				'id'                => $prefix . '_primary-dog-breed',
				'before'            => function () {
					echo '<strong>Choose two at most.</strong>'; },
				'classes'           => 'breed-select',
				'taxonomy'          => 'pet-type', //Enter Taxonomy Slug
				'type'              => 'taxonomy_multicheck',
				'select_all_button' => false,
				'remove_default'    => 'true', // Removes the default metabox provided by WP core.
				// Optionally override the args sent to the WordPress get_terms function.
				'query_args'        => array(
					'child_of' => \get_term_by( 'slug', 'dog', 'pet-type' )->term_id,
				),
				'attributes'        => array(
					'data-conditional-id'    => $prefix . '_pet-type',
					'data-conditional-value' => 'dog',
				),
			)
		);

		// Set mixed to true if there is a secondary breed
		/* $cmb_pet->add_field(
			array(
				'id'   => $prefix . '_mixed',
				'name' => 'Mixed',
				'type' => 'checkbox',
			)
		); */
		$cmb_pet->add_field(
			array(
				'id'      => $prefix . '_pet-size',
				'name'    => 'Pet Size',
				'type'    => 'select',
				'options' => array(
					'S'  => 'Small',
					'M'  => 'Medium',
					'L'  => 'Large',
					'XL' => 'Extra Large',
				),
			)
		);

		$prechecks1    = $cmb_pet->add_field(
			array(
				'id'   => $prefix . '_spayed-neutered',
				'name' => 'Spayed/Neutered',
				'type' => 'checkbox',
			)
		);
		$prechecks2    = $cmb_pet->add_field(
			array(
				'id'   => $prefix . '_shots-up-to-date',
				'name' => 'Shots up to date',
				'type' => 'checkbox',
			)
		);
		$prechecks3    = $cmb_pet->add_field(
			array(
				'id'   => $prefix . '_housebroken',
				'name' => 'Housebroken',
				'type' => 'checkbox',
			)
		);
		$prechecks4    = $cmb_pet->add_field(
			array(
				'id'         => $prefix . '_declawed',
				'name'       => 'Declawed',
				'type'       => 'checkbox',
				'attributes' => array(
					'data-conditional-id'    => $prefix . '_pet-type',
					'data-conditional-value' => 'cat',
				),
			)
		);
		$prechecks_row = $cmb2Grid->addRow(); //phpcs:ignore WordPress.NamingConventions
		$prechecks_row->addColumns( array( $prechecks1, $prechecks2, $prechecks3, $prechecks4 ) );

		$no1    = $cmb_pet->add_field(
			array(
				'id'   => $prefix . '_no-cats',
				'name' => 'No Cats',
				'type' => 'checkbox',
			)
		);
		$no2    = $cmb_pet->add_field(
			array(
				'id'   => $prefix . '_no-dogs',
				'name' => 'No Dogs',
				'type' => 'checkbox',
			)
		);
		$no3    = $cmb_pet->add_field(
			array(
				'id'   => $prefix . '_no-kids',
				'name' => 'No Kids',
				'type' => 'checkbox',
			)
		);
		$no4    = $cmb_pet->add_field(
			array(
				'id'   => $prefix . '_special-needs',
				'name' => 'Special Needs',
				'type' => 'checkbox',
			)
		);
		$no_row = $cmb2Grid->addRow(); //phpcs:ignore WordPress.NamingConventions
		$no_row->addColumns( array( $no1, $no2, $no3, $no4 ) );

		$cmb_pet->add_field(
			array(
				'id'         => $prefix . '_special-needs-description',
				'name'       => 'Special Needs Description',
				'type'       => 'textarea',
				'attributes' => array(
					'data-conditional-id' => $prefix . '_special-needs',
					// Works too: 'data-conditional-value' => 'on'.
				),
			)
		);

		$cmb_pet->add_field(
			array(
				'id'   => $prefix . '_adopted-date',
				'name' => 'Adopted Date',
				'type' => 'text_date',
			)
		);
		$cmb_pet->add_field(
			array(
				'id'   => $prefix . '_volunteer-comments',
				'name' => 'Volunteer Comments',
				'type' => 'textarea',
			)
		);
		$cmb_pet->add_field(
			array(
				'id'   => $prefix . '_pet-origin',
				'name' => 'Pet Origin',
				'type' => 'text',
			)
		);
		$cmb_pet->add_field(
			array(
				'id'   => $prefix . '_origin-reason',
				'name' => 'Origin Reason',
				'type' => 'textarea',
			)
		);
		$cmb_pet->add_field(
			array(
				'id'   => $prefix . '_born-to',
				'name' => 'Born to',
				'type' => 'text',
			)
		);
		$cmb_pet->add_field(
			array(
				'id'   => $prefix . '_adopted-date-2',
				'name' => 'Adopted Date (2)',
				'type' => 'text_date',
			)
		);
		$cmb_pet->add_field(
			array(
				'id'   => $prefix . '_adopted-date-3',
				'name' => 'Adopted Date (3)',
				'type' => 'text_date',
			)
		);
		$cmb_pet->add_field(
			array(
				'id'   => $prefix . '_pet-returned',
				'name' => 'Pet Returned',
				'type' => 'text_date',
			)
		);
		$cmb_pet->add_field(
			array(
				'id'   => $prefix . '_transfer-date',
				'name' => 'Transfer Date',
				'type' => 'text_date',
			)
		);

		$cmb_pet->add_field(
			array(
				'id'   => $prefix . '_offsite',
				'name' => 'Offsite',
				'type' => 'checkbox',
			)
		);
		$cmb_pet->add_field(
			array(
				'id'   => $prefix . '_camp-ravenwood',
				'name' => 'Camp Ravenwood',
				'type' => 'checkbox',
			)
		);
		$cmb_pet->add_field(
			array(
				'id'   => $prefix . '_at-office',
				'name' => 'At Office',
				'type' => 'checkbox',
			)
		);
		$cmb_pet->add_field(
			array(
				'id'   => $prefix . '_black-friday',
				'name' => 'Black Friday',
				'type' => 'checkbox',
			)
		);

		$cmb_pet->add_field(
			array(
				'id'   => $prefix . '_pets-number',
				'name' => 'Pets Number',
				'type' => 'number',
			)
		);
		$cmb_pet->add_field(
			array(
				'id'   => $prefix . '_bissell',
				'name' => 'Bissell',
				'type' => 'checkbox',
			)
		);
		$cmb_pet->add_field(
			array(
				'id'   => $prefix . '_cafe',
				'name' => 'Cafe',
				'type' => 'checkbox',
			)
		);
		$cmb_pet->add_field(
			array(
				'id'   => $prefix . '_barn',
				'name' => 'Barn Cat',
				'type' => 'checkbox',
			)
		);
		$cmb_pet->add_field(
			array(
				'id'   => $prefix . '_ready-to-fix',
				'name' => 'Ready to fix',
				'type' => 'checkbox',
			)
		);
	}

}
