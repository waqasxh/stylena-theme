<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Single Service template.
 * Displays the full detail page for one "service" custom post type entry:
 * image, price/duration, benefits, full description, booking call-to-action,
 * and related services from the same category.
 *
 * @package Stylena
 */

get_header();

while ( have_posts() ) :
	the_post();

	$stylena_service_id       = get_the_ID();
	$stylena_price            = get_post_meta( $stylena_service_id, '_service_price', true );
	$stylena_duration         = get_post_meta( $stylena_service_id, '_service_duration', true );
	$stylena_benefits         = get_post_meta( $stylena_service_id, '_service_benefits', true );
	$stylena_is_new           = get_post_meta( $stylena_service_id, '_service_is_new', true );
	$stylena_service_image_id = get_post_meta( $stylena_service_id, '_service_image', true );
	$stylena_custom_link      = get_post_meta( $stylena_service_id, '_service_link', true );
	$stylena_description      = get_post_meta( $stylena_service_id, '_service_description', true );
	$stylena_benefits_array   = $stylena_benefits ? array_filter( array_map( 'trim', explode( "\n", $stylena_benefits ) ) ) : array();

	$stylena_image_url = $stylena_service_image_id ? wp_get_attachment_image_url( $stylena_service_image_id, 'stylena-service' ) : '';
	if ( ! $stylena_image_url ) {
		$stylena_image_url = stylena_placeholder_url( 'service' );
	}
	$stylena_image_alt = $stylena_service_image_id ? ( get_post_meta( $stylena_service_image_id, '_wp_attachment_image_alt', true ) ?: get_the_title() ) : get_the_title();

	$stylena_categories = get_the_terms( $stylena_service_id, 'service_category' );
	$stylena_categories = ( is_array( $stylena_categories ) && ! is_wp_error( $stylena_categories ) ) ? $stylena_categories : array();

	$stylena_whatsapp_link  = get_theme_mod( 'stylena_whatsapp_link', '#' );
	$stylena_telephone_link = get_theme_mod( 'stylena_telephone_link', '#' );
	$stylena_telephone_text = get_theme_mod( 'stylena_telephone_text', '' );
	?>

	<main id="main" tabindex="-1" class="site-main single-service-main" role="main">
		<div class="container service-detail-container">

			<nav class="service-breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'stylena' ); ?>">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'stylena' ); ?></a>
				<span aria-hidden="true">/</span>
				<a href="<?php echo esc_url( get_post_type_archive_link( 'service' ) ); ?>"><?php esc_html_e( 'Services', 'stylena' ); ?></a>
				<span aria-hidden="true">/</span>
				<span class="service-breadcrumb-current"><?php the_title(); ?></span>
			</nav>

			<div class="row service-detail-row">
				<div class="col-lg-6 service-detail-image-col">
					<div class="service-detail-image">
						<img src="<?php echo esc_url( $stylena_image_url ); ?>" alt="<?php echo esc_attr( $stylena_image_alt ); ?>" class="img-fluid rounded">
						<?php if ( '1' === $stylena_is_new ) : ?>
							<div class="card-tag"><?php esc_html_e( 'New', 'stylena' ); ?></div>
						<?php endif; ?>
					</div>
				</div>

				<div class="col-lg-6 service-detail-content-col">
					<?php if ( ! empty( $stylena_categories ) ) : ?>
						<div class="service-detail-categories">
							<?php foreach ( $stylena_categories as $term ) : ?>
								<a href="<?php echo esc_url( get_term_link( $term ) ); ?>" class="service-category-badge"><?php echo esc_html( $term->name ); ?></a>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>

					<h1 class="service-detail-title"><?php the_title(); ?></h1>

					<div class="service-detail-meta">
						<?php if ( $stylena_price ) : ?>
							<div class="service-detail-price">
								<span class="service-detail-meta-label"><?php esc_html_e( 'Price', 'stylena' ); ?></span>
								<span class="service-detail-meta-value"><?php echo esc_html( stylena_format_price( $stylena_price ) ); ?></span>
							</div>
						<?php endif; ?>
						<?php if ( $stylena_duration ) : ?>
							<div class="service-detail-duration">
								<span class="service-detail-meta-label"><?php esc_html_e( 'Duration', 'stylena' ); ?></span>
								<span class="service-detail-meta-value"><?php echo esc_html( $stylena_duration ); ?></span>
							</div>
						<?php endif; ?>
					</div>

					<div class="service-detail-summary">
						<?php
						if ( ! empty( $stylena_description ) ) {
							echo wp_kses_post( wpautop( esc_html( $stylena_description ) ) );
						} else {
							the_excerpt();
						}
						?>
					</div>

					<?php if ( ! empty( $stylena_benefits_array ) ) : ?>
						<ul class="service-detail-benefits">
							<?php foreach ( $stylena_benefits_array as $stylena_benefit ) : ?>
								<li><i class="fas fa-check" aria-hidden="true"></i> <?php echo esc_html( $stylena_benefit ); ?></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>

					<div class="service-detail-cta">
						<?php if ( $stylena_custom_link ) : ?>
							<a href="<?php echo esc_url( $stylena_custom_link ); ?>" class="btn btn-primary service-cta-btn" target="_blank" rel="noopener noreferrer">
								<?php esc_html_e( 'Book This Service', 'stylena' ); ?>
							</a>
						<?php else : ?>
							<?php if ( $stylena_whatsapp_link && '#' !== $stylena_whatsapp_link ) : ?>
								<a href="<?php echo esc_url( $stylena_whatsapp_link ); ?>" class="btn btn-primary service-cta-btn" target="_blank" rel="noopener noreferrer">
									<?php esc_html_e( 'Book via WhatsApp', 'stylena' ); ?>
								</a>
							<?php endif; ?>
							<?php if ( $stylena_telephone_link && '#' !== $stylena_telephone_link ) : ?>
								<a href="<?php echo esc_url( $stylena_telephone_link ); ?>" class="btn btn-outline-dark service-cta-btn">
									<?php echo esc_html(
										$stylena_telephone_text ? sprintf(
										/* translators: %s: phone number */
											__( 'Call %s', 'stylena' ),
											$stylena_telephone_text
										) : __( 'Call Us', 'stylena' )
									); ?>
								</a>
							<?php endif; ?>
						<?php endif; ?>
					</div>
				</div>
			</div>

			<?php if ( get_the_content() ) : ?>
				<div class="service-detail-full-description">
					<h2><?php esc_html_e( 'About This Service', 'stylena' ); ?></h2>
					<?php the_content(); ?>
				</div>
			<?php endif; ?>

			<?php
			// Related services: same category, excluding the current service.
			if ( ! empty( $stylena_categories ) ) {
				$stylena_related_query = new WP_Query(
					array(
						'post_type'      => 'service',
						'posts_per_page' => 3,
						'post__not_in'   => array( $stylena_service_id ),
						'tax_query'      => array(
							array(
								'taxonomy' => 'service_category',
								'field'    => 'term_id',
								'terms'    => wp_list_pluck( $stylena_categories, 'term_id' ),
							),
						),
					)
				);
				if ( $stylena_related_query->have_posts() ) :
					?>
					<div class="service-detail-related">
						<h2><?php esc_html_e( 'Related Services', 'stylena' ); ?></h2>
						<div class="row">
							<?php while ( $stylena_related_query->have_posts() ) :
								$stylena_related_query->the_post();
								$stylena_related_image_id  = get_post_meta( get_the_ID(), '_service_image', true );
								$stylena_related_image_url = $stylena_related_image_id ? wp_get_attachment_image_url( $stylena_related_image_id, 'stylena-service' ) : stylena_placeholder_url( 'service' );
								$stylena_related_price     = get_post_meta( get_the_ID(), '_service_price', true );
								?>
								<div class="col-md-4 related-service-col">
									<a href="<?php the_permalink(); ?>" class="related-service-card">
										<img src="<?php echo esc_url( $stylena_related_image_url ); ?>" alt="<?php the_title_attribute(); ?>" class="related-service-image">
										<span class="related-service-title"><?php the_title(); ?></span>
										<?php if ( $stylena_related_price ) : ?>
											<span class="related-service-price"><?php echo esc_html( stylena_format_price( $stylena_related_price ) ); ?></span>
										<?php endif; ?>
									</a>
								</div>
							<?php endwhile; ?>
						</div>
					</div>
					<?php
				endif;
				wp_reset_postdata();
			}
			?>

		</div>
	</main>

	<?php
endwhile;

get_footer();
