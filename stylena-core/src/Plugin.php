<?php
/**
 * Plugin bootstrap.
 *
 * Coexistence with the legacy theme (<= 1.2.x, which still registers the same
 * post types, taxonomy, shortcode and widgets): every registration here is
 * guarded by post_type_exists() / taxonomy_exists() and runs at init priority 5.
 * The slider CPT is registered by the old theme at priority 0, so the theme's
 * copy wins and the plugin skips it. The other types are registered by the old
 * theme at priority 10, i.e. after the plugin; the later register_post_type()
 * call simply replaces the plugin's identical definition. Either way the
 * arguments are identical, so the result is the same and nothing fatals.
 * Once the theme drops its registrations the plugin is the only source.
 *
 * @package StylenaCore
 */

namespace StylenaCore;

use StylenaCore\Meta\Registry;
use StylenaCore\PostTypes\Faq;
use StylenaCore\PostTypes\Portfolio;
use StylenaCore\PostTypes\Service;
use StylenaCore\PostTypes\Slider;
use StylenaCore\PostTypes\Team;
use StylenaCore\Taxonomies\PortfolioCategory;
use StylenaCore\Taxonomies\ServiceCategory;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main plugin class.
 */
final class Plugin {

	const VERSION_OPTION = 'stylena_core_version';
	const FLUSH_OPTION   = 'stylena_core_flush_rewrite';

	/**
	 * Singleton instance.
	 *
	 * @var Plugin|null
	 */
	private static $instance = null;

	/**
	 * Get the instance.
	 *
	 * @return Plugin
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Hook everything up.
	 *
	 * @return void
	 */
	public function boot() {
		add_action( 'init', array( $this, 'load_textdomain' ), 1 );
		add_action( 'init', array( $this, 'register_content_types' ), 5 );
		add_action( 'init', array( $this, 'maybe_flush_rewrite' ), 99 );
		\StylenaCore\Admin\Admin::init();
		require_once STYLENA_CORE_DIR . 'src/api.php';
		\StylenaCore\Frontend\Slider::boot();
	}

	/**
	 * Load translations.
	 *
	 * @return void
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'stylena-core', false, dirname( plugin_basename( STYLENA_CORE_FILE ) ) . '/languages' );
	}

	/**
	 * Register post types, taxonomy and meta.
	 *
	 * @return void
	 */
	public function register_content_types() {
		Service::register();
		Team::register();
		Portfolio::register();
		Faq::register();
		Slider::register();
		ServiceCategory::register();
		PortfolioCategory::register();
		Registry::register();
	}

	/**
	 * Flush rewrite rules once after activation or a version change.
	 *
	 * @return void
	 */
	public function maybe_flush_rewrite() {
		$flag    = get_option( self::FLUSH_OPTION );
		$changed = get_option( self::VERSION_OPTION ) !== STYLENA_CORE_VERSION;

		if ( $flag || $changed ) {
			flush_rewrite_rules( false );
			delete_option( self::FLUSH_OPTION );
			update_option( self::VERSION_OPTION, STYLENA_CORE_VERSION );
		}
	}

	/**
	 * Activation: set flags only; no content is created and no remote calls are made.
	 *
	 * @return void
	 */
	public static function activate() {
		update_option( self::FLUSH_OPTION, '1' );
		update_option( self::VERSION_OPTION, STYLENA_CORE_VERSION );
	}

	/**
	 * Deactivation: flush rules so the removed types stop routing.
	 *
	 * @return void
	 */
	public static function deactivate() {
		flush_rewrite_rules( false );
	}
}
