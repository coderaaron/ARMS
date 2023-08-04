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

namespace ARMS\Backend;

use ARMS\Engine\Base;

/**
 * Activate and deactive method of the plugin and relates.
 */
class ActDeact extends Base {

	/**
	 * Initialize the class.
	 *
	 * @return void|bool
	 */
	public function initialize() {
		if ( ! parent::initialize() ) {
			return;
		}

		// Activate plugin when new blog is added
		\add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		// Handle the form submission
		\add_action( 'admin_init', array( $this, 'rerun_activation' ) );
	}

	public function rerun_activation() {
		if ( isset( $_POST['arms_rerun_activation'] ) && check_admin_referer( 'my_plugin_action', 'my_plugin_nonce' ) ) {
			$this->single_activate();
		}
	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @param int $blog_id ID of the new blog.
	 * @since 1.0.0
	 * @return void
	 */
	public function activate_new_site( int $blog_id ) {
		if ( 1 !== \did_action( 'wpmu_new_blog' ) ) {
			return;
		}

		\switch_to_blog( $blog_id );
		self::single_activate();
		\restore_current_blog();
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @param bool|null $network_wide True if active in a multiste, false if classic site.
	 * @since 1.0.0
	 * @return void
	 */
	public static function activate( $network_wide ) {
		if ( \function_exists( 'is_multisite' ) && \is_multisite() ) {
			if ( $network_wide ) {
				// Get all blog ids
				/** @var array<\WP_Site> $blogs */
				$blogs = \get_sites();

				foreach ( $blogs as $blog ) {
					\switch_to_blog( (int) $blog->blog_id );
					self::single_activate();
					\restore_current_blog();
				}

				return;
			}
		}

		self::single_activate();
	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @param bool $network_wide True if WPMU superadmin uses
	 * 'Network Deactivate' action, false if
	 * WPMU is disabled or plugin is
	 * deactivated on an individual blog.
	 * @since 1.0.0
	 * @return void
	 */
	public static function deactivate( bool $network_wide ) {
		if ( \function_exists( 'is_multisite' ) && \is_multisite() ) {
			if ( $network_wide ) {
				// Get all blog ids
				/** @var array<\WP_Site> $blogs */
				$blogs = \get_sites();

				foreach ( $blogs as $blog ) {
					\switch_to_blog( (int) $blog->blog_id );
					self::single_deactivate();
					\restore_current_blog();
				}

				return;
			}
		}

		self::single_deactivate();
	}

	/**
	 * Add admin capabilities
	 *
	 * @return void
	 */
	public static function add_capabilities() {
		// Add the capabilites to all the roles
		$default_pet_caps   = array(
			'create_pet'            => false,
			'create_pets'           => false,
			'read_private_pets'     => false,
			'edit_pet'              => false,
			'edit_pets'             => false,
			'edit_private_pets'     => false,
			'edit_published_pets'   => false,
			'edit_others_pets'      => false,
			'publish_pets'          => false,
			'delete_pet'            => false,
			'delete_pets'           => false,
			'delete_private_pets'   => false,
			'delete_published_pets' => false,
			'delete_others_pets'    => false,
			'manage_pets'           => false,
			'read_pet'              => false,
		);
		$administrator_caps = get_role( 'administrator' );
		$editor_caps        = get_role( 'editor' );
		$author_caps        = get_role( 'author' );
		$contributor_caps   = get_role( 'contributor' );
		$subscriber_caps    = get_role( 'subscriber' );

		$foster_caps    = array(
			'publish_pets'       => false,
			'edit_pets'          => false,
			'edit_others_pets'   => false,
			'delete_pets'        => false,
			'delete_others_pets' => false,
			'read_private_pets'  => false,
			'edit_pet'           => true,
			'delete_pet'         => true,
			'read_pet'           => true,
		);
		$updater_caps   = array(
			'publish_pets'       => true,
			'edit_pets'          => false,
			'edit_others_pets'   => false,
			'delete_pets'        => false,
			'delete_others_pets' => false,
			'read_private_pets'  => true,
			'edit_pet'           => true,
			'delete_pet'         => true,
			'read_pet'           => true,
		);
		$core_team_caps = array(
			'publish_pets'       => true,
			'edit_pets'          => true,
			'edit_others_pets'   => true,
			'delete_pets'        => true,
			'delete_others_pets' => true,
			'read_private_pets'  => true,
			'edit_pet'           => true,
			'delete_pet'         => true,
			'read_pet'           => true,
		);

		//Add a custom roles
		// ☞ Web Team and Board Member are essentially Super Admins.
		\add_role( 'volunteer', __( 'Active Volunteer' ), array_merge( $contributor_caps->capabilities, $default_pet_caps ) );
		\add_role( 'cat_foster', __( 'Cat Foster' ), array_merge( $contributor_caps->capabilities, $foster_caps ) );
		\add_role( 'dog_foster', __( 'Dog Foster' ), array_merge( $contributor_caps->capabilities, $foster_caps ) );
		/* Essentially just taxonomies */
		\add_role( 'bottle_baby', __( 'Bottle Baby Foster' ), array() );
		\add_role( 'bully_foster', __( 'Bully Foster' ), array() );
		\add_role( 'mentor', __( 'Mentor' ), array() );
		\add_role( 'animal_control', __( 'Animal Control Contact' ), array() );
		/* Must be combined with cat/dog foster role */
		\add_role( 'core_team', __( 'Core Team Member' ), array_merge( $contributor_caps->capabilities, $core_team_caps ) );
		\add_role( 'intake', __( 'Intake Team' ), array() ); //TODO: Add intake capabilities
		\add_role( 'web_updater', __( 'Web Updater' ), array_merge( $contributor_caps->capabilities, $updater_caps ) );
		\add_role( 'tag_admin', __( 'Tag Admin' ), array() );
		\add_role( 'screener', __( 'Application Screener' ), array() ); // TODO: Add application capabilities
		/* General, non-foster related roles */
		\add_role( 'events_team', __( 'Events Team' ), array() );
		\add_role( 'volunteer_coordinator', __( 'Volunteer Coordinator' ), array( 'edit_users' => true ) );
		\add_role( 'marketing_team', 'Marketing Team', array() );
		/* Custom, one-off roles */
		\add_role( 'screening_coordinator', 'Screening Coordinator', array() );
		\add_role( 'office_cat_sponsor', 'Office Cat Sponsor', array() );
		\add_role( 'dog_kennel', 'Dog Kennel Group Member', array() );

		$roles = array(
			\get_role( 'administrator' ),
			\get_role( 'editor' ),
			\get_role( 'author' ),
			\get_role( 'contributor' ),
			\get_role( 'subscriber' ),
		);

		foreach ( $roles as $role ) {
			foreach ( $default_pet_caps as $cap ) {
				if ( \is_null( $role ) ) {
					continue;
				}

				$role->add_cap( $cap );
			}
		}
	}

	/**
	 * Remove capabilities to specific roles
	 *
	 * @return void
	 */
	public static function remove_capabilities() {
		// Remove capabilities to specific roles
		$bad_caps = array(
			'create_pets',
			'read_private_pets',
			'edit_pet',
			'edit_pets',
			'edit_private_pets',
			'edit_published_pets',
			'edit_others_pets',
			'publish_pets',
			'delete_pet',
			'delete_pets',
			'delete_private_pets',
			'delete_published_pets',
			'delete_others_pets',
			'manage_pets',
		);
		$roles    = array(
			\get_role( 'author' ),
			\get_role( 'contributor' ),
			\get_role( 'subscriber' ),
		);

		foreach ( $roles as $role ) {
			foreach ( $bad_caps as $cap ) {
				if ( \is_null( $role ) ) {
					continue;
				}

				$role->remove_cap( $cap );
			}
		}
	}


	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private static function single_activate() {
		self::add_capabilities();
		$post_types = new \ARMS\Internals\PostTypes();
		$post_types->load_cpts();
		// Create default pet types
		$cat_term_id = term_exists( 'Cat', 'pet-type' );
		if ( $cat_term_id === 0 || $cat_term_id === null ) {
			// Term doesn't exist, so insert it
			$cat_term_id = wp_insert_term( 'Cat', 'pet-type' );
		}
		// If cat_term_id is an array set it to the term_id
		if ( is_array( $cat_term_id ) ) {
			$cat_term_id = $cat_term_id['term_id'];
		}
		wp_insert_term( 'Abyssinian', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'American Bobtail', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'American Curl', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'American Shorthair', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'American Wirehair', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Applehead Siamese', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Balinese', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Bengal', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Birman', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Bombay', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'British Shorthair', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Burmese', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Burmilla', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Calico', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Canadian Hairless', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Chartreux', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Chausie', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Chinchilla', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Cornish Rex', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Cymric', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Devon Rex', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Dilute Calico', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Dilute Tortoiseshell', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Domestic Long Hair', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Domestic Medium Hair', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Domestic Short Hair', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Egyptian Mau', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Exotic Shorthair', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Extra-Toes Cat \/ Hemingway Polydactyl', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Havana', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Himalayan', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Japanese Bobtail', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Javanese', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Korat', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'LaPerm', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Maine Coon', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Manx', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Munchkin', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Nebelung', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Norwegian Forest Cat', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Ocicat', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Oriental Long Hair', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Oriental Short Hair', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Oriental Tabby', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Persian', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Pixiebob', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Ragamuffin', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Ragdoll', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Russian Blue', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Scottish Fold', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Selkirk Rex', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Siamese', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Siberian', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Silver', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Singapura', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Snowshoe', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Somali', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Sphynx \/ Hairless Cat', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Tabby', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Tiger', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Tonkinese', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Torbie', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Tortoiseshell', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Toyger', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Turkish Angora', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Turkish Van', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'Tuxedo', 'pet-type', array( 'parent' => $cat_term_id ) );
		wp_insert_term( 'York Chocolate', 'pet-type', array( 'parent' => $cat_term_id ) );

		$dog_term_id = term_exists( 'Dog', 'pet-type' );
		if ( $dog_term_id === 0 || $dog_term_id === null ) {
			// Term doesn't exist, so insert it
			$dog_term_id = wp_insert_term( 'Dog', 'pet-type' );
		}
		// If dog_term_id is an array set it to the term_id
		if ( is_array( $dog_term_id ) ) {
			$dog_term_id = $dog_term_id['term_id'];
		}
		wp_insert_term( 'Affenpinscher', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Afghan Hound', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Airedale Terrier', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Akbash', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Akita', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Alaskan Malamute', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'American Bulldog', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'American Bully', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'American Eskimo Dog', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'American Foxhound', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'American Hairless Terrier', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'American Staffordshire Terrier', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'American Water Spaniel', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Anatolian Shepherd', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Appenzell Mountain Dog', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Aussiedoodle', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Australian Cattle Dog \/ Blue Heeler', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Australian Kelpie', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Australian Shepherd', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Australian Terrier', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Basenji', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Basset Hound', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Beagle', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Bearded Collie', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Beauceron', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Bedlington Terrier', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Belgian Shepherd \/ Laekenois', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Belgian Shepherd \/ Malinois', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Belgian Shepherd \/ Sheepdog', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Belgian Shepherd \/ Tervuren', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Bernedoodle', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Bernese Mountain Dog', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Bichon Frise', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Black and Tan Coonhound', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Black Labrador Retriever', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Black Mouth Cur', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Black Russian Terrier', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Bloodhound', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Blue Lacy', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Bluetick Coonhound', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Boerboel', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Bolognese', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Border Collie', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Border Terrier', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Borzoi', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Boston Terrier', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Bouvier des Flandres', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Boxer', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Boykin Spaniel', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Briard', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Brittany Spaniel', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Brussels Griffon', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Bull Terrier', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Bullmastiff', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Cairn Terrier', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Canaan Dog', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Cane Corso', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Cardigan Welsh Corgi', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Carolina Dog', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Catahoula Leopard Dog', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Cattle Dog', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Caucasian Sheepdog \/ Caucasian Ovtcharka', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Cavachon', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Cavalier King Charles Spaniel', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Cavapoo', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Chesapeake Bay Retriever', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Chihuahua', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Chinese Crested Dog', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Chinese Foo Dog', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Chinook', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Chiweenie', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Chocolate Labrador Retriever', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Chow Chow', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Cirneco dell\u0027Etna', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Clumber Spaniel', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Cockapoo', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Cocker Spaniel', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Collie', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Coonhound', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Corgi', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Coton de Tulear', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Curly-Coated Retriever', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Dachshund', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Dalmatian', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Dandie Dinmont Terrier', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Doberman Pinscher', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Dogo Argentino', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Dogue de Bordeaux', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Dutch Shepherd', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'English Bulldog', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'English Cocker Spaniel', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'English Coonhound', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'English Foxhound', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'English Pointer', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'English Setter', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'English Shepherd', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'English Springer Spaniel', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'English Toy Spaniel', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Entlebucher', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Eskimo Dog', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Feist', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Field Spaniel', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Fila Brasileiro', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Finnish Lapphund', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Finnish Spitz', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Flat-Coated Retriever', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Fox Terrier', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Foxhound', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'French Bulldog', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Galgo Spanish Greyhound', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'German Pinscher', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'German Shepherd Dog', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'German Shorthaired Pointer', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'German Spitz', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'German Wirehaired Pointer', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Giant Schnauzer', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Glen of Imaal Terrier', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Golden Retriever', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Goldendoodle', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Gordon Setter', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Great Dane', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Great Pyrenees', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Greater Swiss Mountain Dog', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Greyhound', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Hamiltonstovare', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Harrier', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Havanese', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Hound', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Hovawart', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Husky', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Ibizan Hound', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Icelandic Sheepdog', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Illyrian Sheepdog', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Irish Setter', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Irish Terrier', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Irish Water Spaniel', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Irish Wolfhound', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Italian Greyhound', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Jack Russell Terrier', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Japanese Chin', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Jindo', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Kai Dog', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Karelian Bear Dog', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Keeshond', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Kerry Blue Terrier', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Kishu', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Klee Kai', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Komondor', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Kuvasz', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Kyi Leo', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Labradoodle', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Labrador Retriever', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Lakeland Terrier', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Lancashire Heeler', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Leonberger', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Lhasa Apso', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Lowchen', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Lurcher', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Maltese', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Maltipoo', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Manchester Terrier', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Maremma Sheepdog', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Mastiff', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'McNab', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Miniature Bull Terrier', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Miniature Dachshund', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Miniature Pinscher', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Miniature Poodle', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Miniature Schnauzer', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Mixed Breed', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Morkie', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Mountain Cur', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Mountain Dog', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Munsterlander', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Neapolitan Mastiff', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'New Guinea Singing Dog', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Newfoundland Dog', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Norfolk Terrier', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Norwegian Buhund', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Norwegian Elkhound', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Norwegian Lundehund', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Norwich Terrier', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Nova Scotia Duck Tolling Retriever', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Old English Sheepdog', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Otterhound', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Papillon', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Parson Russell Terrier', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Patterdale Terrier \/ Fell Terrier', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Pekingese', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Pembroke Welsh Corgi', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Peruvian Inca Orchid', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Petit Basset Griffon Vendeen', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Pharaoh Hound', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Pit Bull Terrier', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Plott Hound', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Pointer', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Polish Lowland Sheepdog', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Pomeranian', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Pomsky', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Poodle', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Portuguese Podengo', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Portuguese Water Dog', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Presa Canario', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Pug', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Puggle', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Puli', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Pumi', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Pyrenean Shepherd', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Rat Terrier', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Redbone Coonhound', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Retriever', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Rhodesian Ridgeback', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Rottweiler', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Rough Collie', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Saint Bernard', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Saluki', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Samoyed', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Sarplaninac', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Schipperke', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Schnauzer', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Schnoodle', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Scottish Deerhound', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Scottish Terrier', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Sealyham Terrier', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Setter', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Shar-Pei', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Sheep Dog', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Sheepadoodle', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Shepherd', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Shetland Sheepdog \/ Sheltie', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Shiba Inu', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Shih poo', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Shih Tzu', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Shollie', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Siberian Husky', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Silky Terrier', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Skye Terrier', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Sloughi', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Smooth Collie', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Smooth Fox Terrier', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'South Russian Ovtcharka', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Spaniel', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Spanish Water Dog', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Spinone Italiano', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Spitz', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Staffordshire Bull Terrier', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Standard Poodle', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Standard Schnauzer', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Sussex Spaniel', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Swedish Vallhund', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Tennessee Treeing Brindle', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Terrier', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Thai Ridgeback', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Tibetan Mastiff', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Tibetan Spaniel', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Tibetan Terrier', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Tosa Inu', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Toy Fox Terrier', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Toy Manchester Terrier', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Treeing Walker Coonhound', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Vizsla', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Weimaraner', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Welsh Springer Spaniel', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Welsh Terrier', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'West Highland White Terrier \/ Westie', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Wheaten Terrier', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Whippet', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'White German Shepherd', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Wire Fox Terrier', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Wirehaired Dachshund', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Wirehaired Pointing Griffon', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Wirehaired Terrier', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Xoloitzcuintli \/ Mexican Hairless', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Yellow Labrador Retriever', 'pet-type', array( 'parent' => $dog_term_id ) );
		wp_insert_term( 'Yorkshire Terrier', 'pet-type', array( 'parent' => $dog_term_id ) );
		// Create default pet statuses
		wp_insert_term( 'Created', 'pet-status' );
		wp_insert_term( 'Quarantined', 'pet-status' );
		wp_insert_term( 'Available', 'pet-status' );
		wp_insert_term( 'Sidelined', 'pet-status' );
		wp_insert_term( 'Adopted', 'pet-status' );
		wp_insert_term( 'Deceased', 'pet-status' );
		wp_insert_term( 'Other', 'pet-status' );
		wp_insert_term( 'Adoption Pending', 'pet-status' );
		wp_insert_term( 'Transferred Out of KAR', 'pet-status' );
		wp_insert_term( 'Returned to Relinquisher', 'pet-status' );
		wp_insert_term( 'Euthanized', 'pet-status' );
		wp_insert_term( 'Medical', 'pet-status' );
		wp_insert_term( 'Application Screening', 'pet-status' );
		wp_insert_term( 'Incomplete/Missing Info', 'pet-status' );
		// Create default genders
		wp_insert_term( 'Female', 'pet-gender' );
		wp_insert_term( 'Male', 'pet-gender' );

		// Clear the permalinks
		\flush_rewrite_rules();
	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private static function single_deactivate() {
		// @TODO: Define deactivation functionality here
		self::remove_capabilities();
		// Clear the permalinks
		\flush_rewrite_rules();
	}

}
