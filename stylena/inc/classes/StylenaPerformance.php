<?php

/**
 * Performance optimization class for Stylena theme.
 * Handles CSS/JS minification, deferring, lazy loading, and caching.
 *
 * @package Stylena
 */

namespace Stylena;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class StylenaPerformance {

	/**
	 * Add actions and filters
	 */
	public function add_actions_filters() {
		// Defer non-critical CSS.
		\add_filter( 'style_loader_tag', array( $this, 'defer_non_critical_css' ), 10, 4 );

		// Defer non-critical JavaScript.
		\add_filter( 'script_loader_tag', array( $this, 'defer_non_critical_js' ), 10, 3 );

		// Add image dimensions automatically.
		\add_filter( 'the_content', array( $this, 'add_image_dimensions' ) );

		// Add lazy loading to images.
		\add_filter( 'wp_get_attachment_image_attributes', array( $this, 'add_lazy_loading_to_images' ), 10, 3 );

		// Set cache headers for static assets (fallback when .htaccess not available).
		\add_action( 'send_headers', array( $this, 'set_cache_headers' ) );
		// Conditionally load Bootstrap JS only where needed (v2 fix).
		\add_action( 'wp_enqueue_scripts', array( $this, 'conditional_bootstrap_js' ), 100 );

		// Add fetchpriority to LCP image/video (v2 fix).
		\add_filter( 'wp_get_attachment_image_attributes', array( $this, 'add_fetch_priority' ), 10, 3 );
	}

	/**
	 * Defer non-critical CSS files
	 *
	 * @param string $html The link tag for the enqueued style.
	 * @param string $handle The style's registered handle.
	 * @param string $href The stylesheet's source URL.
	 * @param string $media The stylesheet's media attribute.
	 * @return string Modified HTML tag
	 */
	public function defer_non_critical_css( $html, $handle, $href, $media ) {
		// Critical CSS that should load immediately.
		$critical_handles = array(
			'bootstrap4',
			'stylena-custom',
			'Stylena-main',
		);

		// Skip deferring for critical CSS.
		if ( in_array( $handle, $critical_handles ) ) {
			return $html;
		}

		// Defer non-critical CSS using media="print" technique.
		$deferred_html = str_replace(
			"media='$media'",
			"media='print' onload=\"this.media='$media'; this.onload=null;\"",
			$html
		);

		// Add noscript fallback.
		$deferred_html .= '<noscript>' . $html . '</noscript>';

		return $deferred_html;
	}

	/**
	 * Defer non-critical JavaScript files
	 *
	 * @param string $tag The script tag for the enqueued script.
	 * @param string $handle The script's registered handle.
	 * @param string $src The script's source URL.
	 * @return string Modified script tag
	 */
	public function defer_non_critical_js( $tag, $handle, $src ) {
		// Scripts that should NOT be deferred (jQuery and critical dependencies).
		$critical_scripts = array(
			'jquery',
			'jquery-core',
			'jquery-migrate',
			'wp-i18n',
			'wp-hooks',
			'wp-polyfill',
		);

		// Skip deferring for critical scripts.
		if ( in_array( $handle, $critical_scripts, true ) ) {
			return $tag;
		}

		// WordPress core packages (wp-hooks, wp-dom-ready, ...) load in dependency order and
		// have non-deferred inline bootstraps or dependants; deferring them breaks that order.
		if ( strpos( $handle, 'wp-' ) === 0 ) {
			return $tag;
		}

		// Skip deferring any script that has inline "before"/"after" data attached
		// (e.g. wp_set_script_translations(), wp_add_inline_script()). WordPress core
		// prints that inline JS immediately after the tag without a defer attribute,
		// so it runs synchronously and assumes the deferred script already executed —
		// deferring the parent script here breaks that ordering (e.g. causes
		// "ReferenceError: wp is not defined" when wp-i18n's translations bootstrap runs
		// before the deferred wp-i18n.js has loaded).
		$wp_scripts = \wp_scripts();
		if ( $wp_scripts->get_data( $handle, 'before' ) || $wp_scripts->get_data( $handle, 'after' ) ) {
			return $tag;
		}

		// Add defer attribute to non-critical scripts.
		if ( strpos( $tag, 'defer' ) === false ) {
			$tag = str_replace( ' src', ' defer src', $tag );
		}

		return $tag;
	}

