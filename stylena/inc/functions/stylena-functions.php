<?php

/**
 * Stylena Theme Custom Functions
 * Additional customizations for the Stylena theme
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add custom body classes for different page types
 */
function stylena_body_classes( $classes ) {
	if ( is_front_page() ) {
		$classes[] = 'homepage';
		$classes[] = 'full-width';
	}
	return $classes;
}
add_filter( 'body_class', 'stylena_body_classes' );

/**
 * Get the configured price symbol
 */
function stylena_get_price_symbol() {
	return get_theme_mod( 'stylena_services_price_symbol', '£' );
}

/**
 * Format a price with the configured symbol
 */
function stylena_format_price( $price ) {
	if ( empty( $price ) ) {
		return '';
	}

	$symbol      = stylena_get_price_symbol();
	$clean_price = trim( $price );

	// If price already starts with a currency symbol, return as is.
	if ( preg_match( '/^[£$€¥₹¢₽₩₪₦₡₴₨₱₵₸₴₼₾]/', $clean_price ) ) {
		return $clean_price;
	}

	return $symbol . $clean_price;
}

/**
 * Add theme support for custom logo
 */
function stylena_custom_logo_setup() {
	$defaults = array(
		'height'               => 100,
		'width'                => 400,
		'flex-height'          => true,
		'flex-width'           => true,
		'header-text'          => array( 'site-title', 'site-description' ),
		'unlink-homepage-logo' => true,
	);
	add_theme_support( 'custom-logo', $defaults );
}
add_action( 'after_setup_theme', 'stylena_custom_logo_setup' );

/**
 * Custom walker for top navigation menus
 */
class Stylena_Top_Nav_Walker extends Walker_Nav_Menu {


	// Start Level.
	function start_lvl( &$output, $depth = 0, $args = null ) {
		$indent  = str_repeat( "\t", $depth );
		$output .= "\n$indent<ul class=\"dropdown-menu\">\n";
	}

	// End Level.
	function end_lvl( &$output, $depth = 0, $args = null ) {
		$indent  = str_repeat( "\t", $depth );
		$output .= "$indent</ul>\n";
	}

	// Start Element.
	function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Core WordPress hook applied by the nav menu walker.
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Core WordPress hook applied by the nav menu walker.
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names . '>';

		$attributes  = ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) . '"' : '';
		$attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '"' : '';
		$attributes .= ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) . '"' : '';
		$attributes .= ! empty( $item->url ) ? ' href="' . esc_attr( $item->url ) . '"' : '';

		$item_output  = isset( $args->before ) ? $args->before : '';
		$item_output .= '<a' . $attributes . '>';
		$item_output .= ( isset( $args->link_before ) ? $args->link_before : '' ) . apply_filters( 'the_title', $item->title, $item->ID ) . ( isset( $args->link_after ) ? $args->link_after : '' ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Core WordPress hook applied by the nav menu walker.
		$item_output .= '</a>';
		$item_output .= isset( $args->after ) ? $args->after : '';

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Core WordPress hook applied by the nav menu walker.
	}

	// End Element.
	function end_el( &$output, $item, $depth = 0, $args = null ) {
		$output .= "</li>\n";
	}
}

/**
 * Add custom CSS for admin area
 */
function stylena_admin_styles() {
	echo '<style>
    .appearance_page_Stylena-help .wrap h1:first-child {
            background: linear-gradient(135deg, #bc9c25, #b8941f);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>';
}
add_action( 'admin_head', 'stylena_admin_styles' );

/**
 * Customize excerpt length
 */
function stylena_excerpt_length( $length ) {
	return 30;
}
add_filter( 'excerpt_length', 'stylena_excerpt_length', 999 );

/**
 * Customize excerpt more
 */
function stylena_excerpt_more( $more ) {
	return '...';
}
add_filter( 'excerpt_more', 'stylena_excerpt_more' );

/**
 * Add custom image sizes
 */
function stylena_image_sizes() {
	add_image_size( 'stylena-hero', 1920, 1080, true );
	add_image_size( 'stylena-service', 500, 700, true );
	add_image_size( 'stylena-team', 400, 600, true ); // Updated for 700x1050 portrait images (2:3 aspect ratio).
}
add_action( 'after_setup_theme', 'stylena_image_sizes' );

/**
 * Add custom widgets
 */
function stylena_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Homepage Hero', 'stylena' ),
			'id'            => 'homepage-hero',
			'description'   => esc_html__( 'Add widgets to appear in the homepage hero section.', 'stylena' ),
			'before_widget' => '<section id="%1$s" class="widget hero-widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title hero-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Homepage Services', 'stylena' ),
			'id'            => 'homepage-services',
			'description'   => esc_html__( 'Add widgets to appear in the homepage services section.', 'stylena' ),
			'before_widget' => '<section id="%1$s" class="widget services-widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title service-title">',
			'after_title'   => '</h3>',
		)
	);
}
add_action( 'widgets_init', 'stylena_widgets_init' );

