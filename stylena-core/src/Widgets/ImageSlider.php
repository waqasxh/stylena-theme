<?php
/**
 * Image slider widget (id_base preserved from the theme: stylena_image_slider_widget).
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
class ImageSlider extends \WP_Widget {

	/**
	 * Register unless the legacy theme widget (same id_base) is present.
	 * The theme copy wins until it is removed (T-101f).
	 *
	 * @return void
	 */
	public static function register() {
		if ( class_exists( 'Stylena_Image_Slider_Widget', false ) ) {
			return;
		}
		if ( ! apply_filters( 'stylena_core_register_slider_widget', true ) ) {
			return;
		}
		register_widget( __CLASS__ );
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct(
			'stylena_image_slider_widget',
			__( 'Stylena Image Slider', 'stylena-core' ),
			array( 'description' => __( 'Display the image slider', 'stylena-core' ) )
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
		$title  = ! empty( $instance['title'] ) ? $instance['title'] : '';
		$height = ! empty( $instance['height'] ) ? $instance['height'] : '500px';

		$html = stylena_core_render_slider( array( 'height' => $height ) );
		if ( '' === $html ) {
			return;
		}

		echo wp_kses_post( $args['before_widget'] );

		if ( $title ) {
			/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
			echo wp_kses_post( $args['before_title'] ) . esc_html( apply_filters( 'widget_title', $title, $instance, $this->id_base ) ) . wp_kses_post( $args['after_title'] );
		}

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- stylena_core_render_slider() returns escaped markup.
		echo $html;

		echo wp_kses_post( $args['after_widget'] );
	}

	/**
	 * Admin form.
	 *
	 * @param array $instance Settings.
	 * @return string
	 */
	public function form( $instance ) {
		$title  = ! empty( $instance['title'] ) ? $instance['title'] : '';
		$height = ! empty( $instance['height'] ) ? $instance['height'] : '500px';
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'stylena-core' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text"
				value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'height' ) ); ?>"><?php esc_html_e( 'Height:', 'stylena-core' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'height' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'height' ) ); ?>" type="text"
				value="<?php echo esc_attr( $height ); ?>">
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
		$height = ! empty( $new_instance['height'] ) ? sanitize_text_field( $new_instance['height'] ) : '500px';
		if ( ! preg_match( '/^\d+(\.\d+)?(px|em|rem|vh|%)\z/', $height ) ) {
			$height = '500px';
		}

		return array(
			'title'  => ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '',
			'height' => $height,
		);
	}
}
