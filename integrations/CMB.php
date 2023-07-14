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
		\add_action( 'cmb2_init', array( $this, 'cmb_pet_metaboxes' ) );
	}

	/**
	 * Your metabox on pet CPT
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function cmb_pet_metaboxes() { // phpcs:ignore
		// Start with an underscore to hide fields from custom fields list
		$prefix   = '_pet_data';
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

		$cmb_pet->add_field(
			array(
				'id'   => $prefix . '_legacy-pet-id',
				'name' => 'Legacy Pet ID',
				'type' => 'hidden',
			)
		);

		$basics1    = $cmb_pet->add_field(
			array(
				'id'   => $prefix . '_tag-number',
				'name' => 'Tag Number',
				'type' => 'text_small',
			)
		);
		$basics2    = $cmb_pet->add_field(
			array(
				'id'      => $prefix . '_pet-type',
				'name'    => 'Pet Type',
				'type'    => 'select',
				'options' => array(
					'c' => 'Cat',
					'd' => 'Dog',
				),
			)
		);
		$basics3    = $cmb_pet->add_field(
			array(
				'id'      => $prefix . '_pet-sex',
				'name'    => 'Pet Sex',
				'type'    => 'select',
				'options' => array(
					'm' => 'Male',
					'f' => 'Female',
				),
			)
		);
		$basics_row = $cmb2Grid->addRow(); //phpcs:ignore WordPress.NamingConventions
		$basics_row->addColumns( array( $basics1, $basics2, $basics3 ) );

		$status1    = $cmb_pet->add_field(
			array(
				'id'      => $prefix . '_primary-pet-status',
				'name'    => 'Primary Pet Status',
				'type'    => 'select',
				'options' => array(
					'c' => 'Created',
					'q' => 'Quarantined',
					'v' => 'Available',
					's' => 'Sidelined',
					'a' => 'Adopted',
					'd' => 'Deceased',
					'r' => 'Other',
					'p' => 'Adoption Pending',
					't' => 'Transferred Out of KAR',
					'u' => 'Returned to Relinquisher',
				),
			)
		);
		$status2    = $cmb_pet->add_field(
			array(
				'id'      => $prefix . '_secondary-pet-status',
				'name'    => 'Secondary Pet Status',
				'type'    => 'select',
				'options' => array(
					'n' => 'None',
					'e' => 'Euthanized',
					'm' => 'Medical',
					'a' => 'Application Screening',
					'i' => 'Incomplete/Missing Info',
					'o' => 'Other',
				),
			)
		);
		$status_row = $cmb2Grid->addRow(); //phpcs:ignore WordPress.NamingConventions
		$status_row->addColumns( array( $status1, $status2 ) );

		$cmb_pet->add_field(
			array(
				'id'   => $prefix . '_adoption-fee',
				'name' => 'Adoption Fee',
				'type' => 'text_money',
			)
		);

		$cmb_pet->add_field(
			array(
				'id'   => $prefix . '_date-of-birth',
				'name' => 'Date of Birth',
				'type' => 'text_date',
			)
		);
		$cmb_pet->add_field(
			array(
				'id'   => $prefix . '_declawed',
				'name' => 'Declawed',
				'type' => 'checkbox',
			)
		);

		$mc1    = $cmb_pet->add_field(
			array(
				'id'   => $prefix . '_microchipped',
				'name' => 'Microchipped',
				'type' => 'checkbox',
			)
		);
		$mc2    = $cmb_pet->add_field(
			array(
				'id'   => $prefix . '_microchip-brand',
				'name' => 'Microchip brand',
				'type' => 'text',
			)
		);
		$mc3    = $cmb_pet->add_field(
			array(
				'id'   => $prefix . '_microchip-id',
				'name' => 'Microchip ID',
				'type' => 'text',
			)
		);
		$mc_row = $cmb2Grid->addRow(); //phpcs:ignore WordPress.NamingConventions
		$mc_row->addColumns( array( $mc1, $mc2, $mc3 ) );

		$cmb_pet->add_field(
			array(
				'id'   => $prefix . '_mixed',
				'name' => 'Mixed',
				'type' => 'checkbox',
			)
		);
		$cmb_pet->add_field(
			array(
				'id'      => $prefix . '_pet-size',
				'name'    => 'Pet Size',
				'type'    => 'select',
				'options' => array(
					's'  => 'Small',
					'm'  => 'Medium',
					'l'  => 'Large',
					'xl' => 'Extra Large',
				),
			)
		);
		$cmb_pet->add_field(
			array(
				'id'   => $prefix . '_shots-up-to-date',
				'name' => 'Shots up to date',
				'type' => 'checkbox',
			)
		);
		$cmb_pet->add_field(
			array(
				'id'   => $prefix . '_spayed-neutered',
				'name' => 'Spayed/Neutered',
				'type' => 'checkbox',
			)
		);

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
		$no_row = $cmb2Grid->addRow(); //phpcs:ignore WordPress.NamingConventions
		$no_row->addColumns( array( $no1, $no2, $no3 ) );

		$cmb_pet->add_field(
			array(
				'id'   => $prefix . '_housebroken',
				'name' => 'Housebroken',
				'type' => 'checkbox',
			)
		);
		$cmb_pet->add_field(
			array(
				'id'   => $prefix . '_special-needs',
				'name' => 'Special Needs',
				'type' => 'checkbox',
			)
		);
		$cmb_pet->add_field(
			array(
				'id'   => $prefix . '_special-needs-description',
				'name' => 'Special Needs Description',
				'type' => 'textarea',
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
