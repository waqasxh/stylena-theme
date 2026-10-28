<?php
/**
 * Service category term meta (sort_order).
 *
 * @package StylenaCore
 */

namespace StylenaCore\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sort order field, save and column for service_category.
 */
final class TermMeta {

	const NONCE_ACTION = 'stylena_core_save_service_category';
	const NONCE_NAME   = 'stylena_core_service_category_nonce';

	/**
	 * Hooks.
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'service_category_add_form_fields', array( __CLASS__, 'add_field' ) );
		add_action( 'service_category_edit_form_fields', array( __CLASS__, 'edit_field' ) );
		add_action( 'created_service_category', array( __CLASS__, 'save' ) );
		add_action( 'edited_service_category', array( __CLASS__, 'save' ) );
		add_filter( 'manage_edit-service_category_columns', array( __CLASS__, 'columns' ) );
		add_filter( 'manage_service_category_custom_column', array( __CLASS__, 'column' ), 10, 3 );
	}

	/**
	 * Add-term form field.
	 *
	 * @return void
	 */
	public static function add_field() {
		wp_nonce_field( self::NONCE_ACTION, self::NONCE_NAME );
		?>
		<div class="form-field term-sort-order-wrap">
			<label for="service_category_sort_order"><?php esc_html_e( 'Sort Order', 'stylena-core' ); ?></label>
			<input name="service_category_sort_order" id="service_category_sort_order" type="number" value="0" />
			<p class="description"><?php esc_html_e( 'Numeric sort order for categories. Lower numbers display first.', 'stylena-core' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Edit-term form field.
	 *
	 * @param \WP_Term $term Term.
	 * @return void
	 */
	public static function edit_field( $term ) {
		$value = get_term_meta( $term->term_id, 'sort_order', true );
		wp_nonce_field( self::NONCE_ACTION, self::NONCE_NAME );
		?>
		<tr class="form-field term-sort-order-wrap">
			<th scope="row"><label for="service_category_sort_order"><?php esc_html_e( 'Sort Order', 'stylena-core' ); ?></label></th>
			<td>
				<input name="service_category_sort_order" id="service_category_sort_order" type="number" value="<?php echo esc_attr( (string) (int) $value ); ?>" />
				<p class="description"><?php esc_html_e( 'Numeric sort order for categories. Lower numbers display first.', 'stylena-core' ); ?></p>
			</td>
		</tr>
		<?php
	}

	/**
	 * Save term meta.
	 *
	 * @param int $term_id Term ID.
	 * @return void
	 */
	public static function save( $term_id ) {
		if ( ! isset( $_POST[ self::NONCE_NAME ] ) ) {
			return;
		}
		$nonce = sanitize_text_field( wp_unslash( $_POST[ self::NONCE_NAME ] ) );
		if ( ! wp_verify_nonce( $nonce, self::NONCE_ACTION ) ) {
			return;
		}
		if ( ! current_user_can( 'edit_term', (int) $term_id ) ) {
			return;
		}
		if ( isset( $_POST['service_category_sort_order'] ) && is_scalar( $_POST['service_category_sort_order'] ) ) {
			update_term_meta( (int) $term_id, 'sort_order', (int) sanitize_text_field( wp_unslash( $_POST['service_category_sort_order'] ) ) );
		}
	}

	/**
	 * Add column.
	 *
	 * @param array $columns Columns.
	 * @return array
	 */
	public static function columns( $columns ) {
		return array_slice( $columns, 0, 2, true ) + array( 'sort_order' => __( 'Sort Order', 'stylena-core' ) ) + array_slice( $columns, 2, null, true );
	}

	/**
	 * Column content.
	 *
	 * @param string $content     Existing content.
	 * @param string $column_name Column.
	 * @param int    $term_id     Term ID.
	 * @return string
	 */
	public static function column( $content, $column_name, $term_id ) {
		if ( 'sort_order' === $column_name ) {
			return esc_html( (string) (int) get_term_meta( $term_id, 'sort_order', true ) );
		}
		return $content;
	}
}
