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

// Register Elementor controls, widget, and editor assets.
add_action( 'elementor/controls/register', function ( $manager ) {
    require_once BRS_ELEMENTOR_TABLES_PATH . 'includes/controls/class-brs-table-control.php';
    $manager->register( new BRS_Elementor_Table_Control() );
} );

add_action( 'elementor/widgets/register', function ( $widgets_manager ) {
    require_once BRS_ELEMENTOR_TABLES_PATH . 'includes/widgets/class-brs-table-widget.php';
    $widgets_manager->register( new BRS_Elementor_Table_Widget() );
} );

add_action( 'init', function () {
    wp_register_style( 'brs-elementor-tables', plugins_url( 'assets/table.css', __FILE__ ), [], BRS_ELEMENTOR_TABLES_VERSION );
} );

add_action( 'elementor/editor/after_enqueue_scripts', function () {
    wp_enqueue_script( 'brs-elementor-tables-editor', plugins_url( 'assets/editor.js', __FILE__ ), [ 'jquery', 'elementor-editor' ], BRS_ELEMENTOR_TABLES_VERSION, true );
    wp_enqueue_style( 'brs-elementor-tables-editor', plugins_url( 'assets/editor.css', __FILE__ ), [], BRS_ELEMENTOR_TABLES_VERSION );
} );