/**
 * Helper function to get service categories for a service
 */
function stylena_get_service_categories( $service_id = null, $format = 'names' ) {
	if ( ! $service_id ) {
		global $post;
		$service_id = $post->ID;
	}

	if ( ! taxonomy_exists( 'service_category' ) ) {
		return array();
	}

	$categories = get_the_terms( $service_id, 'service_category' );

	if ( ! $categories || is_wp_error( $categories ) ) {
		return array();
	}

	switch ( $format ) {
		case 'names':
			return array_map(
				function ( $cat ) {
					return $cat->name;
				},
				$categories
			);
		case 'slugs':
			return array_map(
				function ( $cat ) {
					return $cat->slug;
				},
				$categories
			);
		case 'objects':
		default:
			return $categories;
	}
}

/**
 * Display service categories as comma-separated list
 */
function stylena_display_service_categories( $service_id = null, $before = '', $after = '', $separator = ', ' ) {
	$categories = stylena_get_service_categories( $service_id, 'names' );

	if ( empty( $categories ) ) {
		return '';
	}

	return $before . implode( $separator, array_map( 'esc_html', $categories ) ) . $after;
}

/**
 * Admin notice for reviews configuration
 */
// Admin notices for reviews removed to avoid debug output in production.

// Reviews cache: administrators use the "Clear Reviews Cache" button in the Customizer (see below).

/**
 * Google Reviews: fetch from Google Places API, cache, and render helper
 */
function stylena_fetch_google_reviews( $place_id, $api_key, $limit = 3 ) {
	// allow API key via constant for improved secrecy if set in wp-config.php.
	if ( empty( $api_key ) ) {
		$api_key = stylena_get_places_key();
	}

	// No request to Google (and no cached Google data) without the owner's opt-in.
	if ( ! get_theme_mod( 'stylena_reviews_external_optin', false ) ) {
		return array();
	}

	if ( empty( $place_id ) || empty( $api_key ) ) {
		return array();
	}

	$cache_key = 'stylena_google_reviews_' . md5( $place_id . $api_key );
	$cached    = get_transient( $cache_key );
	if ( false !== $cached ) {
		return $cached;
	}

	// Request only safe fields; avoid deprecated/invalid fields which may make API reject the request.
	$fields   = 'reviews,rating,name';
	$endpoint = 'https://maps.googleapis.com/maps/api/place/details/json?place_id=' . urlencode( $place_id ) . '&fields=' . urlencode( $fields ) . '&key=' . urlencode( $api_key );

	$response = wp_safe_remote_get( $endpoint, array( 'timeout' => 12 ) );

	if ( is_wp_error( $response ) ) {
		error_log( 'Stylena: Google Places request failed: ' . $response->get_error_message() );
		return array();
	}

	$code = wp_remote_retrieve_response_code( $response );
	$body = wp_remote_retrieve_body( $response );
	$data = json_decode( $body, true );

	if ( 200 !== $code ) {
		error_log( 'Stylena: Google Places HTTP error code: ' . $code );
		return array();
	}

	$status = isset( $data['status'] ) ? $data['status'] : '';
	if ( 'OK' !== $status ) {
		$err = isset( $data['error_message'] ) ? $data['error_message'] : 'No error_message provided';
		error_log( 'Stylena: Google Places API status: ' . $status . ' - ' . substr( sanitize_text_field( $err ), 0, 200 ) );
		return array();
	}

	$reviews    = $data['result']['reviews'] ?? array();
	$place_name = isset( $data['result']['name'] ) ? sanitize_text_field( $data['result']['name'] ) : '';
	$output     = array();
	$count      = 0;
	foreach ( $reviews as $r ) {
		if ( $count >= $limit ) {
			break;
		}
		$output[] = array(
			'author_name'               => sanitize_text_field( $r['author_name'] ?? '' ),
			'rating'                    => intval( $r['rating'] ?? 0 ),
			'text'                      => wp_kses_post( $r['text'] ?? '' ),
			'time'                      => intval( $r['time'] ?? 0 ),
			'relative_time_description' => sanitize_text_field( $r['relative_time_description'] ?? '' ),
		);
		++$count;
	}

	// Cache for 12 hours.
	$cache_value = array(
		'place_name' => $place_name,
		'reviews'    => $output,
	);
	set_transient( $cache_key, $cache_value, 12 * HOUR_IN_SECONDS );

	return $cache_value;
}

