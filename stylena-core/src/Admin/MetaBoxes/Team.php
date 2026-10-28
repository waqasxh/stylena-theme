<?php
/**
 * Team meta box.
 *
 * @package StylenaCore
 */

namespace StylenaCore\Admin\MetaBoxes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Team member details.
 */
final class Team extends Base {

	/**
	 * Post type.
	 *
	 * @return string
	 */
	protected static function post_type() {
		return 'team';
	}

	/**
	 * Box id.
	 *
	 * @return string
	 */
	protected static function box_id() {
		return 'stylena-core-team-details';
	}

	/**
	 * Title.
	 *
	 * @return string
	 */
	protected static function title() {
		return __( 'Team Member Details', 'stylena-core' );
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
		echo '<tr><td><label for="team_position">' . esc_html__( 'Position', 'stylena-core' ) . '</label></td>';
		echo '<td><input type="text" id="team_position" name="team_position" value="' . esc_attr( (string) get_post_meta( $id, '_team_position', true ) ) . '" style="width:100%;" /></td></tr>';
		echo '<tr><td><label for="team_bio">' . esc_html__( 'Bio/Description', 'stylena-core' ) . '</label></td>';
		echo '<td><textarea id="team_bio" name="team_bio" rows="5" cols="50" style="width:100%;" placeholder="' . esc_attr__( 'Enter team member bio/description...', 'stylena-core' ) . '">' . esc_textarea( (string) get_post_meta( $id, '_team_bio', true ) ) . '</textarea></td></tr>';
		echo '<tr><td><label for="team_specialties">' . esc_html__( 'Specialties', 'stylena-core' ) . '</label></td>';
		echo '<td><textarea id="team_specialties" name="team_specialties" rows="3" cols="50" style="width:100%;" placeholder="' . esc_attr__( 'Enter specialties, separated by commas...', 'stylena-core' ) . '">' . esc_textarea( (string) get_post_meta( $id, '_team_specialties', true ) ) . '</textarea></td></tr>';
		echo '<tr><td><label for="team_image">' . esc_html__( 'Team Image', 'stylena-core' ) . '</label></td><td>';
		self::image_picker( 'team_image', (int) get_post_meta( $id, '_team_image', true ) );
		echo '<p class="description">' . esc_html__( 'Upload a custom image for this team member (optional)', 'stylena-core' ) . '</p></td></tr>';
		echo '</table>';

		self::sort_field( 'team_sort_order', '_team_sort_order', $id );
	}

	/**
	 * Persist.
	 *
	 * @param int $post_id Post ID.
	 * @return void
	 */
	protected static function persist( $post_id ) {
		self::save_text( $post_id, 'team_position', '_team_position' );
		self::save_textarea( $post_id, 'team_bio', '_team_bio' );
		self::save_textarea( $post_id, 'team_specialties', '_team_specialties' );
		self::save_attachment( $post_id, 'team_image', '_team_image' );
		self::save_sort_order( $post_id, 'team_sort_order', '_team_sort_order' );
	}
}
