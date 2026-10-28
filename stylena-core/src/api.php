<?php
/**
 * Public API (global functions, guarded so a theme or another copy can define them first).
 *
 * @package StylenaCore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'stylena_core_render_slider' ) ) {
	/**
	 * Render the Stylena image slider.
	 *
	 * Slide data comes from the 'stylena_slider' post type. Defaults can be
	 * supplied (for example from theme mods) through the 'stylena_core_slider_args'
	 * filter. Output is fully escaped.
	 *
	 * @param array $args Optional: posts_per_page, orderby, order, autoplay,
	 *                    autoplay_speed, height, primary_color.
	 * @return string Escaped HTML, or an empty string when there are no slides.
	 */
	function stylena_core_render_slider( array $args = array() ) {
		return \StylenaCore\Frontend\Slider::render( $args );
	}
}
