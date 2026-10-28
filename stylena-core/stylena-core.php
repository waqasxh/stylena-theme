<?php
/**
 * Plugin Name:       Stylena Core
 * Plugin URI:        https://stylena.zylonex.com
 * Description:       Companion plugin for the Stylena theme. Registers the Services, Team, Portfolio, FAQ and Image Slider content types and the Service Category taxonomy.
 * Version:           1.0.0
 * Requires at least: 6.8
 * Requires PHP:      8.1
 * Author:            Zylonex
 * Author URI:        https://zylonex.com
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       stylena-core
 * Domain Path:       /languages
 * Update URI:        false
 *
 * @package StylenaCore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'STYLENA_CORE_VERSION', '1.0.0' );
define( 'STYLENA_CORE_FILE', __FILE__ );
define( 'STYLENA_CORE_DIR', plugin_dir_path( __FILE__ ) );
define( 'STYLENA_CORE_URL', plugin_dir_url( __FILE__ ) );

/**
 * PSR-4 style autoloader for the StylenaCore\ namespace (maps to src/).
 *
 * @param string $class_name Fully qualified class name.
 * @return void
 */
spl_autoload_register(
	static function ( $class_name ) {
		$prefix = 'StylenaCore\\';
		if ( 0 !== strpos( $class_name, $prefix ) ) {
			return;
		}
		$relative = substr( $class_name, strlen( $prefix ) );
		$file     = STYLENA_CORE_DIR . 'src/' . str_replace( '\\', '/', $relative ) . '.php';
		if ( is_readable( $file ) ) {
			require_once $file;
		}
	}
);

register_activation_hook( __FILE__, array( 'StylenaCore\\Plugin', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'StylenaCore\\Plugin', 'deactivate' ) );

\StylenaCore\Plugin::instance()->boot();
