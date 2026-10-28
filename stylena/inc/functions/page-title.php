<?php
/**
 * Page title helper for full-width templates.
 *
 * @package Stylena
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'stylena_content_has_banner' ) ) {
	/**
	 * Whether the page content contains an image or cover block usable as a title banner.
	 *
	 * @param string $content Raw post content.
	 * @return bool
	 */
	function stylena_content_has_banner( $content ) {
		return (bool) preg_match( '/<img\b|wp-block-image|wp-block-cover|<!--\s*wp:(image|cover)\b/i', (string) $content );
	}
}

if ( ! function_exists( 'stylena_render_page_title' ) ) {
	/**
	 * Output the page H1. Overlay style when the content has a banner image, otherwise a
	 * normal in-flow centred heading.
	 *
	 * @param string $content     Raw post content.
	 * @param string $font_family CSS font-family value for the heading.
	 * @param bool   $absolute    Whether the overlay is absolutely positioned.
	 */
	function stylena_render_page_title( $content, $font_family, $absolute = true ) {
		$title = get_the_title();
		$style = $font_family ? ' style="font-family: ' . esc_attr( $font_family ) . ';"' : '';

		if ( stylena_content_has_banner( $content ) ) {
			echo '<div class="' . ( $absolute ? 'position-absolute ' : '' ) . 'start-50 translate-middle text-center title-overlay">';
			echo '<h1 class="h3 responsive-title text-white title-overlay-text"' . $style . '>' . wp_kses_post( $title ) . '</h1>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $style escaped above.
			echo '</div>';
			return;
		}

		echo '<div class="container text-center stylena-page-title-wrap">';
		echo '<h1 class="h3 responsive-title stylena-page-title"' . $style . '>' . wp_kses_post( $title ) . '</h1>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $style escaped above.
		echo '</div>';
	}
}
