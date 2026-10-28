<?php
/**
 * Service categories widget (id_base preserved from the theme: stylena_service_categories).
 *
 * @package StylenaCore
 */

namespace StylenaCore\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Widget.
 */
class ServiceCategories extends \WP_Widget {

	/**
	 * Register unless the legacy theme widget (same id_base) is present.
	 * The theme copy wins until it is removed (T-101f).
	 *
	 * @return void
	 */
	public static function register() {
		if ( class_exists( '\Stylena\Widgets\ServiceCategoriesWidget' ) ) {
			return;
		}
		// No taxonomy_exists() check: widgets_init fires at init priority 1, before the plugin registers
		// the taxonomy at init priority 5. The widget output handles a missing taxonomy (WP_Error).
		register_widget( __CLASS__ );
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct(
			'stylena_service_categories',
			__( 'Service Categories', 'stylena-core' ),
			array(
				'description' => __( 'Display service categories with customizable options.', 'stylena-core' ),
				'classname'   => 'widget_service_categories',
			)
		);
	}

	/**
	 * Front-end output.
	 *
	 * @param array $args     Sidebar args.
	 * @param array $instance Settings.
	 * @return void
	 */
	public function widget( $args, $instance ) {
		$title      = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Service Categories', 'stylena-core' );
		$show_count = ! empty( $instance['show_count'] );
		$show_empty = ! empty( $instance['show_empty'] );
		$limit      = ! empty( $instance['limit'] ) ? absint( $instance['limit'] ) : 0;

		echo wp_kses_post( $args['before_widget'] );

		if ( $title ) {
			/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
			echo wp_kses_post( $args['before_title'] ) . esc_html( apply_filters( 'widget_title', $title, $instance, $this->id_base ) ) . wp_kses_post( $args['after_title'] );
		}

		$categories = get_terms(
			array(
				'taxonomy'   => 'service_category',
				'hide_empty' => ! $show_empty,
				'number'     => $limit > 0 ? $limit : '',
				'orderby'    => 'name',
				'order'      => 'ASC',
			)
		);

		if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
			echo '<ul class="service-categories-list">';
			foreach ( $categories as $category ) {
				$link = get_term_link( $category );
				if ( is_wp_error( $link ) ) {
					continue;
				}
				$count_text = $show_count ? ' (' . (int) $category->count . ')' : '';
				echo '<li class="service-category-item"><a href="' . esc_url( $link ) . '" class="service-category-link">';
				echo esc_html( $category->name . $count_text );
				echo '</a></li>';
			}
			echo '</ul>';
		} else {
			echo '<p>' . esc_html__( 'No service categories found.', 'stylena-core' ) . '</p>';
		}

		echo wp_kses_post( $args['after_widget'] );
	}

	/**
	 * Admin form.
	 *
	 * @param array $instance Settings.
	 * @return string
	 */
	public function form( $instance ) {
		$title      = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Service Categories', 'stylena-core' );
		$show_count = ! empty( $instance['show_count'] );
		$show_empty = ! empty( $instance['show_empty'] );
		$limit      = ! empty( $instance['limit'] ) ? absint( $instance['limit'] ) : '';
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'stylena-core' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $show_count ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_count' ) ); ?>" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_count' ) ); ?>"><?php esc_html_e( 'Show service count', 'stylena-core' ); ?></label>
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $show_empty ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_empty' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_empty' ) ); ?>" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_empty' ) ); ?>"><?php esc_html_e( 'Show empty categories', 'stylena-core' ); ?></label>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>"><?php esc_html_e( 'Number of categories to show (0 = all):', 'stylena-core' ); ?></label>
			<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'limit' ) ); ?>" type="number" step="1" min="0" value="<?php echo esc_attr( (string) $limit ); ?>" size="3" />
		</p>
		<?php
		return '';
	}

	/**
	 * Save settings.
	 *
	 * @param array $new_instance New settings.
	 * @param array $old_instance Old settings.
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		return array(
			'title'      => ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '',
			'show_count' => ! empty( $new_instance['show_count'] ) ? 1 : 0,
			'show_empty' => ! empty( $new_instance['show_empty'] ) ? 1 : 0,
			'limit'      => ! empty( $new_instance['limit'] ) ? absint( $new_instance['limit'] ) : 0,
		);
	}
}
