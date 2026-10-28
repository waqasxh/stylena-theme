<?php
/**
 * Admin assets (media uploader).
 *
 * @package StylenaCore
 */

namespace StylenaCore\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueues the media library and the picker script on the relevant screens.
 */
final class Assets {

	/**
	 * Hooks.
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue' ) );
	}

	/**
	 * Enqueue on service/team/portfolio edit screens.
	 *
	 * @param string $hook Admin page hook.
	 * @return void
	 */
	public static function enqueue( $hook ) {
		if ( 'post.php' !== $hook && 'post-new.php' !== $hook ) {
			return;
		}
		$screen = get_current_screen();
		if ( ! $screen || ! in_array( $screen->post_type, array( 'service', 'team', 'portfolio' ), true ) ) {
			return;
		}

		wp_enqueue_media();
		wp_enqueue_script(
			'stylena-core-admin-media',
			STYLENA_CORE_URL . 'assets/js/admin-media.js',
			array( 'jquery' ),
			STYLENA_CORE_VERSION,
			true
		);
		wp_localize_script(
			'stylena-core-admin-media',
			'stylenaCoreMedia',
			array(
				'title'  => __( 'Select Image', 'stylena-core' ),
				'button' => __( 'Use this image', 'stylena-core' ),
			)
		);
	}
}
