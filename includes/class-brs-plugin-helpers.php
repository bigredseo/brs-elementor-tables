<?php
/**
 * BRS Plugin Helpers.
 *
 * Single entry point for the reusable BRS plugin infrastructure. A managed
 * plugin only needs one line in its main file:
 *
 *     require_once plugin_dir_path( __FILE__ ) . 'includes/class-brs-plugin-helpers.php';
 *     BRS_Plugin_Helpers::boot( __FILE__ );
 *
 * Everything else is read from the WordPress plugin header, so plugin metadata
 * is declared exactly once and cannot drift.
 *
 * @package   BRS_Plugin_Helpers
 * @author    Big Red SEO
 * @copyright Copyright (c) 2026 Big Red SEO
 * @license   GPL-2.0-or-later
 * @link      https://bigredseo.com/
 * @version   1.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'BRS_Plugin_Helpers', false ) ) {
	final class BRS_Plugin_Helpers {
		public const VERSION = '1.0.0';

		/**
		 * Plugin files already booted, keyed by normalized path.
		 *
		 * @var array<string,bool>
		 */
		private static array $booted = array();

		/**
		 * Load and register the reusable helpers for a plugin.
		 *
		 * @param string $plugin_file Main plugin file, normally __FILE__.
		 * @param array  $args        Optional overrides passed through to the
		 *                            updater and changelog registrations.
		 * @return void
		 */
		public static function boot( string $plugin_file, array $args = array() ): void {
			$plugin_file = wp_normalize_path( $plugin_file );

			if ( isset( self::$booted[ $plugin_file ] ) ) {
				return;
			}

			self::$booted[ $plugin_file ] = true;

			// The updater and changelog only affect admin screens and update
			// checks, so skip the header read on front-end requests.
			if ( ! self::in_managed_context() ) {
				return;
			}

			$directory = trailingslashit( dirname( $plugin_file ) );

			self::load_class(
				'BRS_Public_GitHub_Updater',
				$directory . 'includes/class-brs-public-github-updater.php'
			);

			self::load_class(
				'BRS_Plugin_Changelog',
				$directory . 'includes/class-brs-plugin-changelog.php'
			);

			$headers = class_exists( 'BRS_Public_GitHub_Updater', false )
				? BRS_Public_GitHub_Updater::read_plugin_headers( $plugin_file )
				: array(
					'Name' => '',
				);

			$slug = sanitize_key(
				$args['slug'] ?? dirname( plugin_basename( $plugin_file ) )
			);

			$name = (string) ( $args['name'] ?? $headers['Name'] );

			if ( class_exists( 'BRS_Public_GitHub_Updater', false ) ) {
				BRS_Public_GitHub_Updater::register(
					array_merge(
						$args,
						array( 'plugin_file' => $plugin_file )
					)
				);
			}

			if ( class_exists( 'BRS_Plugin_Changelog', false ) && '' !== $name && '' !== $slug ) {
				BRS_Plugin_Changelog::register(
					array(
						'plugin_file'    => $plugin_file,
						'name'           => $name,
						'slug'           => $slug,
						'changelog_file' => $args['changelog_file'] ?? '',
						'capability'     => $args['capability'] ?? 'manage_options',
					)
				);
			}
		}

		/**
		 * Require a helper class file when the class is not already loaded.
		 */
		private static function load_class( string $class_name, string $path ): void {
			if ( class_exists( $class_name, false ) ) {
				return;
			}

			if ( is_readable( $path ) ) {
				require_once $path;
			}
		}

		/**
		 * Whether the current request can act on updates or admin screens.
		 */
		private static function in_managed_context(): bool {
			if ( is_admin() ) {
				return true;
			}

			if ( function_exists( 'wp_doing_cron' ) && wp_doing_cron() ) {
				return true;
			}

			if ( defined( 'WP_CLI' ) && WP_CLI ) {
				return true;
			}

			return false;
		}
	}
}
