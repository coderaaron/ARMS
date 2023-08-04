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
 * Blocks of this plugin
 */
class Blocks extends Base {

	/**
	 * Initialize the class.
	 *
	 * @return void|bool
	 */
	public function initialize() {
		parent::initialize();

		\add_action( 'init', array( $this, 'register_blocks' ) );
		\add_filter( 'block_categories_all', array( $this, 'add_arms_block_category' ) );
	}

	/**
	 * Registers and enqueue the block assets
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function register_blocks() {
		$blocks = array(
			'arms-meta-block',
			'block',
		);

		foreach ( $blocks as $block ) {
			\register_block_type_from_metadata(
				A_PLUGIN_ROOT . 'assets/src/blocks/' . $block,
			);
		}
	}

	/**
	 * Add ARMS block category
	 *
	 * @param array  $categories Array of block categories.
	 * @param object $post Post object.
	 * @return array
	 */
	public function add_arms_block_category( $categories ) {
		$arms_block_category = array(
			'slug'  => 'arms',
			'title' => 'ARMS',
		);
		if ( ! in_array( $arms_block_category, $categories, true ) ) {
			return array_merge(
				$categories,
				array(
					$arms_block_category,
				)
			);
		} else {
			return $categories;
		}
	}
}
