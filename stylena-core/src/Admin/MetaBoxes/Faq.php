<?php
/**
 * FAQ meta box.
 *
 * @package StylenaCore
 */

namespace StylenaCore\Admin\MetaBoxes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * FAQ display settings.
 */
final class Faq extends Base {

	/**
	 * Post type.
	 *
	 * @return string
	 */
	protected static function post_type() {
		return 'stylena_faq';
	}

	/**
	 * Box id.
	 *
	 * @return string
	 */
	protected static function box_id() {
		return 'stylena-core-faq-display';
	}

	/**
	 * Title.
	 *
	 * @return string
	 */
	protected static function title() {
		return __( 'FAQ Display Settings', 'stylena-core' );
	}

	/**
	 * Context.
	 *
	 * @return string
	 */
	protected static function context() {
		return 'side';
	}

	/**
	 * Fields.
	 *
	 * @param \WP_Post $post Post.
	 * @return void
	 */
	protected static function fields( $post ) {
		echo '<p><label for="stylena_faq_display"><input type="checkbox" id="stylena_faq_display" name="stylena_faq_display" value="1"' . checked( get_post_meta( $post->ID, '_stylena_faq_display', true ), '1', false ) . ' /> ';
		echo esc_html__( 'Display this FAQ in footer section', 'stylena-core' ) . '</label></p>';
	}

	/**
	 * Persist.
	 *
	 * @param int $post_id Post ID.
	 * @return void
	 */
	protected static function persist( $post_id ) {
		update_post_meta( $post_id, '_stylena_faq_display', self::flag( 'stylena_faq_display' ) );
	}
}
