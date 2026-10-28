<?php
/**
 * Front-end image slider: renderer, shortcode, widget registration, assets.
 *
 * Coexistence with the legacy theme (<= 1.2.x) which still ships the same
 * [stylena_slider] shortcode, Stylena_Image_Slider_Widget and slider CSS/JS:
 *  - the shortcode is added only if shortcode_exists( 'stylena_slider' ) is false;
 *  - the widgets are skipped when the theme classes exist (see the widget classes);
 *  - the plugin's own CSS/JS is enqueued only when the theme has not enqueued
 *    its 'stylena-custom' style/script (filter 'stylena_core_slider_enqueue_assets'
 *    to force or suppress; filters 'stylena_core_slider_style_handle' and
 *    'stylena_core_slider_script_handle' change which theme handles are detected).
 * The theme copy wins until T-101f removes it.
 *
 * @package StylenaCore
 */

namespace StylenaCore\Frontend;

use StylenaCore\Widgets\ImageSlider;
use StylenaCore\Widgets\ServiceCategories;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Slider front-end.
 */
final class Slider {

	const POST_TYPE = 'stylena_slider';

	/**
	 * Hook up.
	 *
	 * @return void
	 */
	public static function boot() {
		add_action( 'init', array( __CLASS__, 'register_shortcode' ), 20 );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'register_assets' ) );
		add_action( 'widgets_init', array( __CLASS__, 'register_widgets' ), 20 );
	}

	/**
	 * Add the shortcode unless the theme already provides it.
	 *
	 * @return void
	 */
	public static function register_shortcode() {
		if ( shortcode_exists( 'stylena_slider' ) ) {
			return;
		}
		add_shortcode( 'stylena_slider', array( __CLASS__, 'shortcode' ) );
	}

	/**
	 * Register both widgets (each class guards against the legacy theme copy).
	 *
	 * @return void
	 */
	public static function register_widgets() {
		ImageSlider::register();
		ServiceCategories::register();
	}

	/**
	 * Shortcode callback.
	 *
	 * @param array|string $atts Shortcode attributes.
	 * @return string Escaped markup.
	 */
	public static function shortcode( $atts ) {
		$atts = shortcode_atts(
			array(
				'height' => '',
			),
			$atts,
			'stylena_slider'
		);

		$args = array();
		if ( '' !== $atts['height'] ) {
			$args['height'] = $atts['height'];
		}

		return self::render( $args );
	}

	/**
	 * Register (not enqueue) the plugin assets.
	 *
	 * @return void
	 */
	public static function register_assets() {
		wp_register_style( 'stylena-core-slider', STYLENA_CORE_URL . 'assets/css/slider.css', array(), STYLENA_CORE_VERSION );
		wp_register_script( 'stylena-core-slider', STYLENA_CORE_URL . 'assets/js/slider.js', array(), STYLENA_CORE_VERSION, true );
	}

	/**
	 * Enqueue the assets for a rendered slider, unless the theme provides them.
	 *
	 * @return void
	 */
	private static function enqueue_assets() {
		$style_handle  = (string) apply_filters( 'stylena_core_slider_style_handle', 'stylena-custom' );
		$script_handle = (string) apply_filters( 'stylena_core_slider_script_handle', 'stylena-custom' );
		$theme_has     = wp_style_is( $style_handle, 'enqueued' ) && wp_script_is( $script_handle, 'enqueued' );

		if ( ! apply_filters( 'stylena_core_slider_enqueue_assets', ! $theme_has ) ) {
			return;
		}
		if ( ! wp_style_is( 'stylena-core-slider', 'registered' ) ) {
			self::register_assets();
		}
		wp_enqueue_style( 'stylena-core-slider' );
		wp_enqueue_script( 'stylena-core-slider' );
	}

	/**
	 * Normalise a CSS length; falls back to the default when invalid.
	 *
	 * @param mixed  $value    Raw value.
	 * @param string $fallback Fallback length.
	 * @return string
	 */
	private static function sanitize_length( $value, $fallback ) {
		$value = trim( (string) $value );
		if ( preg_match( '/^\d+(\.\d+)?(px|em|rem|vh|%)\z/', $value ) ) {
			return $value;
		}
		return $fallback;
	}

	/**
	 * Render the slider.
	 *
	 * @param array $args Optional: posts_per_page, orderby, order, autoplay,
	 *                    autoplay_speed, height, primary_color.
	 * @return string Escaped markup, or '' when there is nothing to show.
	 */
	public static function render( array $args = array() ) {
		if ( ! post_type_exists( self::POST_TYPE ) ) {
			return '';
		}

		$defaults = array(
			'posts_per_page' => -1,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'autoplay'       => true,
			'autoplay_speed' => 4000,
			'height'         => get_theme_mod( 'stylena_slider_height', '500px' ),
			'primary_color'  => get_theme_mod( 'stylena_primary_color', '#c4a574' ),
			'stop_duration'  => get_theme_mod( 'stylena_slider_stop_duration', 1000 ),
		);

		/**
		 * Filter the slider defaults (the theme supplies Customizer values here).
		 *
		 * @param array $defaults Default arguments.
		 */
		$defaults = (array) apply_filters( 'stylena_core_slider_args', $defaults );
		$args     = wp_parse_args( $args, $defaults );

		$args['height']         = self::sanitize_length( $args['height'], '500px' );
		$args['primary_color']  = sanitize_hex_color( (string) $args['primary_color'] );
		$args['primary_color']  = $args['primary_color'] ? $args['primary_color'] : '#c4a574';
		$args['autoplay']       = filter_var( $args['autoplay'], FILTER_VALIDATE_BOOLEAN );
		$args['autoplay_speed'] = max( 500, absint( $args['autoplay_speed'] ) );
		$args['stop_duration']  = absint( $args['stop_duration'] );

		$order = strtoupper( (string) $args['order'] );
		$order = 'DESC' === $order ? 'DESC' : 'ASC';

		$allowed_orderby = array( 'menu_order', 'date', 'title', 'rand', 'ID' );
		$orderby         = in_array( $args['orderby'], $allowed_orderby, true ) ? $args['orderby'] : 'menu_order';

		$posts_per_page = (int) $args['posts_per_page'];
		$posts_per_page = ( $posts_per_page < 1 ) ? -1 : min( $posts_per_page, 50 );

		$query = new \WP_Query(
			array(
				'post_type'           => self::POST_TYPE,
				'post_status'         => 'publish',
				'posts_per_page'      => $posts_per_page,
				'orderby'             => $orderby,
				'order'               => $order,
				'ignore_sticky_posts' => true,
				'no_found_rows'       => true,
			)
		);

		if ( ! $query->have_posts() ) {
			return '';
		}

		self::enqueue_assets();

		$template = locate_template( 'stylena-core/slider.php' );
		if ( ! $template ) {
			$template = STYLENA_CORE_DIR . 'templates/slider.php';
		}

		ob_start();
		include $template;
		wp_reset_postdata();

		return (string) ob_get_clean();
	}
}
