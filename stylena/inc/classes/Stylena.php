<?php

/**
 * The Stylena main functional file.
 *
 * @package Stylena
 */


namespace Stylena;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( '\Stylena\Stylena' ) ) {
	/**
	 * Stylena main functional in class style.
	 *
	 * This class will be handle all the main hooks that work with theme features such as add theme support features, register widgets area or sidebar, enqueue scripts and styles.<br>
	 * If you want to hook into WordPress and make changes or modification, please use \Stylena\Hooks\StylenaHooks() class.<br>
	 * To use, just code as follows:
	 *
	 * $Stylena = new \Stylena\Stylena();
	 * $Stylena->add_actions_filters();
	 *
	 * That's it.
	 */
	class Stylena {



		/**
		 * Add actions and filters to make main theme functional works.
		 */
		public function add_actions_filters() {
			// Change main content width up to columns available.
			\add_action( 'template_redirect', array( $this, 'detect_content_width' ) );
			// Add theme feature.
			\add_action( 'after_setup_theme', array( $this, 'theme_setup' ) );
			// Register sidebars.
			\add_action( 'widgets_init', array( $this, 'register_sidebars' ) );
			// Enqueue scripts and styles.
			\add_action( 'wp_enqueue_scripts', array( $this, 'register_common_scripts_and_styles' ) );
			\add_action( 'admin_enqueue_scripts', array( $this, 'register_common_scripts_and_styles' ) );
			\add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts_and_styles' ) );
			// Add Bootstrap styles into editor.
			\add_action( 'admin_init', array( $this, 'add_editor_styles' ) );
			// Add Bootstrap styles into Gutenberg editor.
			\add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_editor_assets' ) );
		} // add_actions_filters


		/**
		 * Add Bootstrap styles into classic editor.
		 */
		public function add_editor_styles() {
			\add_editor_style( 'assets/css/bootstrap.min.css' );
		} // add_editor_styles


		/**
		 * Detect main content width upto columns available.
		 */
		public function detect_content_width() {
			global $content_width, $stylena_sidebar_left_size, $stylena_sidebar_right_size;

			if ( \is_active_sidebar( 'sidebar-left' ) && \is_active_sidebar( 'sidebar-right' ) ) {
				$content_width = 540;
			} elseif ( \is_active_sidebar( 'sidebar-left' ) || \is_active_sidebar( 'sidebar-right' ) ) {
				$content_width = 825;
			}

			$content_width = \apply_filters( 'stylena_content_width', $content_width, $stylena_sidebar_left_size, $stylena_sidebar_right_size );
		} // detect_content_width


		/**
		 * Add Bootstrap styles into Gutenberg editor.
		 */
		public function enqueue_block_editor_assets() {
			if ( ! \wp_script_is( 'bootstrap4', 'registered' ) ) {
				$this->register_common_scripts_and_styles();
			}
			\wp_enqueue_style( 'bootstrap4' );
		} // enqueue_block_editor_assets