/**
 * Generic placeholder reviews shown only when no real or custom reviews exist.
 *
 * @return array[] Review arrays in the same shape as fetched reviews.
 */
function stylena_get_sample_reviews() {
	$samples = array(
		array( __( 'Sample Client', 'stylena' ), __( 'This is a sample review. Replace it with feedback from your own clients to build trust with visitors.', 'stylena' ) ),
		array( __( 'Sample Client', 'stylena' ), __( 'Placeholder text showing how a short testimonial looks. Great service and a friendly team could go here.', 'stylena' ) ),
		array( __( 'Sample Client', 'stylena' ), __( 'Another example review. Edit these cards in the Customizer or install the Site Reviews plugin.', 'stylena' ) ),
	);

	$reviews = array();
	foreach ( $samples as $sample ) {
		$reviews[] = array(
			'author_name'               => $sample[0],
			'rating'                    => 5,
			'text'                      => $sample[1],
			'time'                      => 0,
			'relative_time_description' => '',
		);
	}

	return $reviews;
}

/**
 * Render Google Reviews section. Uses theme_mod options if available.
 */
function stylena_render_google_reviews() {
	$enable = get_theme_mod( 'stylena_reviews_enable', true );
	if ( ! $enable ) {
		return;
	}

	$place_id = get_theme_mod( 'stylena_reviews_place_id', '' );
	$api_key  = stylena_get_places_key();
	$limit    = intval( get_theme_mod( 'stylena_reviews_limit', 3 ) );

	$reviews = stylena_fetch_google_reviews( $place_id, $api_key, $limit );

	// If fetch returned cache structure with place_name and reviews, extract them early.
	$place_name = '';
	if ( is_array( $reviews ) && isset( $reviews['reviews'] ) && isset( $reviews['place_name'] ) ) {
		$place_name = $reviews['place_name'];
		$reviews    = $reviews['reviews'];
	}

	// Only keep 5-star reviews. This ensures we display only perfect-rating reviews.
	if ( ! empty( $reviews ) && is_array( $reviews ) ) {
		$reviews = array_filter(
			$reviews,
			function ( $r ) {
				return intval( $r['rating'] ?? 0 ) === 5;
			}
		);
		// Reindex and trim to the requested limit.
		$reviews = array_values( $reviews );
		if ( count( $reviews ) > $limit ) {
			$reviews = array_slice( $reviews, 0, $limit );
		}
	}

	// Fallback: if no remote reviews, allow custom_text entries from customizer.
	if ( empty( $reviews ) ) {
		$custom = array();
		// Only include fallback custom reviews that are 5-star to match requirement.
		for ( $i = 1; $i <= $limit; $i++ ) {
			$text       = get_theme_mod( "stylena_reviews_custom_text_{$i}", '' );
			$author     = get_theme_mod( "stylena_reviews_custom_author_{$i}", '' );
			$rating_val = intval( get_theme_mod( "stylena_reviews_custom_rating_{$i}", 5 ) );
			if ( ! empty( trim( $text ) ) && 5 === $rating_val ) {
				$custom[] = array(
					'author_name'               => sanitize_text_field( $author ),
					'rating'                    => 5,
					'text'                      => wp_kses_post( $text ),
					'time'                      => 0,
					'relative_time_description' => '',
				);
			}
		}
		$reviews = $custom;
	}

	// Last resort: static sample cards so a fresh install or demo does not look empty.
	$is_sample = false;
	if ( empty( $reviews ) ) {
		$reviews   = stylena_get_sample_reviews();
		$is_sample = true;
	}

	if ( empty( $reviews ) ) {
		return;
	}

	// Output markup - keep matched to CSS in theme.
	echo '<section class="reviews-section py-2">';
	echo '<div class="container">';
	// place_name (if any) was already extracted from the fetch earlier.

	// Allow admin overrides from Customizer (place name, total reviews, avg rating).
	$override_place = get_theme_mod( 'stylena_reviews_place_name', '' );
	if ( ! empty( $override_place ) ) {
		$place_name = sanitize_text_field( $override_place );
	}

	// Compute stats: total reviews and average rating.
	$total_reviews = is_array( $reviews ) ? count( $reviews ) : 0;
	$avg_rating    = 0;
	if ( $total_reviews > 0 ) {
		$sum = 0;
		foreach ( $reviews as $r ) {
			$sum += intval( $r['rating'] ?? 0 );
		}
		$avg_rating = round( $sum / $total_reviews, 1 );
	}

	// Apply overrides if provided in Customizer.
	$override_total = get_theme_mod( 'stylena_reviews_total_reviews', '' );
	if ( '' !== $override_total && is_numeric( $override_total ) ) {
		$total_reviews = intval( $override_total );
	}
	$override_avg = get_theme_mod( 'stylena_reviews_avg_rating', '' );
	if ( '' !== $override_avg && is_numeric( $override_avg ) ) {
		$avg_rating = floatval( $override_avg );
	}

	echo '<div class="mb-4">';
	echo '<div class="col-12">';
	echo '<div class="reviews-header d-flex align-items-center justify-content-between">';
	// Business/place name (if available) then the Reviews section title.
	$header_left = '';

	$header_left .= '<h2 class="reviews-title" style="font-family: ' . esc_attr( stylena_get_font_family( get_theme_mod( 'stylena_heading_font', 'montserrat' ) ) ) . '; display:inline-block; margin:0 0 10px 20px;">' . esc_html( get_theme_mod( 'stylena_reviews_title', __( 'WHAT OUR CLIENTS SAY', 'stylena' ) ) ) . '</h2>';
	echo '<div class="reviews-header-left d-flex align-items-center">' . wp_kses_post( $header_left ) . '</div>';

	echo '<div class="reviews-stats text-end">';
	// Stack place name, total reviews and average rating vertically (each its own block).
	if ( ! empty( $place_name ) ) {
		echo '<div class="reviews-business-name-stats">' . esc_html( $place_name ) . '</div>';
	}
	if ( ! $is_sample ) {
		/* translators: %d: number of reviews */
		echo '<div class="reviews-count-stats">' . esc_html( sprintf( _n( '%d review', '%d reviews', $total_reviews, 'stylena' ), $total_reviews ) ) . '</div>';
	}
	if ( $total_reviews > 0 && ! $is_sample ) {
		// Render the average as visual stars (rounded to nearest integer).
		$rounded_stars = intval( round( $avg_rating ) );
		if ( $rounded_stars < 0 ) {
			$rounded_stars = 0;
		}
		if ( $rounded_stars > 5 ) {
			$rounded_stars = 5;
		}
		$stars_html = str_repeat( '★', $rounded_stars );
		// Output stars and show numeric average visibly, with a screen-reader label as well.
		echo '<div class="reviews-average-stats" aria-hidden="false">' . esc_html( $stars_html ) . ' <span class="reviews-average-number">' . esc_html( $avg_rating ) . '</span><span class="sr-only">' . esc_html( $avg_rating ) . ' out of 5</span></div>';
	}
	echo '</div>'; // reviews-stats.
	echo '</div>'; // reviews-header.
	echo '</div></div>';

	if ( $is_sample && current_user_can( 'edit_theme_options' ) ) {
		echo '<p class="stylena-sample-reviews-notice small text-muted mb-3" role="note">' . esc_html__( 'Sample reviews shown. Replace them under Customize > Reviews Section, or install Site Reviews.', 'stylena' ) . '</p>';
	}

	// Reviews slider with navigation buttons.
	echo '<div class="reviews-slider-container">';

	echo '<div class="reviews-slider" data-index="0">';
	echo '<div class="reviews-wrapper">';
	foreach ( $reviews as $rev ) {
		$rating = intval( $rev['rating'] ?? 5 );
		$stars  = str_repeat( '★', $rating );
		$author = esc_html( $rev['author_name'] ?? '' );
		// Prepare review text and trim to 30 words if longer.
		$raw_text   = wp_kses_post( $rev['text'] ?? '' );
		$plain_text = wp_strip_all_tags( $raw_text );
		if ( str_word_count( $plain_text ) > 30 ) {
			$plain_text = wp_trim_words( $plain_text, 30, '...' );
		}
		$text = esc_html( $plain_text );
		$date = '';
		if ( ! empty( $rev['time'] ) ) {
			$date = date_i18n( get_option( 'date_format' ), $rev['time'] );
		}

		echo '<div class="col-lg-4 col-md-6 mb-4 review-slide">';
		echo '<div class="review-card pl-4 h-100">';
		echo '<div class="review-head d-flex align-items-center mb-3">';
		// Use inline SVG image (google.svg) as the review logo.
		if ( ! $is_sample ) {
			echo '<img src="' . esc_url( get_theme_file_uri( '/img/google.svg' ) ) . '" alt="Google" class="review-logo me-2" />';
		}
		echo '<div class="review-stars">' . esc_html( $stars ) . '</div>';
		if ( $is_sample ) {
			echo '<span class="review-sample-label ms-2">' . esc_html__( 'Sample review', 'stylena' ) . '</span>';
		}
		echo '</div>'; // review-head.
		echo '<div class="review-body">' . wp_kses_post( $text ) . '</div>';
		echo '<div class="review-meta"><div class="review-author">' . wp_kses_post( $author ) . '</div>';
		echo '<div class="review-date">' . esc_html( $date ) . '</div></div>';
		echo '</div></div>';
	}
	echo '</div>'; // reviews-wrapper.
	echo '</div>'; // reviews-slider.

	// Navigation buttons at the bottom.
	echo '<div class="reviews-navigation desktop-btns">';
	echo '<button class="reviews-prev swiper-arrow-btn" tabindex="0" role="button" aria-label="Previous reviews">';
	echo '<img src="' . esc_url( get_theme_file_uri( '/img/arrow.svg' ) ) . '" alt="Previous" class="arrow-svg flipped" />';
	echo '</button>';
	echo '<button class="reviews-next swiper-arrow-btn" tabindex="0" role="button" aria-label="Next reviews">';
	echo '<img src="' . esc_url( get_theme_file_uri( '/img/arrow.svg' ) ) . '" alt="Next" class="arrow-svg" />';
	echo '</button>';
	echo '</div>';

	echo '</div>'; // reviews-slider-container.
	echo '</div></section>';
}

