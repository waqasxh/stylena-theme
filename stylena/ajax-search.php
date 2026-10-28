<?php

/**
 * AJAX Live Search Handler
 * Content search only (no categories/tags)
 *
 * @package Stylena
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Handle AJAX search.
add_action( 'wp_ajax_stylena_live_search', 'stylena_handle_live_search' );
add_action( 'wp_ajax_nopriv_stylena_live_search', 'stylena_handle_live_search' );

function stylena_handle_live_search() {
	// Verify nonce for security.
	$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
	if ( ! wp_verify_nonce( $nonce, 'stylena_search_nonce' ) ) {
		wp_send_json_error( 'Security check failed' );
		return;
	}

	$search_term = isset( $_POST['search_term'] ) ? sanitize_text_field( wp_unslash( $_POST['search_term'] ) ) : '';

	if ( empty( $search_term ) || strlen( $search_term ) < 2 ) {
		wp_send_json_error( 'Search term too short' );
		return;
	}

	// Search for content only.
	$args = array(
		'post_type'      => array( 'post', 'page', 'product', 'service', 'team' ),
		'post_status'    => 'publish',
		's'              => $search_term,
		'posts_per_page' => 8,
	);

	$search_query = new WP_Query( $args );
	$results      = array();

	if ( $search_query->have_posts() ) {
		while ( $search_query->have_posts() ) {
			$search_query->the_post();

			$post_id   = get_the_ID();
			$thumbnail = get_the_post_thumbnail_url( $post_id, 'thumbnail' );
			$excerpt   = get_the_excerpt();

			// Create excerpt if none exists.
			if ( empty( $excerpt ) ) {
				$excerpt = wp_trim_words( get_the_content(), 20, '...' );
			}

			// Limit excerpt length.
			if ( strlen( $excerpt ) > 120 ) {
				$excerpt = substr( $excerpt, 0, 120 ) . '...';
			}

			$results[] = array(
				'id'        => $post_id,
				'title'     => get_the_title(),
				'url'       => get_permalink(),
				'excerpt'   => $excerpt ? $excerpt : '',
				'thumbnail' => $thumbnail ? $thumbnail : '',
				'post_type' => get_post_type(),
				'date'      => get_the_date( 'M j, Y' ),
			);
		}
		wp_reset_postdata();
	}

	$response = array(
		'success'     => true,
		'posts'       => $results,
		'total_found' => $search_query->found_posts,
		'search_url'  => home_url( '/?s=' . urlencode( $search_term ) ),
	);

	wp_send_json_success( $response );
}
