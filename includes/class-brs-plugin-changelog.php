<?php
/**
 * BRS Plugin Changelog.
 *
 * Adds a reusable local changelog link and hidden admin page for BRS plugins.
 *
 * @package BRS_Plugin_Changelog
 * @author  Big Red SEO
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'BRS_Plugin_Changelog', false ) ) {
	final class BRS_Plugin_Changelog {
		private static array $plugins = array();
		private static bool $hooks_registered = false;

		/**
		 * Register a plugin changelog.
		 *
		 * Required:
		 * - plugin_file
		 * - name
		 * - slug
		 *
		 * Optional:
		 * - changelog_file
		 * - capability
		 */
		public static function register( array $args ): void {
			$defaults = array(
				'plugin_file'    => '',
				'name'           => '',
				'slug'           => '',
				'changelog_file' => '',
				'capability'     => 'manage_options',
			);

			$args = wp_parse_args( $args, $defaults );

			if (
				empty( $args['plugin_file'] )
				|| empty( $args['name'] )
				|| empty( $args['slug'] )
			) {
				return;
			}

			$args['plugin_file'] = wp_normalize_path( $args['plugin_file'] );
			$args['slug'] = sanitize_key( $args['slug'] );
			$args['name'] = sanitize_text_field( $args['name'] );
			$args['capability'] = sanitize_key( $args['capability'] );

			if ( empty( $args['changelog_file'] ) ) {
				$args['changelog_file'] = dirname( $args['plugin_file'] ) . '/CHANGELOG.md';
			}

			$args['changelog_file'] = wp_normalize_path( $args['changelog_file'] );

			self::$plugins[ $args['slug'] ] = $args;

			if ( self::$hooks_registered ) {
				return;
			}

			add_filter(
				'plugin_row_meta',
				array( __CLASS__, 'plugin_row_meta' ),
				10,
				2
			);

			add_action(
				'admin_menu',
				array( __CLASS__, 'register_pages' )
			);

			self::$hooks_registered = true;
		}

		/**
		 * Add a Changelog link to the Installed Plugins row.
		 */
		public static function plugin_row_meta( array $links, string $file ): array {
			foreach ( self::$plugins as $args ) {
				if ( plugin_basename( $args['plugin_file'] ) !== $file ) {
					continue;
				}

				$url = admin_url(
					'admin.php?page=' . $args['slug'] . '-changelog'
				);

				$links[] = sprintf(
					'<a href="%1$s">%2$s</a>',
					esc_url( $url ),
					esc_html__( 'Changelog', 'brs-plugin-changelog' )
				);

				break;
			}

			return $links;
		}

		/**
		 * Register hidden changelog pages.
		 */
		public static function register_pages(): void {
			foreach ( self::$plugins as $args ) {
				add_submenu_page(
					null,
					$args['name'] . ' Changelog',
					__( 'Changelog', 'brs-plugin-changelog' ),
					$args['capability'],
					$args['slug'] . '-changelog',
					static function () use ( $args ): void {
						self::render_page( $args );
					}
				);
			}
		}

		/**
		 * Render the local CHANGELOG.md file.
		 */
		private static function render_page( array $args ): void {
			if ( ! current_user_can( $args['capability'] ) ) {
				wp_die(
					esc_html__(
						'You do not have permission to view this page.',
						'brs-plugin-changelog'
					)
				);
			}
			?>
			<div class="wrap">
				<h1><?php echo esc_html( $args['name'] . ' Changelog' ); ?></h1>

				<p>
					<a href="<?php echo esc_url( admin_url( 'plugins.php' ) ); ?>">
						&larr; <?php esc_html_e( 'Back to Plugins', 'brs-plugin-changelog' ); ?>
					</a>
				</p>

				<?php if ( is_readable( $args['changelog_file'] ) ) : ?>
					<pre style="max-width:1000px;padding:20px;overflow:auto;white-space:pre-wrap;background:#fff;border:1px solid #c3c4c7;"><?php
						echo esc_html( file_get_contents( $args['changelog_file'] ) );
					?></pre>
				<?php else : ?>
					<div class="notice notice-warning">
						<p>
							<?php
							esc_html_e(
								'The CHANGELOG.md file could not be found.',
								'brs-plugin-changelog'
							);
							?>
						</p>
					</div>
				<?php endif; ?>
			</div>
			<?php
		}
	}
}