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

namespace ARMS\Internals;

use ARMS\Engine\Base;

/**
 * Post Types and Taxonomies
 */
class PostTypes extends Base {

	/**
	 * Initialize the class.
	 *
	 * @return void|bool
	 */
	public function initialize() { // phpcs:ignore
		parent::initialize();

		\add_action( 'init', array( $this, 'load_cpts' ) );
		/*
		 * Custom Columns
		 */
		$post_columns = new \CPT_columns( 'pet' );
		$post_columns->add_column(
			'cmb2_field',
			array(
				'label'    => \__( 'Pet Status', A_TEXTDOMAIN ),
				'type'     => 'custom_value',
				'meta_key' => '_pet_data_primary-pet-status', // phpcs:ignore WordPress.DB
				'callback' => array( $this, 'decode_pet_status' ),
				'orderby'  => 'meta_value',
				'sortable' => true,
				'prefix'   => '<b>',
				'suffix'   => '</b>',
				'order'    => '-1',
			)
		);

		/*
		 * Custom Bulk Actions
		 */
		$bulk_actions = new \Seravo_Custom_Bulk_Action( array( 'post_type' => 'pet' ) );
		$bulk_actions->register_bulk_action(
			array(
				'menu_text'    => 'Mark meta',
				'admin_notice' => 'Written something on custom bulk meta',
				'callback'     => static function( $post_ids ) {
					foreach ( $post_ids as $post_id ) {
						\update_post_meta( $post_id, '_pet_' . A_TEXTDOMAIN . '_text', 'Random stuff' );
					}

					return true;
				},
			)
		);
		$bulk_actions->init();
		// Add bubble notification for cpt pending
		\add_action( 'admin_menu', array( $this, 'pending_cpt_bubble' ), 999 );
		\add_filter( 'pre_get_posts', array( $this, 'filter_search' ) );
	}

	public function decode_pet_status( $post_id ) {
		$value    = \get_post_meta( $post_id, '_pet_data_primary-pet-status', true ); // phpcs:ignore WordPress.DB
		$statuses = array(
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
		);

		return $statuses[ $value ];
	}

	/**
	 * Add support for custom CPT on the search box
	 *
	 * @param \WP_Query $query WP_Query.
	 * @since 1.0.0
	 * @return \WP_Query
	 */
	public function filter_search( \WP_Query $query ) {
		if ( $query->is_search && ! \is_admin() ) {
			$post_types = $query->get( 'post_type' );

			if ( 'post' === $post_types ) {
				$post_types = array( $post_types );
				$query->set( 'post_type', \array_push( $post_types, array( 'pet' ) ) );
			}
		}

		return $query;
	}

	/**
	 * Load CPT and Taxonomies on WordPress
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function load_cpts() { //phpcs:ignore
		// Create Custom Post Type https://github.com/johnbillion/extended-cpts/wiki
		$pet_cpt = \register_extended_post_type(
			'pet',
			array(
				// Show all posts on the post type archive:
				'archive'            => array(
					'nopaging' => true,
				),
				'slug'               => 'pet',
				'show_in_rest'       => true,
				'dashboard_activity' => true,

				// Add some custom columns to the admin screen
				'admin_cols'         => array(
					'featured_image' => array(
						'title'          => 'Featured Image',
						'featured_image' => 'thumbnail',
					),
					'title',
					'genre'          => array(
						'taxonomy' => 'pet-type',
					),
					'date'           => array(
						'title'   => 'Date',
						'default' => 'ASC',
					),
				),
				// Add a dropdown filter to the admin screen:
				'admin_filters'      => array(
					'genre' => array(
						'taxonomy' => 'pet-type',
					),
				),
			),
			array(
				// Override the base names used for labels:
				'singular' => \__( 'Pet', A_TEXTDOMAIN ),
				'plural'   => \__( 'Pets', A_TEXTDOMAIN ),
			)
		);

		$pet_cpt->add_taxonomy(
			'pet-type',
			array(
				'hierarchical' => false,
				'show_ui'      => false,
			)
		);
		// Create Custom Taxonomy https://github.com/johnbillion/extended-taxos
		\register_extended_taxonomy(
			'pet-type',
			'pet',
			array(
				// Use radio buttons in the meta box for this taxonomy on the post editing screen:
				'meta_box'         => 'radio',
				'default_term'     => 'cat',
				// Show this taxonomy in the 'At a Glance' dashboard widget:
				'dashboard_glance' => true,
				'slug'             => 'pet-type',
				'show_in_rest'     => true,
				'required'         => true,
			),
			array(
				// Override the base names used for labels:
				'singular' => \__( 'Pet Type', A_TEXTDOMAIN ),
				'plural'   => \__( 'Pet Types', A_TEXTDOMAIN ),
			)
		);
	}

	/**
	 * Bubble Notification for pending cpt<br>
	 * NOTE: add in $post_types your cpts<br>
	 *
	 *        Reference:  http://wordpress.stackexchange.com/questions/89028/put-update-like-notification-bubble-on-multiple-cpts-menus-for-pending-items/95058
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function pending_cpt_bubble() {
		global $menu;

		$post_types = array( 'pet' );

		foreach ( $post_types as $type ) {
			if ( ! \post_type_exists( $type ) ) {
				continue;
			}

			// Count posts
			$cpt_count = \wp_count_posts( $type );

			if ( ! $cpt_count->pending ) {
				continue;
			}

			// Locate the key of
			$key = self::recursive_array_search_php( 'edit.php?post_type=' . $type, $menu );

			// Not found, just in case
			if ( ! $key ) {
				return;
			}

			// Modify menu item
			$menu[ $key ][0] .= \sprintf( //phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
				'<span class="update-plugins count-%1$s"><span class="plugin-count">%1$s</span></span>',
				$cpt_count->pending
			);
		}
	}

	/**
	 * Required for the bubble notification<br>
	 *
	 *  Reference:  http://wordpress.stackexchange.com/questions/89028/put-update-like-notification-bubble-on-multiple-cpts-menus-for-pending-items/95058
	 *
	 * @param string $needle First parameter.
	 * @param array  $haystack Second parameter.
	 * @since 1.0.0
	 * @return string|bool
	 */
	private function recursive_array_search_php( string $needle, array $haystack ) {
		foreach ( $haystack as $key => $value ) {
			$current_key = $key;

			if (
				$needle === $value ||
				( \is_array( $value ) &&
				false !== self::recursive_array_search_php( $needle, $value ) )
			) {
				return $current_key;
			}
		}

		return false;
	}

}
