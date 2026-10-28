<?php
/**
 * Optional plugin integrations: WooCommerce basics and booking plugin styling.
 *
 * Everything here is inert (no hooks that output, no styles, no theme support)
 * unless the relevant plugin is active.
 *
 * @package Stylena
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether WooCommerce is active.
 *
 * @return bool
 */
function stylena_is_woocommerce_active() {
	return class_exists( 'WooCommerce' );
}

/**
 * Register WooCommerce theme support and layout wrappers.
 *
 * @return void
 */
function stylena_woocommerce_init() {
	if ( ! stylena_is_woocommerce_active() ) {
		return;
	}
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
	add_action( 'woocommerce_before_main_content', 'stylena_woocommerce_wrapper_start', 10 );
	add_action( 'woocommerce_after_main_content', 'stylena_woocommerce_wrapper_end', 10 );
	add_action( 'wp_enqueue_scripts', 'stylena_woocommerce_enqueue_styles', 20 );
}
add_action( 'after_setup_theme', 'stylena_woocommerce_init', 20 );

/**
 * Open the theme container (same structure as page.php, full width).
 *
 * @return void
 */
function stylena_woocommerce_wrapper_start() {
	echo '<div class="container page-container stylena-woocommerce"><div class="row">'
		. '<main id="main" tabindex="-1" class="col-12 site-main" role="main">';
}

/**
 * Close the theme container.
 *
 * @return void
 */
function stylena_woocommerce_wrapper_end() {
	echo '</main></div></div>';
}

/**
 * Enqueue WooCommerce styling (only called when WooCommerce is active).
 *
 * @return void
 */
function stylena_woocommerce_enqueue_styles() {
	if ( ! stylena_is_woocommerce_active() ) {
		return;
	}
	$version = wp_get_theme()->get( 'Version' );
	wp_enqueue_style( 'stylena-woocommerce', get_theme_file_uri( '/css/woocommerce.css' ), array( 'stylena-custom' ), $version );
}

/**
 * Whether a supported booking plugin is active. Filterable.
 *
 * @return bool
 */
function stylena_booking_plugins_active() {
	$active = class_exists( 'Bookly\Lib\Plugin' )
		|| class_exists( 'AmeliaBooking\Plugin' )
		|| defined( 'AMELIA_VERSION' )
		|| class_exists( 'SSA_Bootstrap' )
		|| defined( 'SSA_PLUGIN_FILE' )
		|| class_exists( 'EasyAppointment' )
		|| defined( 'EA_PLUGIN_URL' )
		|| defined( 'LATEPOINT_VERSION' )
		|| class_exists( 'LatePoint' )
		|| defined( 'CALENDLY_PLUGIN_VERSION' );

	/**
	 * Force or disable the booking plugin stylesheet.
	 *
	 * @param bool $active Whether a booking plugin was detected.
	 */
	return (bool) apply_filters( 'stylena_booking_plugins_active', $active );
}

/**
 * Enqueue booking styles when a booking plugin is active.
 *
 * @return void
 */
function stylena_booking_enqueue_styles() {
	if ( ! stylena_booking_plugins_active() ) {
		return;
	}
	$version = wp_get_theme()->get( 'Version' );
	wp_enqueue_style( 'stylena-booking', get_theme_file_uri( '/css/booking.css' ), array( 'stylena-custom' ), $version );
}
add_action( 'wp_enqueue_scripts', 'stylena_booking_enqueue_styles', 20 );
