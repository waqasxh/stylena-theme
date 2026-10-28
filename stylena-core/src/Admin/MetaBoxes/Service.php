<?php
/**
 * Service meta box.
 *
 * @package StylenaCore
 */

namespace StylenaCore\Admin\MetaBoxes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Service details.
 */
final class Service extends Base {

	/**
	 * Post type.
	 *
	 * @return string
	 */
	protected static function post_type() {
		return 'service';
	}

	/**
	 * Box id.
	 *
	 * @return string
	 */
	protected static function box_id() {
		return 'stylena-core-service-details';
	}

	/**
	 * Title.
	 *
	 * @return string
	 */
	protected static function title() {
		return __( 'Service Details', 'stylena-core' );
	}

	/**
	 * Fields.
	 *
	 * @param \WP_Post $post Post.
	 * @return void
	 */
	protected static function fields( $post ) {
		$id = $post->ID;

		echo '<table class="form-table">';
		echo '<tr><td><label for="service_price">' . esc_html__( 'Price', 'stylena-core' ) . '</label></td>';
		echo '<td><input type="text" id="service_price" name="service_price" value="' . esc_attr( (string) get_post_meta( $id, '_service_price', true ) ) . '" placeholder="' . esc_attr__( 'e.g., £145', 'stylena-core' ) . '" /></td></tr>';
		echo '<tr><td><label for="service_duration">' . esc_html__( 'Duration', 'stylena-core' ) . '</label></td>';
		echo '<td><input type="text" id="service_duration" name="service_duration" value="' . esc_attr( (string) get_post_meta( $id, '_service_duration', true ) ) . '" placeholder="' . esc_attr__( 'e.g., 20 min', 'stylena-core' ) . '" /></td></tr>';
		echo '<tr><td><label for="service_is_new">' . esc_html__( 'Mark as New Service', 'stylena-core' ) . '</label></td>';
		echo '<td><input type="checkbox" id="service_is_new" name="service_is_new" value="1"' . checked( get_post_meta( $id, '_service_is_new', true ), '1', false ) . ' /> ' . esc_html__( 'Yes', 'stylena-core' ) . '</td></tr>';
		echo '<tr><td><label for="service_link">' . esc_html__( 'Custom Link (optional)', 'stylena-core' ) . '</label></td>';
		echo '<td><input type="text" id="service_link" name="service_link" value="' . esc_attr( (string) get_post_meta( $id, '_service_link', true ) ) . '" style="width:100%;" placeholder="' . esc_attr__( 'Leave blank to link to this service\'s detail page', 'stylena-core' ) . '" /></td></tr>';
		echo '<tr><td><label for="service_description">' . esc_html__( 'Description', 'stylena-core' ) . '</label></td>';
		echo '<td><textarea id="service_description" name="service_description" rows="4" style="width:100%;" placeholder="' . esc_attr__( 'Short description or summary', 'stylena-core' ) . '">' . esc_textarea( (string) get_post_meta( $id, '_service_description', true ) ) . '</textarea></td></tr>';
		echo '<tr><td><label for="service_image">' . esc_html__( 'Service Image', 'stylena-core' ) . '</label></td><td>';
		self::image_picker( 'service_image', (int) get_post_meta( $id, '_service_image', true ) );
		echo '<p class="description">' . esc_html__( 'Upload a custom image for this service (optional)', 'stylena-core' ) . '</p></td></tr>';
		echo '<tr><td><label for="service_benefits">' . esc_html__( 'Benefits (one per line)', 'stylena-core' ) . '</label></td>';
		echo '<td><textarea id="service_benefits" name="service_benefits" rows="5" cols="50" placeholder="' . esc_attr__( 'Enter benefits, one per line', 'stylena-core' ) . '">' . esc_textarea( (string) get_post_meta( $id, '_service_benefits', true ) ) . '</textarea></td></tr>';
		echo '</table>';

		self::sort_field( 'service_sort_order', '_service_sort_order', $id );
	}

	/**
	 * Persist.
	 *
	 * @param int $post_id Post ID.
	 * @return void
	 */
	protected static function persist( $post_id ) {
		self::save_text( $post_id, 'service_price', '_service_price' );
		self::save_text( $post_id, 'service_duration', '_service_duration' );
		self::save_textarea( $post_id, 'service_benefits', '_service_benefits' );
		self::save_attachment( $post_id, 'service_image', '_service_image' );
		// Empty means "use the service's own permalink"; relative links are allowed.
		self::save_url( $post_id, 'service_link', '_service_link' );
		self::save_textarea( $post_id, 'service_description', '_service_description' );
		update_post_meta( $post_id, '_service_is_new', self::flag( 'service_is_new' ) );
		self::save_sort_order( $post_id, 'service_sort_order', '_service_sort_order' );
	}
}
