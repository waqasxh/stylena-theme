<?php
/**
 * Shared meta box plumbing: registration, nonce, guarded save.
 *
 * @package StylenaCore
 */

namespace StylenaCore\Admin\MetaBoxes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Base class for post meta boxes.
 */
abstract class Base {

	/**
	 * Post type slug.
	 *
	 * @return string
	 */
	abstract protected static function post_type();

	/**
	 * Meta box id.
	 *
	 * @return string
	 */
	abstract protected static function box_id();

	/**
	 * Meta box title.
	 *
	 * @return string
	 */
	abstract protected static function title();

	/**
	 * Context (normal|side).
	 *
	 * @return string
	 */
	protected static function context() {
		return 'normal';
	}

	/**
	 * Render the box body (nonce already printed).
	 *
	 * @param \WP_Post $post Post.
	 * @return void
	 */
	abstract protected static function fields( $post );

	/**
	 * Persist sanitized values (all security checks already passed).
	 *
	 * @param int $post_id Post ID.
	 * @return void
	 */
	abstract protected static function persist( $post_id );

	/**
	 * Hook registration and save.
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'add_meta_boxes', array( static::class, 'add' ) );
		add_action( 'save_post_' . static::post_type(), array( static::class, 'save' ) );
	}

	/**
	 * Nonce action.
	 *
	 * @return string
	 */
	protected static function nonce_action() {
		return 'stylena_core_save_' . static::post_type();
	}

	/**
	 * Nonce field name.
	 *
	 * @return string
	 */
	protected static function nonce_name() {
		return 'stylena_core_' . static::post_type() . '_nonce';
	}

	/**
	 * Add the meta box.
	 *
	 * @return void
	 */
	public static function add() {
		add_meta_box(
			static::box_id(),
			static::title(),
			array( static::class, 'render' ),
			static::post_type(),
			static::context(),
			'side' === static::context() ? 'default' : 'high'
		);
	}

	/**
	 * Render callback.
	 *
	 * @param \WP_Post $post Post.
	 * @return void
	 */
	public static function render( $post ) {
		wp_nonce_field( static::nonce_action(), static::nonce_name() );
		static::fields( $post );
	}