		/**
		 * Enqueue scripts and styles.
		 *
		 * @access private Do not access this method directly. This is for hook callback not for direct call.
		 */
		public function enqueue_scripts_and_styles() {
			$theme        = \wp_get_theme();
			$theme_version = $theme->get( 'Version' );
			unset( $theme );

			// Theme-owned CSS/JS carry their file modification time in the version so a
			// changed file is never served from a stale browser, CDN or host cache.
			$asset_version = static function ( $file ) use ( $theme_version ) {
				$path = \get_theme_file_path( $file );
				return \file_exists( $path ) ? $theme_version . '.' . \filemtime( $path ) : $theme_version;
			};

			\wp_enqueue_style( 'Stylena-wp-main', \get_stylesheet_uri(), array(), $theme_version );

			\wp_enqueue_style( 'bootstrap4' );
			// font awesome. choose css fonts instead of svg, see more at https://fontawesome.com/how-to-use/on-the-web/other-topics/performance.
			\wp_enqueue_style( 'Stylena-font-awesome5', \get_theme_file_uri( '/assets/fontawesome/css/all.min.css' ), array(), '5.15.4' );
			\wp_enqueue_style( 'Stylena-main', \get_theme_file_uri( '/assets/css/main.css' ), array(), $theme_version );

			// Custom theme fonts and styles were merged into custom.css; no separate enqueue required
			// Self-hosted fonts (Exo, Aboreto, Montserrat; OFL) - no Google requests.
			\wp_enqueue_style( 'stylena-fonts', \get_theme_file_uri( '/css/fonts.css' ), array(), $theme_version );
			// Custom overrides.
			\wp_enqueue_style( 'stylena-custom', \get_theme_file_uri( '/css/custom.css' ), array( 'bootstrap4', 'stylena-fonts' ), $asset_version( '/css/custom.css' ) );

			// Responsive overrides loaded after custom so it can override custom rules when necessary.
			\wp_enqueue_style( 'stylena-responsive', \get_theme_file_uri( '/css/responsive.css' ), array( 'stylena-custom' ), $asset_version( '/css/responsive.css' ) );

			// RTL mirror of directional rules; WordPress only auto-swaps style.css to rtl.css when 'rtl' data is set on that handle (it is not),
			// so this is the single load (verified by tests/phpunit/RtlStylesheetTest.php).
			if ( \is_rtl() && \file_exists( \get_theme_file_path( '/rtl.css' ) ) ) {
				\wp_enqueue_style( 'stylena-rtl', \get_theme_file_uri( '/rtl.css' ), array( 'stylena-responsive' ), $asset_version( '/rtl.css' ) );
			}

			// Live search mega menu styles (production version).
			\wp_enqueue_style( 'stylena-live-search', \get_theme_file_uri( '/css/live-search.css' ), array( 'stylena-custom' ), $asset_version( '/css/live-search.css' ) );

			// FAQ styles were merged into custom.css. no separate enqueue required.

			// Image Slider styles and scripts
			// Styles for the image slider were merged into css/custom.css; no separate stylesheet enqueue required.
			// Image slider behaviors merged into stylena-custom; no separate enqueue required.

			// 404 page specific styles
			// 404 styles were merged into custom.css; no additional enqueue required.

			if ( StylenaUtilities::has_widget_block( 'calendar' ) ) {
				// if theme using widget block calendar.
				// enqueue css to fix calendar widget block to render as non widget block.
				// if you would like it to be render as new widget block, please dequeue this handle.
				\wp_enqueue_style( 'Stylena-widgetblocks-calendar', \get_theme_file_uri( '/assets/css/widget-blocks/calendar.css' ), array(), $theme_version );
			}

			if ( \is_singular() && \get_option( 'thread_comments' ) ) {
				\wp_enqueue_script( 'comment-reply' );
			}
			\wp_enqueue_script( 'bootstrap4-bundle', \get_theme_file_uri( '/assets/js/bootstrap.bundle.min.js' ), array( 'jquery' ), '4.6.2', true ); // bundled with popper. see https://getbootstrap.com/docs/4.0/getting-started/contents/#comparison-of-css-files.
			\wp_enqueue_script( 'Stylena-main', \get_theme_file_uri( '/assets/js/main.js' ), array( 'jquery' ), $theme_version, true );

			// Header behaviour (search toggle, scroll state, mobile nav).
			\wp_enqueue_script( 'stylena-header', \get_theme_file_uri( '/js/header.js' ), array(), $asset_version( '/js/header.js' ), true );

			// Custom theme scripts.
			\wp_enqueue_script( 'stylena-custom', \get_theme_file_uri( '/js/custom.js' ), array( 'jquery' ), $asset_version( '/js/custom.js' ), true );

			// Translatable labels for accessibility controls created in JS.
			\wp_localize_script(
				'stylena-custom',
				'stylenaA11y',
				array(
					'slider' => array(
						'pause' => \__( 'Pause slider', 'stylena' ),
						'play'  => \__( 'Play slider', 'stylena' ),
					),
					'hero'   => array(
						'pause' => \__( 'Pause background video', 'stylena' ),
						'play'  => \__( 'Play background video', 'stylena' ),
					),
				)
			);

			// Live search mega menu JavaScript (production version).
			\wp_enqueue_script( 'stylena-live-search', \get_theme_file_uri( '/js/live-search.js' ), array( 'jquery' ), $asset_version( '/js/live-search.js' ), true );

			// Localize script to pass AJAX URL and nonce for live search.
			\wp_localize_script(
				'stylena-live-search',
				'stylena_ajax',
				array(
					'ajax_url' => \admin_url( 'admin-ajax.php' ),
					'nonce'    => \wp_create_nonce( 'stylena_search_nonce' ),
					'i18n'     => array(
						'searching'  => \__( 'Searching...', 'stylena' ),
						'failed'     => \__( 'Search failed', 'stylena' ),
						'connection' => \__( 'Connection error', 'stylena' ),
						/* translators: %s: search term */
						'resultsFor' => \__( 'Search Results for "%s"', 'stylena' ),
						'noResults'  => \__( 'No Results Found', 'stylena' ),
						'close'      => \__( 'Close search results', 'stylena' ),
						/* translators: %d: number of results */
						'found'      => \__( '%d results found', 'stylena' ),
					),
				)
			);

			// Hero margins responsive handler - only on homepage.
			if ( \is_front_page() ) {
				\wp_enqueue_script( 'stylena-hero-margins', \get_theme_file_uri( '/js/hero-margins.js' ), array( 'jquery' ), $asset_version( '/js/hero-margins.js' ), true );
			}

			// FAQ scripts were merged into stylena-custom; no separate enqueue required.

			// Homepage specific scripts - behaviors merged into stylena-custom.

			// 404 page specific scripts - behaviors merged into stylena-custom
		} // enqueue_scripts_and_styles