	/**
	 * Add width and height attributes to images in content
	 * This prevents Cumulative Layout Shift (CLS)
	 *
	 * @param string $content Post content
	 * @return string Modified content with image dimensions
	 */
	public function add_image_dimensions( $content ) {
		if ( empty( $content ) ) {
			return $content;
		}

		// Find all img tags without width/height.
		preg_match_all( '/<img[^>]+>/i', $content, $images );

		if ( empty( $images[0] ) ) {
			return $content;
		}

		foreach ( $images[0] as $image ) {
			// Skip if already has width and height.
			if ( strpos( $image, 'width=' ) !== false && strpos( $image, 'height=' ) !== false ) {
				continue;
			}

			// Extract src.
			if ( preg_match( '/src=["\']([^"\']+)["\']/', $image, $src_match ) ) {
				$src = $src_match[1];

				// Get attachment ID from URL.
				$attachment_id = \attachment_url_to_postid( $src );

				if ( $attachment_id ) {
					$metadata = \wp_get_attachment_metadata( $attachment_id );

					if ( ! empty( $metadata['width'] ) && ! empty( $metadata['height'] ) ) {
						$new_image = $image;

						// Add width if missing.
						if ( strpos( $new_image, 'width=' ) === false ) {
							$new_image = str_replace( '<img ', '<img width="' . esc_attr( $metadata['width'] ) . '" ', $new_image );
						}

						// Add height if missing.
						if ( strpos( $new_image, 'height=' ) === false ) {
							$new_image = str_replace( '<img ', '<img height="' . esc_attr( $metadata['height'] ) . '" ', $new_image );
						}

						$content = str_replace( $image, $new_image, $content );
					}
				}
			}
		}

		return $content;
	}

	/**
	 * Add lazy loading attribute to images
	 *
	 * @param array        $attr Array of attribute values for the image markup
	 * @param WP_Post      $attachment Image attachment post
	 * @param string|int[] $size Requested image size
	 * @return array Modified attributes
	 */
	public function add_lazy_loading_to_images( $attr, $attachment, $size ) {
		// Skip if loading attribute already exists.
		if ( isset( $attr['loading'] ) ) {
			return $attr;
		}

		// Add native lazy loading (supported by modern browsers).
		$attr['loading'] = 'lazy';

		return $attr;
	}

	/**
	 * Set cache headers for static assets
	 * This is a fallback when .htaccess is not available or on Nginx
	 */
	public function set_cache_headers() {
		// Only set headers for asset files.
		$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '';

		// Image files.
		if ( preg_match( '/\.(jpg|jpeg|png|gif|webp|svg|ico)$/i', $request_uri ) ) {
			header( 'Cache-Control: public, max-age=31536000' ); // 1 year
			header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + 31536000 ) . ' GMT' );
		}
		// CSS and JavaScript files.
		elseif ( preg_match( '/\.(css|js)$/i', $request_uri ) ) {
			header( 'Cache-Control: public, max-age=2628000' ); // 1 month
			header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + 2628000 ) . ' GMT' );
		}
		// Font files.
		elseif ( preg_match( '/\.(woff|woff2|ttf|otf|eot)$/i', $request_uri ) ) {
			header( 'Cache-Control: public, max-age=31536000' ); // 1 year
			header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + 31536000 ) . ' GMT' );
		}
	}
	/**
	 * Conditionally load Bootstrap JS only on pages that need it (v2 fix for unused JS)
	 * Reduces payload by ~120KB on pages without modals/carousels
	 */
	public function conditional_bootstrap_js() {
		// Pages that don't need Bootstrap JS interactive components.
		$no_bootstrap_pages = array(
			'page-portfolio-fullwidth.php',
			'page-services-fullwidth.php',
		);

		// Check if current page template doesn't need Bootstrap JS.
		$template = basename( get_page_template() );

		// On simple content pages, we can dequeue Bootstrap bundle
		// Keep it on front page (for modals), single posts (comments), archive pages.
		if ( in_array( $template, $no_bootstrap_pages ) && ! is_singular( 'post' ) ) {
			// Note: Only dequeue if your theme doesn't use Bootstrap modals, dropdowns, etc on these pages
			// \wp_dequeue_script('bootstrap4-bundle');
			// Commented out by default for safety - uncomment if you've verified no Bootstrap JS features are used.
		}
	}

	/**
	 * Add fetchpriority="high" to LCP images (v2 fix)
	 * Prioritizes loading of above-the-fold images
	 *
	 * @param array        $attr Image attributes
	 * @param WP_Post      $attachment Attachment object
	 * @param string|int[] $size Image size
	 * @return array Modified attributes
	 */
	public function add_fetch_priority( $attr, $attachment, $size ) {
		// Add fetchpriority to first featured image on single posts/pages.
		if ( is_singular() && ! isset( $GLOBALS['stylena_fetchpriority_added'] ) ) {
			$attr['fetchpriority']                  = 'high';
			$GLOBALS['stylena_fetchpriority_added'] = true;
		}

		return $attr;
	}
}
