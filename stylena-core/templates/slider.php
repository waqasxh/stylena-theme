<?php
/**
 * Image slider template. Override by copying to {theme}/stylena-core/slider.php.
 *
 * Variables in scope: $query (WP_Query of slides), $args (sanitised render args).
 *
 * @package StylenaCore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Variables.
 *
 * @var WP_Query $query
 * @var array    $args
 */
$stylena_core_style = sprintf(
	'--stylena-primary: %s; --stylena-slider-height: %s;',
	$args['primary_color'],
	$args['height']
);
?>
<div class="stylena-image-slider" style="<?php echo esc_attr( $stylena_core_style ); ?>"
	data-autoplay="<?php echo esc_attr( $args['autoplay'] ? 'true' : 'false' ); ?>"
	data-autoplay-speed="<?php echo esc_attr( (string) $args['autoplay_speed'] ); ?>"
	data-stop-duration="<?php echo esc_attr( (string) $args['stop_duration'] ); ?>"
	data-pause-label="<?php echo esc_attr__( 'Pause slider', 'stylena-core' ); ?>"
	data-play-label="<?php echo esc_attr__( 'Play slider', 'stylena-core' ); ?>">
	<div class="slider-container">
		<div class="slider-wrapper">
			<?php
			while ( $query->have_posts() ) :
				$query->the_post();
				$stylena_core_subtitle = get_post_meta( get_the_ID(), '_stylena_slider_subtitle', true );
				$stylena_core_image    = get_the_post_thumbnail_url( get_the_ID(), 'full' );
				?>
				<div class="slider-slide">
					<?php if ( $stylena_core_image ) : ?>
						<div class="slide-image" style="background-image: url(<?php echo esc_url( $stylena_core_image ); ?>);">
							<div class="slide-overlay"></div>
						</div>
					<?php else : ?>
						<div class="slide-image slide-image--fallback"></div>
					<?php endif; ?>

					<div class="slide-content">
						<div class="content-inner">
							<?php if ( get_the_title() ) : ?>
								<h2 class="slide-title"><?php echo esc_html( get_the_title() ); ?></h2>
							<?php endif; ?>

							<?php if ( $stylena_core_subtitle ) : ?>
								<p class="slide-subtitle"><?php echo esc_html( $stylena_core_subtitle ); ?></p>
							<?php endif; ?>
						</div>
					</div>
				</div>
			<?php endwhile; ?>
		</div>
	</div>
</div>
