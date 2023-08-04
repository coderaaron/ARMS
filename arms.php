<?php

/**
 * @package   ARMS
 * @author    Aaron Graham <aaron@coderaaron.com>
 * @copyright 2023 Aaron Graham
 * @license   GPL 2.0+
 * @link      http://coderaaron.com
 *
 * Plugin Name:     ARMS
 * Plugin URI:      http://coderaaron.com
 * Description:     Animal Rescue Management System - Based off of the original KAMS that I wrote for the Kalamazoo Animal Rescue in 2008.
 * Version:         1.0.0
 * Author:          Aaron Graham
 * Author URI:      http://coderaaron.com
 * Text Domain:     arms
 * License:         GPL 2.0+
 * License URI:     http://www.gnu.org/licenses/gpl-3.0.txt
 * Domain Path:     /languages
 * Requires PHP:    7.4
 * WordPress-Plugin-Boilerplate-Powered: v3.3.0
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'We\'re sorry, but you can not directly access this file.' );
}

define( 'A_VERSION', '1.0.0' );
define( 'A_TEXTDOMAIN', 'arms' );
define( 'A_NAME', 'ARMS' );
define( 'A_PLUGIN_ROOT', plugin_dir_path( __FILE__ ) );
define( 'A_PLUGIN_ABSOLUTE', __FILE__ );
define( 'A_MIN_PHP_VERSION', '8.0' );
define( 'A_WP_VERSION', '5.3' );

add_action(
	'init',
	static function () {
		load_plugin_textdomain( A_TEXTDOMAIN, false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}
);

if ( version_compare( PHP_VERSION, A_MIN_PHP_VERSION, '<=' ) ) {
	add_action(
		'admin_init',
		static function() {
			deactivate_plugins( plugin_basename( __FILE__ ) );
		}
	);
	add_action(
		'admin_notices',
		static function() {
			echo wp_kses_post(
				sprintf(
					'<div class="notice notice-error"><p>%s</p></div>',
					__( '"ARMS" requires PHP 5.6 or newer.', A_TEXTDOMAIN )
				)
			);
		}
	);

	// Return early to prevent loading the plugin.
	return;
}

$arms_libraries = require A_PLUGIN_ROOT . 'vendor/autoload.php'; //phpcs:ignore

require_once A_PLUGIN_ROOT . 'functions/functions.php';
require_once A_PLUGIN_ROOT . 'functions/debug.php';

// Add your new plugin on the wiki: https://github.com/WPBP/WordPress-Plugin-Boilerplate-Powered/wiki/Plugin-made-with-this-Boilerplate

$requirements = new \Micropackage\Requirements\Requirements(
	'ARMS',
	array(
		'php'            => A_MIN_PHP_VERSION,
		'php_extensions' => array( 'mbstring' ),
		'wp'             => A_WP_VERSION,
		// 'plugins'            => array(
		// array( 'file' => 'hello-dolly/hello.php', 'name' => 'Hello Dolly', 'version' => '1.5' )
		// ),
	)
);

if ( ! $requirements->satisfied() ) {
	$requirements->print_notice();

	return;
}



// Documentation to integrate GitHub, GitLab or BitBucket https://github.com/YahnisElsts/plugin-update-checker/blob/master/README.md
//Puc_v4_Factory::buildUpdateChecker( 'https://github.com/user-name/repo-name/', __FILE__, 'unique-plugin-or-theme-slug' );

if ( ! wp_installing() ) {
	register_activation_hook( __FILE__, array( new \ARMS\Backend\ActDeact, 'activate' ) );
	register_deactivation_hook( __FILE__, array( new \ARMS\Backend\ActDeact, 'deactivate' ) );
	add_action(
		'plugins_loaded',
		static function () use ( $arms_libraries ) {
			new \ARMS\Engine\Initialize( $arms_libraries );
		}
	);
}
