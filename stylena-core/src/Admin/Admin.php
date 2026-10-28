<?php
/**
 * Admin bootstrap.
 *
 * Coexistence with the legacy theme (<= 1.2.x), which still registers the same
 * meta boxes, saves, columns, term meta and media enqueue from
 * stylena/inc/functions/stylena-functions.php: when that legacy code is loaded
 * (detected via function_exists( 'stylena_add_meta_boxes' )) this class does NOT
 * register anything, so the editor never shows two boxes and no field is saved
 * twice. Once the theme drops that code (T-101f) the plugin takes over
 * automatically. The decision is filterable via `stylena_core_register_admin`
 * (bool, receives the default computed from the legacy check).
 *
 * @package StylenaCore
 */

namespace StylenaCore\Admin;

use StylenaCore\Admin\MetaBoxes\Faq;
use StylenaCore\Admin\MetaBoxes\Portfolio;
use StylenaCore\Admin\MetaBoxes\Service;
use StylenaCore\Admin\MetaBoxes\Slider;
use StylenaCore\Admin\MetaBoxes\Team;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Wires the admin UI classes.
 */
final class Admin {

	/**
	 * Hook the boot routine. Runs at init 20, after the theme's functions.php
	 * has loaded, so the legacy check is reliable.
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'boot' ), 20 );
	}

	/**
	 * Register the admin classes when appropriate.
	 *
	 * @return void
	 */
	public static function boot() {
		if ( ! is_admin() ) {
			return;
		}

		$legacy = function_exists( 'stylena_add_meta_boxes' );

		/**
		 * Filters whether the plugin registers its admin UI.
		 *
		 * @param bool $register True unless the legacy theme code is present.
		 */
		if ( ! apply_filters( 'stylena_core_register_admin', ! $legacy ) ) {
			return;
		}

		Service::init();
		Team::init();
		Portfolio::init();
		Faq::init();
		Slider::init();
		Columns::init();
		TermMeta::init();
		Assets::init();
	}
}