	/**
	 * Save callback with all guards.
	 *
	 * @param int $post_id Post ID.
	 * @return void
	 */
	public static function save( $post_id ) {
		$post_id = (int) $post_id;

		if ( ! isset( $_POST[ static::nonce_name() ] ) ) {
			return;
		}
		$nonce = sanitize_text_field( wp_unslash( $_POST[ static::nonce_name() ] ) );
		if ( ! wp_verify_nonce( $nonce, static::nonce_action() ) ) {
			return;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( wp_is_post_revision( $post_id ) || get_post_type( $post_id ) !== static::post_type() ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		static::persist( $post_id );
	}

	/**
	 * Read a raw POST string (unslashed, not yet sanitized).
	 *
	 * @param string $key Field name.
	 * @return string|null Null when absent or not a scalar.
	 */
	protected static function posted( $key ) {
		// Nonce verified in save().
		if ( ! isset( $_POST[ $key ] ) || ! is_scalar( $_POST[ $key ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			return null;
		}
		return (string) wp_unslash( $_POST[ $key ] ); // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	}

	/**
	 * Checkbox state as '1' or '0'.
	 *
	 * @param string $key Field name.
	 * @return string
	 */
	protected static function flag( $key ) {
		return ( null === static::posted( $key ) ) ? '0' : '1';
	}

	/**
	 * Save a single-line text field.
	 *
	 * @param int    $post_id  Post ID.
	 * @param string $field    Form field.
	 * @param string $meta_key Meta key.
	 * @return void
	 */
	protected static function save_text( $post_id, $field, $meta_key ) {
		$value = static::posted( $field );
		if ( null !== $value ) {
			update_post_meta( $post_id, $meta_key, sanitize_text_field( $value ) );
		}
	}

	/**
	 * Save a multi-line text field.
	 *
	 * @param int    $post_id  Post ID.
	 * @param string $field    Form field.
	 * @param string $meta_key Meta key.
	 * @return void
	 */
	protected static function save_textarea( $post_id, $field, $meta_key ) {
		$value = static::posted( $field );
		if ( null !== $value ) {
			update_post_meta( $post_id, $meta_key, sanitize_textarea_field( $value ) );
		}
	}

	/**
	 * Save a URL field (empty string when invalid).
	 *
	 * @param int    $post_id  Post ID.
	 * @param string $field    Form field.
	 * @param string $meta_key Meta key.
	 * @return void
	 */
	protected static function save_url( $post_id, $field, $meta_key ) {
		$value = static::posted( $field );
		if ( null !== $value ) {
			update_post_meta( $post_id, $meta_key, esc_url_raw( trim( $value ) ) );
		}
	}

	/**
	 * Save an attachment ID (must be a positive int; 0 clears).
	 *
	 * @param int    $post_id  Post ID.
	 * @param string $field    Form field.
	 * @param string $meta_key Meta key.
	 * @return void
	 */
	protected static function save_attachment( $post_id, $field, $meta_key ) {
		$value = static::posted( $field );
		if ( null !== $value ) {
			$id = absint( $value );
			if ( $id && ( 'attachment' !== get_post_type( $id ) || ! current_user_can( 'read_post', $id ) ) ) {
				$id = 0;
			}
			update_post_meta( $post_id, $meta_key, $id );
		}
	}

	/**
	 * Save a sort order (integer, defaults to 0 when never set).
	 *
	 * @param int    $post_id  Post ID.
	 * @param string $field    Form field.
	 * @param string $meta_key Meta key.
	 * @return void
	 */
	protected static function save_sort_order( $post_id, $field, $meta_key ) {
		$value = static::posted( $field );
		if ( null !== $value ) {
			update_post_meta( $post_id, $meta_key, (int) $value );
		} elseif ( '' === get_post_meta( $post_id, $meta_key, true ) ) {
			update_post_meta( $post_id, $meta_key, 0 );
		}
	}

	/**
	 * Print an image picker (hidden input + buttons + preview). Behaviour in admin-media.js.
	 *
	 * @param string $field Input name/id.
	 * @param int    $value Attachment ID.
	 * @return void
	 */
	protected static function image_picker( $field, $value ) {
		$value = absint( $value );
		echo '<div class="stylena-core-image-field">';
		echo '<input type="hidden" id="' . esc_attr( $field ) . '" name="' . esc_attr( $field ) . '" value="' . esc_attr( (string) $value ) . '" />';
		echo '<button type="button" class="button stylena-core-image-select">' . esc_html__( 'Select Image', 'stylena-core' ) . '</button> ';
		echo '<button type="button" class="button stylena-core-image-remove">' . esc_html__( 'Remove Image', 'stylena-core' ) . '</button>';
		echo '<div class="stylena-core-image-preview" style="margin-top:10px;">';
		if ( $value ) {
			$url = wp_get_attachment_image_url( $value, 'medium' );
			if ( $url ) {
				echo '<img src="' . esc_url( $url ) . '" style="max-width:200px;height:auto;" alt="" />';
			}
		}
		echo '</div></div>';
	}

	/**
	 * Print a sort order field.
	 *
	 * @param string $field    Input name/id.
	 * @param string $meta_key Meta key.
	 * @param int    $post_id  Post ID.
	 * @return void
	 */
	protected static function sort_field( $field, $meta_key, $post_id ) {
		$value = get_post_meta( $post_id, $meta_key, true );
		if ( '' === $value ) {
			$value = 0;
		}
		echo '<p style="margin-top:1rem;"><label for="' . esc_attr( $field ) . '"><strong>' . esc_html__( 'Sort Order', 'stylena-core' ) . ':</strong></label> ';
		echo '<input type="number" id="' . esc_attr( $field ) . '" name="' . esc_attr( $field ) . '" value="' . esc_attr( (string) (int) $value ) . '" style="width:120px;margin-left:0.5rem;" />';
		echo '<span class="description" style="margin-left:0.5rem;">' . esc_html__( 'Lower numbers display first', 'stylena-core' ) . '</span></p>';
	}
}
