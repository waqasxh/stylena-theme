<?php
/**
 * Admin list columns, sorting and ordering.
 *
 * @package StylenaCore
 */

namespace StylenaCore\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Columns for the content types.
 */
final class Columns {

	/**
	 * Sort-order meta key by post type.
	 *
	 * @var array<string,string>
	 */
	const SORT_KEYS = array(
		'service'   => '_service_sort_order',
		'team'      => '_team_sort_order',
		'portfolio' => '_portfolio_sort_order',
	);

	/**
	 * Hooks.
	 *
	 * @return void
	 */
	public static function init() {
		foreach ( array_keys( self::SORT_KEYS ) as $type ) {
			add_filter( "manage_{$type}_posts_columns", array( __CLASS__, 'sort_column' ) );
			add_action( "manage_{$type}_posts_custom_column", array( __CLASS__, 'render_sort_column' ), 10, 2 );
			add_filter( "manage_edit-{$type}_sortable_columns", array( __CLASS__, 'sortable' ) );
		}

		add_filter( 'manage_service_posts_columns', array( __CLASS__, 'price_column' ) );
		add_action( 'manage_service_posts_custom_column', array( __CLASS__, 'render_price_column' ), 10, 2 );
		add_filter( 'manage_edit-service_sortable_columns', array( __CLASS__, 'service_sortable' ) );

		add_filter( 'manage_stylena_faq_posts_columns', array( __CLASS__, 'faq_columns' ) );
		add_action( 'manage_stylena_faq_posts_custom_column', array( __CLASS__, 'faq_render' ), 10, 2 );
		add_filter( 'manage_edit-stylena_faq_sortable_columns', array( __CLASS__, 'menu_order_sortable' ) );

		add_filter( 'manage_stylena_slider_posts_columns', array( __CLASS__, 'slider_columns' ) );
		add_action( 'manage_stylena_slider_posts_custom_column', array( __CLASS__, 'slider_render' ), 10, 2 );
		add_filter( 'manage_edit-stylena_slider_sortable_columns', array( __CLASS__, 'menu_order_sortable' ) );

		add_action( 'pre_get_posts', array( __CLASS__, 'orderby' ) );
	}

	/**
	 * Insert a column after the first two.
	 *
	 * @param array  $columns Columns.
	 * @param string $key     New key.
	 * @param string $label   New label.
	 * @return array
	 */
	private static function insert( $columns, $key, $label ) {
		return array_slice( $columns, 0, 2, true ) + array( $key => $label ) + array_slice( $columns, 2, null, true );
	}

	/**
	 * Sort order column.
	 *
	 * @param array $columns Columns.
	 * @return array
	 */
	public static function sort_column( $columns ) {
		return self::insert( $columns, 'sort_order', __( 'Sort Order', 'stylena-core' ) );
	}

	/**
	 * Price column.
	 *
	 * @param array $columns Columns.
	 * @return array
	 */
	public static function price_column( $columns ) {
		return self::insert( $columns, 'service_price', __( 'Price', 'stylena-core' ) );
	}

	/**
	 * Render sort order.
	 *
	 * @param string $column  Column.
	 * @param int    $post_id Post ID.
	 * @return void
	 */
	public static function render_sort_column( $column, $post_id ) {
		if ( 'sort_order' !== $column ) {
			return;
		}
		$key = self::SORT_KEYS[ get_post_type( $post_id ) ] ?? '';
		echo esc_html( (string) (int) ( $key ? get_post_meta( $post_id, $key, true ) : 0 ) );
	}

	/**
	 * Render price.
	 *
	 * @param string $column  Column.
	 * @param int    $post_id Post ID.
	 * @return void
	 */
	public static function render_price_column( $column, $post_id ) {
		if ( 'service_price' !== $column ) {
			return;
		}
		$price = get_post_meta( $post_id, '_service_price', true );
		if ( $price ) {
			echo esc_html( $price );
		} else {
			echo '<span style="color:#888;">' . esc_html__( 'No price set', 'stylena-core' ) . '</span>';
		}
	}

	/**
	 * Sortable sort order.
	 *
	 * @param array $columns Columns.
	 * @return array
	 */
	public static function sortable( $columns ) {
		$columns['sort_order'] = 'sort_order';
		return $columns;
	}

