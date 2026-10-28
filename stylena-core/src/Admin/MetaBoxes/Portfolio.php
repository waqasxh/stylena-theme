<?php
/**
 * Portfolio meta box.
 *
 * @package StylenaCore
 */

namespace StylenaCore\Admin\MetaBoxes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Portfolio item details.
 */
final class Portfolio extends Base {

	/**
	 * Post type.
	 *
	 * @return string
	 */
	protected static function post_type() {
		return 'portfolio';
	}

	/**
	 * Box id.
	 *
	 * @return string
	 */
	protected static function box_id() {
		return 'stylena-core-portfolio-details';
	}

	/**
	 * Title.
	 *
	 * @return string
	 */
	protected static function title() {
		return __( 'Portfolio Item Details', 'stylena-core' );
	}

	/**
	 * Fields.
	 *
	 * @param \WP_Post $post Post.
	 * @return void
	 */
	protected static function fields( $post ) {
		$id = $post->ID;

		echo '<table style="width:100%;border-spacing:10px;">';
		echo '<tr><td style="width:150px;vertical-align:top;"><strong>' . esc_html__( 'Portfolio Image', 'stylena-core' ) . ':</strong></td><td>';
		self::image_picker( 'portfolio_image', (int) get_post_meta( $id, '_portfolio_image', true ) );
		echo '</td></tr>';
		echo '<tr><td><label for="portfolio_instagram_link"><strong>' . esc_html__( 'Instagram Post Link', 'stylena-core' ) . ':</strong></label></td>';
		echo '<td><input type="url" id="portfolio_instagram_link" name="portfolio_instagram_link" value="' . esc_attr( (string) get_post_meta( $id, '_portfolio_instagram_link', true ) ) . '" style="width:100%;" placeholder="https://www.instagram.com/p/..." /></td></tr>';
		echo '<tr><td><label for="portfolio_external_link"><strong>' . esc_html__( 'External Link', 'stylena-core' ) . ':</strong></label></td>';
		echo '<td><input type="url" id="portfolio_external_link" name="portfolio_external_link" value="' . esc_attr( (string) get_post_meta( $id, '_portfolio_external_link', true ) ) . '" style="width:100%;" placeholder="https://..." /></td></tr>';
		echo '</table>';

		self::sort_field( 'portfolio_sort_order', '_portfolio_sort_order', $id );
	}

	/**
	 * Persist.
	 *
	 * @param int $post_id Post ID.
	 * @return void
	 */
	protected static function persist( $post_id ) {
		self::save_attachment( $post_id, 'portfolio_image', '_portfolio_image' );
		self::save_url( $post_id, 'portfolio_instagram_link', '_portfolio_instagram_link' );
		self::save_url( $post_id, 'portfolio_external_link', '_portfolio_external_link' );
		self::save_sort_order( $post_id, 'portfolio_sort_order', '_portfolio_sort_order' );
	}
}
