<?php
/**
 * Slider item meta box.
 *
 * @package StylenaCore
 */

namespace StylenaCore\Admin\MetaBoxes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Slider item settings.
 */
final class Slider extends Base {

	/**
	 * Post type.
	 *
	 * @return string
	 */
	protected static function post_type() {
		return 'stylena_slider';
	}

	/**
	 * Box id.
	 *
	 * @return string
	 */
	protected static function box_id() {
		return 'stylena-core-slider-settings';
	}

	/**
	 * Title.
	 *
	 * @return string
	 */
	protected static function title() {
		return __( 'Slider Item Settings', 'stylena-core' );
	}

	/**
	 * Fields.
	 *
	 * @param \WP_Post $post Post.
	 * @return void
	 */
	protected static function fields( $post ) {
		echo '<table class="form-table"><tr><th scope="row"><label for="stylena_slider_subtitle">' . esc_html__( 'Subtitle', 'stylena-core' ) . '</label></th><td>';
		echo '<input type="text" id="stylena_slider_subtitle" name="stylena_slider_subtitle" value="' . esc_attr( (string) get_post_meta( $post->ID, '_stylena_slider_subtitle', true ) ) . '" class="regular-text" />';
		echo '<p class="description">' . esc_html__( 'Enter a subtitle for this slider item.', 'stylena-core' ) . '</p>';
		echo '</td></tr></table>';
	}

	/**
	 * Persist.
	 *
	 * @param int $post_id Post ID.
	 * @return void
	 */
	protected static function persist( $post_id ) {
		self::save_text( $post_id, 'stylena_slider_subtitle', '_stylena_slider_subtitle' );
	}
}