	/**
	 * Sortable sort order + price for services.
	 *
	 * @param array $columns Columns.
	 * @return array
	 */
	public static function service_sortable( $columns ) {
		$columns['sort_order']    = 'sort_order';
		$columns['service_price'] = 'service_price';
		return $columns;
	}

	/**
	 * Sortable menu order.
	 *
	 * @param array $columns Columns.
	 * @return array
	 */
	public static function menu_order_sortable( $columns ) {
		$columns['menu_order'] = 'menu_order';
		return $columns;
	}

	/**
	 * FAQ columns.
	 *
	 * @param array $columns Columns.
	 * @return array
	 */
	public static function faq_columns( $columns ) {
		$columns['display_in_footer'] = __( 'Display in Footer', 'stylena-core' );
		$columns['menu_order']        = __( 'Order', 'stylena-core' );
		return $columns;
	}

	/**
	 * FAQ column content.
	 *
	 * @param string $column  Column.
	 * @param int    $post_id Post ID.
	 * @return void
	 */
	public static function faq_render( $column, $post_id ) {
		if ( 'display_in_footer' === $column ) {
			echo '1' === get_post_meta( $post_id, '_stylena_faq_display', true ) ? esc_html__( 'Yes', 'stylena-core' ) : esc_html__( 'No', 'stylena-core' );
		} elseif ( 'menu_order' === $column ) {
			echo esc_html( (string) get_post_field( 'menu_order', $post_id ) );
		}
	}

	/**
	 * Slider columns.
	 *
	 * @param array $columns Columns.
	 * @return array
	 */
	public static function slider_columns( $columns ) {
		$new = array();
		if ( isset( $columns['cb'] ) ) {
			$new['cb'] = $columns['cb'];
		}
		$new['featured_image'] = __( 'Image', 'stylena-core' );
		$new['title']          = $columns['title'] ?? __( 'Title', 'stylena-core' );
		$new['subtitle']       = __( 'Subtitle', 'stylena-core' );
		$new['menu_order']     = __( 'Order', 'stylena-core' );
		if ( isset( $columns['date'] ) ) {
			$new['date'] = $columns['date'];
		}
		return $new;
	}

	/**
	 * Slider column content.
	 *
	 * @param string $column  Column.
	 * @param int    $post_id Post ID.
	 * @return void
	 */
	public static function slider_render( $column, $post_id ) {
		if ( 'featured_image' === $column ) {
			if ( has_post_thumbnail( $post_id ) ) {
				echo wp_kses_post( get_the_post_thumbnail( $post_id, array( 80, 60 ) ) );
			} else {
				echo '<span style="color:#999;">' . esc_html__( 'No image', 'stylena-core' ) . '</span>';
			}
		} elseif ( 'subtitle' === $column ) {
			$subtitle = get_post_meta( $post_id, '_stylena_slider_subtitle', true );
			if ( $subtitle ) {
				echo esc_html( $subtitle );
			} else {
				echo '<span style="color:#999;">' . esc_html__( 'No subtitle', 'stylena-core' ) . '</span>';
			}
		} elseif ( 'menu_order' === $column ) {
			echo esc_html( (string) get_post_field( 'menu_order', $post_id ) );
		}
	}

	/**
	 * Apply admin ordering for sort_order / service_price.
	 *
	 * @param \WP_Query $query Query.
	 * @return void
	 */
	public static function orderby( $query ) {
		if ( ! is_admin() || ! $query->is_main_query() ) {
			return;
		}

		$post_type = $query->get( 'post_type' );
		if ( ! is_string( $post_type ) || ! isset( self::SORT_KEYS[ $post_type ] ) ) {
			return;
		}

		$orderby = $query->get( 'orderby' );
		$order   = 'ASC' === strtoupper( (string) $query->get( 'order' ) ) ? 'ASC' : 'DESC';

		if ( 'sort_order' === $orderby ) {
			$query->set( 'meta_key', self::SORT_KEYS[ $post_type ] ); // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- admin list sorting only.
			$query->set( 'orderby', 'meta_value_num' );
			$query->set( 'order', $order );
		} elseif ( 'service_price' === $orderby && 'service' === $post_type ) {
			$query->set( 'meta_key', '_service_price' ); // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- admin list sorting only.
			$query->set( 'orderby', 'meta_value' );
			$query->set( 'order', $order );
		}
	}
}