/**
 * Is the Site Reviews plugin active and its shortcode available?
 *
 * @return bool
 */
function stylena_site_reviews_active() {
	return shortcode_exists( 'site_reviews' );
}

/**
 * Render the Reviews section (shared by the home page and the Reviews page template).
 *
 * When the Site Reviews plugin is active (and "Use Site Reviews plugin" is on in the
 * Customizer) its shortcode output is shown inside the theme section. Otherwise the
 * built-in Google / static-card renderer is used.
 *
 * @return void
 */
function stylena_render_reviews() {
	if ( ! get_theme_mod( 'stylena_reviews_enable', true ) ) {
		stylena_render_google_reviews(); // Prints only an admin-only HTML comment.
		return;
	}

	if ( get_theme_mod( 'stylena_reviews_use_plugin', true ) && stylena_site_reviews_active() ) {
		$limit = max( 1, min( 20, absint( get_theme_mod( 'stylena_reviews_limit', 3 ) ) ) );
		$title = get_theme_mod( 'stylena_reviews_title', __( 'WHAT OUR CLIENTS SAY', 'stylena' ) );

		echo '<section class="reviews-section stylena-reviews-plugin py-2">';
		echo '<div class="container">';
		if ( '' !== $title ) {
			echo '<h2 class="reviews-title mb-4">' . esc_html( $title ) . '</h2>';
		}
		// Plugin output is already escaped by Site Reviews; the shortcode string is built from a clamped integer only.
		echo do_shortcode( '[site_reviews display="' . $limit . '"]' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '</div></section>';
		return;
	}

	stylena_render_google_reviews();
}

/**
 * Render Advice Section with Contact Form 7 integration
 */
function stylena_render_advice_section() {
	// Get the Contact Form 7 shortcode ID from customizer.
	$cf7_shortcode    = get_theme_mod( 'stylena_advice_cf7_shortcode', '' );
	$section_title    = get_theme_mod( 'stylena_advice_title', 'NEED ADVICE?' );
	$section_subtitle = get_theme_mod( 'stylena_advice_subtitle', 'We offer complementary online and in-person consultations. We are here to help you make the right choices.' );
	$button_text      = get_theme_mod( 'stylena_advice_button_text', 'Send a request' );
	$terms_text       = get_theme_mod( 'stylena_advice_terms_text', 'By clicking to send, I agree to the terms and conditions' );
	$enable_section   = get_theme_mod( 'stylena_advice_enable', true );

	if ( ! $enable_section ) {
		return;
	}

	echo '<section class="advice-section py-5 mb-5">';
	echo '<div class="container">';
	echo '<div class="row align-items-center">';

	// Left side - Content.
	echo '<div class="col-lg-6 col-md-6 mb-4 mb-md-0">';
	echo '<div class="advice-content">';
	echo '<h2 class="advice-title mb-3" style="font-family: ' . esc_attr( stylena_get_font_family( get_theme_mod( 'stylena_heading_font', 'montserrat' ) ) ) . ';">' . esc_html( $section_title ) . '</h2>';
	echo '<p class="advice-subtitle mb-4">' . esc_html( $section_subtitle ) . '</p>';

	// Contact form - either CF7 shortcode or custom form.
	if ( ! empty( $cf7_shortcode ) && function_exists( 'do_shortcode' ) ) {
		echo '<div class="advice-form cf7-form">';
		echo do_shortcode( $cf7_shortcode );
		echo '</div>';
	} else {
		// Fallback custom form if CF7 is not available.
		echo '<div class="advice-form custom-form">';
		echo '<form class="advice-contact-form" method="post" action="">';
		echo '<div class="form-group mb-3">';
		echo '<input type="text" class="form-control" name="advice_name" placeholder="' . esc_attr__( 'Name', 'stylena' ) . '" aria-label="' . esc_attr__( 'Name', 'stylena' ) . '" required>';
		echo '</div>';
		echo '<div class="form-group mb-3">';
		echo '<input type="email" class="form-control" name="advice_email" placeholder="' . esc_attr__( 'Email', 'stylena' ) . '" aria-label="' . esc_attr__( 'Email', 'stylena' ) . '" required>';
		echo '</div>';
		echo '<div class="form-group mb-3">';
		echo '<input type="tel" class="form-control" name="advice_phone" placeholder="+1 000 000 0000" aria-label="' . esc_attr__( 'Phone', 'stylena' ) . '" required>';
		echo '</div>';
		echo '<div class="form-group mb-3">';
		echo '<div class="form-check">';
		echo '<input type="checkbox" class="form-check-input" name="advice_terms" id="advice_terms" required>';
		echo '<label class="form-check-label advice-terms-label" for="advice_terms">';
		echo esc_html( $terms_text );
		echo '</label>';
		echo '</div>';
		echo '</div>';
		echo '<button type="submit" class="btn advice-submit-btn">' . esc_html( $button_text ) . '</button>';
		wp_nonce_field( 'advice_form_submit', 'advice_nonce' );
		echo '</form>';
		echo '</div>';
	}

	echo '</div>'; // advice-content.
	echo '</div>'; // col-lg-6.

	// Right side - Image.
	echo '<div class="col-lg-6 col-md-6">';
	echo '<div class="advice-image">';
	$advice_image = get_theme_mod( 'stylena_advice_image', get_theme_file_uri( '/img/placeholders/advice-placeholder.svg' ) );
	echo '<img src="' . esc_url( $advice_image ) . '" alt="Consultation" class="img-fluid rounded">';
	echo '</div>';
	echo '</div>'; // col-lg-6.

	echo '</div>'; // row.
	echo '</div>'; // container.
	echo '</section>';
}

/**
 * Handle advice form submission (fallback when CF7 is not available)
 */
function stylena_handle_advice_form_submission() {
	if ( isset( $_POST['advice_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['advice_nonce'] ) ), 'advice_form_submit' ) ) {
		$name           = sanitize_text_field( wp_unslash( $_POST['advice_name'] ?? '' ) );
		$email          = sanitize_email( wp_unslash( $_POST['advice_email'] ?? '' ) );
		$phone          = sanitize_text_field( wp_unslash( $_POST['advice_phone'] ?? '' ) );
		$terms_accepted = isset( $_POST['advice_terms'] ) ? 1 : 0;

		if ( ! empty( $name ) && ! empty( $email ) && ! empty( $phone ) && $terms_accepted ) {
			// Send email to admin.
			$admin_email = get_option( 'admin_email' );
			$subject     = 'New Advice Request from ' . get_bloginfo( 'name' );
			$message     = "New advice request received:\n\n";
			$message    .= 'Name: ' . $name . "\n";
			$message    .= 'Email: ' . $email . "\n";
			$message    .= 'Phone: ' . $phone . "\n";
			$message    .= "Terms Accepted: Yes\n";
			$message    .= 'Date: ' . date( 'Y-m-d H:i:s' ) . "\n";

			$headers = array( 'Content-Type: text/plain; charset=UTF-8' );

			if ( wp_mail( $admin_email, $subject, $message, $headers ) ) {
				// Success message.
				add_action(
					'wp_footer',
					function () {
						echo '<script>
                        document.addEventListener("DOMContentLoaded", function() {
                            alert("Thank you! Your advice request has been submitted successfully. We will contact you soon.");
                        });
                    </script>';
					}
				);
			}
		}
	}
}
add_action( 'init', 'stylena_handle_advice_form_submission' );

/**
 * Add Advice Section settings to theme customizer
 */
function stylena_advice_customizer( $wp_customize ) {
	// Add Advice Section under Stylena Theme Options Panel.
	$wp_customize->add_section(
		'stylena_advice_section',
		array(
			'title'       => __( 'Advice Section', 'stylena' ),
			'description' => __( 'Configure the advice/consultation section settings', 'stylena' ),
			'panel'       => 'stylena_panel',
			'priority'    => 47,
		)
	);

	// Enable/Disable Advice Section.
	$wp_customize->add_setting(
		'stylena_advice_enable',
		array(
			'default'           => true,
			'sanitize_callback' => 'wp_validate_boolean',
		)
	);
	$wp_customize->add_control(
		'stylena_advice_enable',
		array(
			'label'    => __( 'Enable Advice Section', 'stylena' ),
			'section'  => 'stylena_advice_section',
			'type'     => 'checkbox',
			'priority' => 10,
		)
	);

	// Section Title.
	$wp_customize->add_setting(
		'stylena_advice_title',
		array(
			'default'           => 'NEED ADVICE?',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'stylena_advice_title',
		array(
			'label'    => __( 'Section Title', 'stylena' ),
			'section'  => 'stylena_advice_section',
			'type'     => 'text',
			'priority' => 20,
		)
	);

	// Section Subtitle.
	$wp_customize->add_setting(
		'stylena_advice_subtitle',
		array(
			'default'           => __( 'We offer complementary online and in-person consultations. We are here to help you make the right choices.', 'stylena' ),
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'stylena_advice_subtitle',
		array(
			'label'    => __( 'Section Subtitle', 'stylena' ),
			'section'  => 'stylena_advice_section',
			'type'     => 'textarea',
			'priority' => 30,
		)
	);

	// Contact Form 7 Shortcode.
	$wp_customize->add_setting(
		'stylena_advice_cf7_shortcode',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'stylena_advice_cf7_shortcode',
		array(
			'label'       => __( 'Contact Form 7 Shortcode', 'stylena' ),
			'description' => __( 'Enter the Contact Form 7 shortcode (e.g., [contact-form-7 id="123" title="Advice Form"]). Leave empty to use default form.', 'stylena' ),
			'section'     => 'stylena_advice_section',
			'type'        => 'text',
			'priority'    => 40,
		)
	);

	// Button Text.
	$wp_customize->add_setting(
		'stylena_advice_button_text',
		array(
			'default'           => __( 'Send a request', 'stylena' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'stylena_advice_button_text',
		array(
			'label'    => __( 'Button Text', 'stylena' ),
			'section'  => 'stylena_advice_section',
			'type'     => 'text',
			'priority' => 50,
		)
	);

	// Terms Text.
	$wp_customize->add_setting(
		'stylena_advice_terms_text',
		array(
			'default'           => __( 'By clicking to send, I agree to the terms and conditions', 'stylena' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'stylena_advice_terms_text',
		array(
			'label'    => __( 'Terms and Conditions Text', 'stylena' ),
			'section'  => 'stylena_advice_section',
			'type'     => 'text',
			'priority' => 60,
		)
	);

	// Advice Section Image.
	$wp_customize->add_setting(
		'stylena_advice_image',
		array(
			'default'           => get_theme_file_uri( '/img/placeholders/advice-placeholder.svg' ),
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'stylena_advice_image',
			array(
				'label'    => __( 'Section Image', 'stylena' ),
				'section'  => 'stylena_advice_section',
				'priority' => 70,
			)
		)
	);
}
add_action( 'customize_register', 'stylena_advice_customizer' );

/**
 * AJAX handler: clear reviews cache (admin only)
 */
function stylena_ajax_clear_reviews_cache() {
	// Only allow administrators.
	if ( ! function_exists( 'current_user_can' ) || ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( 'permission_denied' );
	}

	// Verify nonce.
	$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';
	if ( ! function_exists( 'wp_verify_nonce' ) || ! wp_verify_nonce( $nonce, 'stylena_clear_reviews_cache' ) ) {
		wp_send_json_error( 'invalid_nonce' );
	}

	global $wpdb;
	// Delete transients matching our key pattern.
	$like    = $wpdb->esc_like( '_transient_stylena_google_reviews_' ) . '%';
	$deleted = $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s", $like ) );

	// Also delete any site transients for multisite.
	if ( is_multisite() ) {
		$like2 = $wpdb->esc_like( '_site_transient_stylena_google_reviews_' ) . '%';
		$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->sitemeta} WHERE meta_key LIKE %s", $like2 ) );
	}

	wp_send_json_success( array( 'deleted' => intval( $deleted ) ) );
}
add_action( 'wp_ajax_stylena_clear_reviews_cache', 'stylena_ajax_clear_reviews_cache' );

/**
 * Tell WordPress the intended upload limit (in bytes).
 */
function stylena_upload_size_limit( $size ) {
	$upload_mb = 128;
	return $upload_mb * 1024 * 1024;
}
add_filter( 'upload_size_limit', 'stylena_upload_size_limit', 20 );

/**
 * Get FAQs for display
 */
function stylena_get_faqs( $limit = -1 ) {
	if ( ! post_type_exists( 'stylena_faq' ) ) {
		return array();
	}

	$args = array(
		'post_type'      => 'stylena_faq',
		'post_status'    => 'publish',
		'posts_per_page' => $limit,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'meta_query'     => array(
			array(
				'key'     => '_stylena_faq_display',
				'value'   => '1',
				'compare' => '=',
			),
		),
	);

	return get_posts( $args );
}

/**
 * URL of a published page by slug, for the theme's fallback navigation.
 *
 * Returns an empty string when the page does not exist, so templates can skip
 * the link instead of printing one that 404s (e.g. before the demo import).
 *
 * @param string $slug Page path/slug, e.g. 'about-us'.
 * @return string Escaped-ready permalink or ''.
 */
function stylena_fallback_page_url( $slug ) {
	static $cache = array();
	$known_slugs  = array( 'about-us', 'contact-us', 'reviews', 'services', 'team', 'membership', 'portfolio' );

	$pages = wp_cache_get( 'fallback_pages', 'stylena' );
	if ( false === $pages ) {
		// One query for every top-level fallback page instead of one get_page_by_path() per slug.
		$pages = array();
		$found = get_posts(
			array(
				'post_type'      => 'page',
				'post_status'    => 'publish',
				'post_parent'    => 0,
				'post_name__in'  => $known_slugs,
				'posts_per_page' => count( $known_slugs ),
				'no_found_rows'  => true,
			)
		);
		foreach ( $found as $found_page ) {
			$pages[ $found_page->post_name ] = $found_page;
		}
		wp_cache_set( 'fallback_pages', $pages, 'stylena' );
	}

	if ( ! isset( $cache[ $slug ] ) ) {
		$url = '';
		if ( isset( $pages[ $slug ] ) ) {
			$page = $pages[ $slug ];
		} else {
			$page = in_array( $slug, $known_slugs, true ) ? null : get_page_by_path( $slug );
		}
		if ( $page && 'publish' === $page->post_status ) {
			$url = get_permalink( $page );
		}

		/**
		 * Filters the URL used for a fallback navigation item.
		 *
		 * @param string $url  Permalink, or '' to hide the item.
		 * @param string $slug Page slug.
		 */
		$cache[ $slug ] = (string) apply_filters( 'stylena_fallback_page_url', $url, $slug );
	}

	return $cache[ $slug ];
}

/**
 * Drop the cached fallback-page map whenever a post changes.
 */
function stylena_flush_fallback_pages_cache() {
	wp_cache_delete( 'fallback_pages', 'stylena' );
}
add_action( 'clean_post_cache', 'stylena_flush_fallback_pages_cache' );

/**
 * Handle membership form submission
 */
function stylena_handle_membership_form_submission() {
	if ( isset( $_POST['membership_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['membership_nonce'] ) ), 'membership_form_submit' ) ) {
		$name           = sanitize_text_field( wp_unslash( $_POST['membership_name'] ?? '' ) );
		$email          = sanitize_email( wp_unslash( $_POST['membership_email'] ?? '' ) );
		$phone          = sanitize_text_field( wp_unslash( $_POST['membership_phone'] ?? '' ) );
		$terms_accepted = isset( $_POST['membership_terms'] ) ? 1 : 0;

		if ( ! empty( $name ) && ! empty( $email ) && ! empty( $phone ) && $terms_accepted ) {
			// Send email to admin.
			$admin_email = get_option( 'admin_email' );
			$subject     = 'New Membership Application from ' . get_bloginfo( 'name' );
			$message     = "New Black Card Membership application received:\n\n";
			$message    .= 'Name: ' . $name . "\n";
			$message    .= 'Email: ' . $email . "\n";
			$message    .= 'Phone: ' . $phone . "\n";
			$message    .= "Terms Accepted: Yes\n";
			$message    .= 'Date: ' . date( 'Y-m-d H:i:s' ) . "\n";
			$message    .= "Application Type: Black Card Membership\n";

			$headers = array( 'Content-Type: text/plain; charset=UTF-8' );

			if ( wp_mail( $admin_email, $subject, $message, $headers ) ) {
				// Success message.
				add_action(
					'wp_footer',
					function () {
						echo '<script>
                        document.addEventListener("DOMContentLoaded", function() {
                            alert("Thank you! Your membership application has been submitted successfully. We will contact you soon to complete your Black Card Membership setup.");
                        });
                    </script>';
					}
				);
			}
		}
	}
}
add_action( 'init', 'stylena_handle_membership_form_submission' );

/**
 * Membership modal script: only on pages using the Membership template.
 */
function stylena_enqueue_membership_modal() {
	if ( is_page_template( 'page-membership-fullwidth.php' ) ) {
		wp_enqueue_script(
			'stylena-membership-modal',
			get_theme_file_uri( 'js/membership-modal.js' ),
			array(),
			wp_get_theme()->get( 'Version' ),
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', 'stylena_enqueue_membership_modal', 20 );