		/**
		 * Register commonly use scripts and styles for back-end and front-end.
		 */
		public function register_common_scripts_and_styles() {
			\wp_register_style( 'bootstrap4', \get_theme_file_uri( '/assets/css/bootstrap.min.css' ), array(), '4.6.2' );
		} // register_common_scripts_and_styles


		/**
		 * Register sidebars
		 *
		 * @access private Do not access this method directly. This is for hook callback not for direct call.
		 */
		public function register_sidebars() {
			\register_sidebar(
				array(
					'name'          => \__( 'Sidebar left', 'stylena' ),
					'id'            => 'sidebar-left',
					'before_widget' => '<aside id="%1$s" class="widget %2$s">',
					'after_widget'  => '</aside>',
					'before_title'  => '<h1 class="widget-title">',
					'after_title'   => '</h1>',
				)
			);

			\register_sidebar(
				array(
					'name'          => \__( 'Sidebar right', 'stylena' ),
					'id'            => 'sidebar-right',
					'before_widget' => '<aside id="%1$s" class="widget %2$s">',
					'after_widget'  => '</aside>',
					'before_title'  => '<h1 class="widget-title">',
					'after_title'   => '</h1>',
				)
			);

			\register_sidebar(
				array(
					'name'          => \__( 'Header right', 'stylena' ),
					'id'            => 'header-right',
					'before_widget' => '<div id="%1$s" class="widget %2$s">',
					'after_widget'  => '</div>',
					'before_title'  => '<h1 class="widget-title">',
					'after_title'   => '</h1>',
				)
			);

			\register_sidebar(
				array(
					'name'          => \__( 'Navigation bar right', 'stylena' ),
					'id'            => 'navbar-right',
					'before_widget' => '',
					'after_widget'  => '',
					'before_title'  => '',
					'after_title'   => '',
				)
			);

			\register_sidebar(
				array(
					'name'          => \__( 'Footer left', 'stylena' ),
					'id'            => 'footer-left',
					'before_widget' => '<div id="%1$s" class="widget %2$s">',
					'after_widget'  => '</div>',
					'before_title'  => '<h1 class="widget-title">',
					'after_title'   => '</h1>',
				)
			);

			\register_sidebar(
				array(
					'name'          => \__( 'Footer right', 'stylena' ),
					'id'            => 'footer-right',
					'before_widget' => '<div id="%1$s" class="widget %2$s">',
					'after_widget'  => '</div>',
					'before_title'  => '<h1 class="widget-title">',
					'after_title'   => '</h1>',
				)
			);
		} // register_sidebars


		/**
		 * Add theme feature.
		 *
		 * @access private Do not access this method directly. This is for hook callback not for direct call.
		 */
		public function theme_setup() {
			// load theme textdomain for translation.
			\load_theme_textdomain( 'stylena', \get_template_directory() . '/languages' );

			// add theme support title-tag.
			\add_theme_support( 'title-tag' );

			// add theme support post and comment automatic feed links.
			\add_theme_support( 'automatic-feed-links' );

			// allow the use of html5 markup
			// @link https://codex.wordpress.org/Theme_Markup.
			\add_theme_support( 'html5', array( 'caption', 'comment-form', 'comment-list', 'gallery', 'search-form' ) );

			// add support menu.
			\register_nav_menus(
				array(
					'primary'  => \__( 'Primary Menu', 'stylena' ),
					'top-left' => \__( 'Top Left Menu', 'stylena' ),
				)
			);

			// add post formats support.
			\add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );

			// add post thumbnails support. **This is REQUIRED for post editor to show featured image field.**
			// To display featured image, please use post thumbnail functions.
			// See https://codex.wordpress.org/Post_Thumbnails for reference.
			\add_theme_support( 'post-thumbnails' );

			// @since 1.2 or WordPress 5.0+
			// make gutenberg support. --------------------------------------------------------------------------------------
			// @link https://wordpress.org/gutenberg/handbook/extensibility/theme-support/ reference.
			// add wide alignment ( https://wordpress.org/gutenberg/handbook/designers-developers/developers/themes/theme-support/#wide-alignment )
			\add_theme_support( 'align-wide' );

			// support default block styles for front-end ( https://wordpress.org/gutenberg/handbook/designers-developers/developers/themes/theme-support/#default-block-styles ).
			\add_theme_support( 'wp-block-styles' );

			// support editor styles ( https://wordpress.org/gutenberg/handbook/designers-developers/developers/themes/theme-support/#editor-styles )
			// this one make appearance in editor more close to Bootstrap 4.
			\add_theme_support( 'editor-styles' );

			// support responsive embeds for front-end ( https://wordpress.org/gutenberg/handbook/designers-developers/developers/themes/theme-support/#responsive-embedded-content ).
			\add_theme_support( 'responsive-embeds' );
			// end make gutenberg support. ---------------------------------------------------------------------------------.
		} // theme_setup
	}
}
