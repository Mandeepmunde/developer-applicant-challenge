<?php
/**
 * Plugin Name: Developer Applicant Challenge
 * Description: Developer Applicant Challenge
 * Version: 1.0.0
 * Author: Mandeep Singh
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: dev-app-challenge
 * Domain Path: /languages
 *
 * @package Developer Applicant Challenge
 */

defined( 'ABSPATH' ) || exit;

// Define Constants.
define( 'DAPPC_URI', plugins_url( 'developer-applicant-challenge' ) );
define( 'DAPPC_ASSETS_URI', plugins_url( 'developer-applicant-challenge' ) . '/assets' );
define( 'DAPPC_TEMPLATE_PATH', plugin_dir_path( __FILE__ ) . 'templates/' );
define( 'DAPPC_PLUGIN_PATH', __FILE__ );


/*
* Load all PHP files in inc/
*/

$inc_paths = [
	'inc/class-dappc-api-handler.php',
	'inc/class-dappc-enqueue-scripts.php',
	'inc/class-dappc-wp-footer.php',
	'inc/class-dappc-ajax-handler.php',
	'inc/class-dappc-shortcode.php',
	'inc/class-dappc-admin-pages.php',
];

foreach ( $inc_paths as $inc_path ) {
	include_once $inc_path;
}

// Only include if WP_CLI is available.
if ( defined( 'WP_CLI' ) && WP_CLI ) {
	require_once( 'inc/class-dappc-cli.php' );
}
