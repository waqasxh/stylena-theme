<?php
/**
 * Post meta and term meta registration.
 *
 * Meta keys keep their legacy unprefixed names on purpose (existing sites store
 * data under them). Nothing is exposed to REST (show_in_rest false).
 *
 * @package StylenaCore
 */

namespace StylenaCore\Meta;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers meta keys with sanitize and auth callbacks.
 */
final class Registry {

	/**
	 * Register all meta. register_meta() is idempotent, so a legacy theme is harmless.
	 *
	 * @return void
	 */
	public static function register() {
		$text     = array( __CLASS__, 'sanitize_text' );
		$textarea = array( __CLASS__, 'sanitize_textarea' );
		$int      = array( __CLASS__, 'sanitize_int' );
		$url      = array( __CLASS__, 'sanitize_url' );
		$flag     = array( __CLASS__, 'sanitize_flag' );

		$map = array(
			'service'        => array(
				'_service_price'       => array( 'string', $text ),
				'_service_duration'    => array( 'string', $text ),
				'_service_benefits'    => array( 'string', $textarea ),
				'_service_is_new'      => array( 'string', $flag ),
				'_service_image'       => array( 'integer', $int ),
				// Empty means "use the service's own permalink"; legacy values may be relative.
				'_service_link'        => array( 'string', array( __CLASS__, 'sanitize_url' ) ),
				'_service_description' => array( 'string', $textarea ),
				'_service_sort_order'  => array( 'integer', $int ),
			),
			'team'           => array(
				'_team_position'    => array( 'string', $text ),
				'_team_specialties' => array( 'string', $textarea ),
				'_team_bio'         => array( 'string', $textarea ),
				'_team_image'       => array( 'integer', $int ),
				'_team_sort_order'  => array( 'integer', $int ),
			),
			'portfolio'      => array(
				'_portfolio_image'          => array( 'integer', $int ),
				'_portfolio_instagram_link' => array( 'string', $url ),
				'_portfolio_external_link'  => array( 'string', $url ),
				'_portfolio_sort_order'     => array( 'integer', $int ),
			),
			'stylena_faq'    => array(
				'_stylena_faq_display' => array( 'string', $flag ),
			),
			'stylena_slider' => array(
				'_stylena_slider_subtitle' => array( 'string', $text ),
				'_stylena_sample_data'     => array( 'string', $flag ),
			),
		);

		foreach ( $map as $post_type => $keys ) {
			foreach ( $keys as $key => $def ) {
				register_post_meta(
					$post_type,
					$key,
					array(
						'type'              => $def[0],
						'single'            => true,
						'show_in_rest'      => false,
						'sanitize_callback' => $def[1],
						'auth_callback'     => array( __CLASS__, 'auth_post_meta' ),
					)
				);
			}
		}

		register_term_meta(
			'service_category',
			'sort_order',
			array(
				'type'              => 'integer',
				'single'            => true,
				'show_in_rest'      => false,
				'sanitize_callback' => $int,
				'auth_callback'     => array( __CLASS__, 'auth_term_meta' ),
			)
		);
	}

	/**
	 * Auth callback for post meta.
	 *
	 * @param bool   $allowed  Current decision.
	 * @param string $meta_key Meta key.
	 * @param int    $post_id  Post ID.
	 * @return bool
	 */
	public static function auth_post_meta( $allowed, $meta_key, $post_id ) {
		unset( $allowed, $meta_key );
		return current_user_can( 'edit_post', (int) $post_id );
	}

	/**
	 * Auth callback for term meta.
	 *
	 * @param bool   $allowed  Current decision.
	 * @param string $meta_key Meta key.
	 * @param int    $term_id  Term ID.
	 * @return bool
	 */
	public static function auth_term_meta( $allowed, $meta_key, $term_id ) {
		unset( $allowed, $meta_key );
		return current_user_can( 'edit_term', (int) $term_id );
	}

	/**
	 * Sanitize a single-line string.
	 *
	 * @param mixed $value Raw value.
	 * @return string
	 */
	public static function sanitize_text( $value ) {
		return sanitize_text_field( is_scalar( $value ) ? (string) $value : '' );
	}

	/**
	 * Sanitize a multi-line string.
	 *
	 * @param mixed $value Raw value.
	 * @return string
	 */
	public static function sanitize_textarea( $value ) {
		return sanitize_textarea_field( is_scalar( $value ) ? (string) $value : '' );
	}

	/**
	 * Sanitize an integer (attachment IDs, sort order; negatives allowed for sorting).
	 *
	 * @param mixed $value Raw value.
	 * @return int
	 */
	public static function sanitize_int( $value ) {
		return is_scalar( $value ) ? (int) $value : 0;
	}

	/**
	 * Sanitize a URL; empty string when invalid.
	 *
	 * @param mixed $value Raw value.
	 * @return string
	 */
	public static function sanitize_url( $value ) {
		return esc_url_raw( is_scalar( $value ) ? (string) $value : '' );
	}

	/**
	 * Sanitize a '1'/'0' flag.
	 *
	 * @param mixed $value Raw value.
	 * @return string
	 */
	public static function sanitize_flag( $value ) {
		return in_array( (string) ( is_scalar( $value ) ? $value : '' ), array( '1', 'true', 'on', 'yes' ), true ) ? '1' : '0';
	}
}
