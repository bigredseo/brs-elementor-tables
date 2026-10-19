<?php
/**
 * Plugin Name: BRS Elementor Tables
 * Plugin URI: https://github.com/bigredseo/brs-elementor-tables
 * Description: Visual Table Editor for Elementor
 * Version: 1.0.0
 * Author: Big Red SEO
 * Author URI: https://www.bigredseo.com/
 * Text Domain: brs-elementor-tables
 * Requires at least: 6.5
 * Requires PHP: 8.3
 * Tested up to: 7.0
 * Requires Plugins: elementor
 * Update URI: https://github.com/bigredseo/brs-elementor-tables
 */

defined( 'ABSPATH' ) || exit;

define( 'BRS_ELEMENTOR_TABLES_VERSION', '1.0.0' );
define( 'BRS_ELEMENTOR_TABLES_FILE', __FILE__ );
define( 'BRS_ELEMENTOR_TABLES_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Reusable BRS plugin infrastructure.
 *
 * Registers the public GitHub updater and the local changelog page. All
 * metadata is read from the plugin header above, so nothing is repeated here.
 */
require_once BRS_ELEMENTOR_TABLES_PATH . 'includes/class-brs-plugin-helpers.php';
BRS_Plugin_Helpers::boot( __FILE__ );

// Plugin code starts here.
