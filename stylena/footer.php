<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The theme footer for homepage.
 *
 * @package Stylena
 */
?>

<?php
// Include FAQ section if enabled and FAQs exist.
if ( get_theme_mod( 'stylena_faq_enable', true ) ) {
	// Inline FAQ section (moved from template-parts/faq-section.php)
	// Get FAQs.
	$stylena_faqs = stylena_get_faqs();

	// Only display if there are FAQs.
	if ( ! empty( $stylena_faqs ) ) : ?>

		<!-- FAQ Section -->
		<section class="faq-section text-white py-5">
			<div class="container-fluid px-5">
				<div class="row">
					<div class="col-12">
						<h2 class="faq-title text-center mb-5">
							<?php echo esc_html( get_theme_mod( 'stylena_faq_section_title', 'FREQUENTLY ASKED QUESTIONS ABOUT SPECIFIC SERVICES' ) ); ?>
						</h2>
					</div>
				</div>

				<div class="row">
					<!-- FAQ List Column -->
					<div class="col-lg-8 col-md-12">
						<div class="faq-accordion" id="faqAccordion">
							<?php foreach ( $stylena_faqs as $stylena_index => $stylena_faq ) :
								$stylena_faq_id = 'faq-' . $stylena_faq->ID;
								?>
								<div class="faq-item mb-3">
									<h3 class="faq-item-heading">
										<button type="button" class="faq-question" id="<?php echo esc_attr( $stylena_faq_id ); ?>-btn" data-bs-target="#<?php echo esc_attr( $stylena_faq_id ); ?>" aria-expanded="false" aria-controls="<?php echo esc_attr( $stylena_faq_id ); ?>">
											<span class="faq-question-title"><?php echo esc_html( $stylena_faq->post_title ); ?></span>
											<span class="faq-toggle-icon" aria-hidden="true">
												<i class="fas fa-plus"></i>
											</span>
										</button>
									</h3>
									<div id="<?php echo esc_attr( $stylena_faq_id ); ?>" class="collapse" role="region" aria-labelledby="<?php echo esc_attr( $stylena_faq_id ); ?>-btn" data-bs-parent="#faqAccordion">
										<div class="faq-answer">
											<?php echo wp_kses_post( $stylena_faq->post_content ); ?>
										</div>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>

					<!-- Image Column -->
					<div class="col-lg-4 col-md-12 d-flex align-items-center justify-content-center">
						<div class="faq-image-container">
							<?php
							$stylena_faq_image_url = get_theme_mod( 'stylena_faq_section_image', stylena_placeholder_url( 'faq' ) );
							if ( $stylena_faq_image_url ) : ?>
								<img src="<?php echo esc_url( $stylena_faq_image_url ); ?>" alt="" class="faq-image">
							<?php else : ?>
								<div class="faq-placeholder-image">
									<div class="salon-scene">
										<div class="stylist"></div>
										<div class="client"></div>
										<div class="salon-details">
											<div class="mirror"></div>
											<div class="chair"></div>
										</div>
									</div>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</section>

<?php endif;
}
?>

<?php
/**
 * Fires before the site footer markup.
 */
do_action( 'stylena_before_footer' );
?>
<!-- Homepage Footer -->
<footer class="homepage-footer bg-dark text-white pt-5 pl-5 pr-5 pb-2">
	<div class="container-fluid">
		<div class="row">
			<!-- Left Column - Logo -->
			<div class="col-lg-3 d-flex align-items-start footer-logo-col">
				<div class="footer-logo">
					<?php
					$stylena_footer_logo_url = get_theme_mod( 'stylena_footer_logo_image' );
					if ( $stylena_footer_logo_url ) {
						echo '<img src="' . esc_url( $stylena_footer_logo_url ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" class="footer-logo-img">';
					} else {
						// Try to use the site logo if no custom footer logo.
						$stylena_site_logo = get_custom_logo();
						if ( $stylena_site_logo && ! get_theme_mod( 'stylena_footer_logo_text' ) ) {
							echo wp_kses_post( $stylena_site_logo );
						} else {
							if ( ! function_exists( 'stylena_get_font_family' ) ) {
								require_once get_template_directory() . '/inc/functions/theme-customizer.php';
							}
							$stylena_heading_font      = get_theme_mod( 'stylena_heading_font', 'montserrat' );
							$stylena_font_family       = stylena_get_font_family( $stylena_heading_font );
							$stylena_footer_text_color = get_theme_mod( 'stylena_footer_text_color', '#ffffff' );
							$stylena_footer_logo_text  = get_theme_mod( 'stylena_footer_logo_text', 'Stylena' );

							// Only apply custom color if it's different from default.
							if ( '#ffffff' !== $stylena_footer_text_color ) {
								echo '<span class="sytlena-logo footer-logo footer-logo-text">' . esc_html( $stylena_footer_logo_text ) . '</span>';
							} else {
								// Use original styling.
								echo '<span class="sytlena-logo footer-logo footer-logo-text">' . esc_html( $stylena_footer_logo_text ) . '</span>';
							}
						}
					}
					?>
				</div>
			</div>

			<!-- Center Column - Navigation Menu -->
			<div class="col-lg-3 d-flex align-items-start footer-nav-col">
				<nav class="footer-nav" aria-label="<?php esc_attr_e( 'Footer menu', 'stylena' ); ?>">
					<?php if ( has_nav_menu( 'primary' ) ) {
						wp_nav_menu(
							array(
								'theme_location' => 'primary',
								'container'      => false,
								'menu_class'     => 'list-unstyled footer-menu',
								'walker'         => new \Stylena\StylenaWalkerNavMenu(),
								'depth'          => 2,
							)
						);
					} else { ?>
						<ul class="list-unstyled footer-menu">
							<?php $stylena_u = stylena_fallback_page_url( 'services' ); if ( $stylena_u ) :
								?><li class="menu-item menu-item-type-post_type menu-item-object-page nav-item"><a href="<?php echo esc_url( $stylena_u ); ?>" class="nav-link  menu-item menu-item-type-post_type menu-item-object-page"><?php esc_html_e( 'Services', 'stylena' ); ?></a></li><?php endif; ?>
							<?php $stylena_u = stylena_fallback_page_url( 'team' ); if ( $stylena_u ) :
								?><li class="menu-item menu-item-type-post_type menu-item-object-page nav-item"><a href="<?php echo esc_url( $stylena_u ); ?>" class="nav-link  menu-item menu-item-type-post_type menu-item-object-page"><?php esc_html_e( 'Team', 'stylena' ); ?></a></li><?php endif; ?>
							<?php $stylena_u = stylena_fallback_page_url( 'membership' ); if ( $stylena_u ) :
								?><li class="menu-item menu-item-type-post_type menu-item-object-page nav-item"><a href="<?php echo esc_url( $stylena_u ); ?>" class="nav-link  menu-item menu-item-type-post_type menu-item-object-page"><?php esc_html_e( 'Membership', 'stylena' ); ?></a></li><?php endif; ?>
							<?php $stylena_u = stylena_fallback_page_url( 'portfolio' ); if ( $stylena_u ) :
								?><li class="menu-item menu-item-type-post_type menu-item-object-page nav-item"><a href="<?php echo esc_url( $stylena_u ); ?>" class="nav-link  menu-item menu-item-type-post_type menu-item-object-page"><?php esc_html_e( 'Portfolio', 'stylena' ); ?></a></li><?php endif; ?>
						</ul>
					<?php } ?>
				</nav>
			</div>

			<!-- Right Column - Business Locations -->
			<div class="col-lg-3 d-flex flex-column footer-location-col">
				<div class="location-info mb-4">
					<h2 class="h6 text-white mb-3 footer-location-title"><?php esc_html_e( 'BUSINESS LOCATION', 'stylena' ); ?></h2>
					<p class="text-white-50 mb-2 small">
						<i class="fas fa-map-marker-alt me-2" aria-hidden="true"></i>
						<?php echo wp_kses_post( get_theme_mod( 'stylena_footer_location_address', __( 'Your street address', 'stylena' ) . '</br>' . __( 'City', 'stylena' ) . '</br>' . __( 'Postcode', 'stylena' ) ) ); ?>
					</p>
					<p class="text-white-50 small">
						<i class="fas fa-envelope me-2" aria-hidden="true"></i>
						<a href="mailto:<?php echo esc_attr( get_theme_mod( 'stylena_footer_location_email', 'hello@example.com' ) ); ?>" class="text-white-50 text-decoration-none"><?php echo esc_html( get_theme_mod( 'stylena_footer_location_email', 'hello@example.com' ) ); ?></a>
					</p>
				</div>
			</div>

			<!-- Far Right Column - Contact & Social -->
			<div class="col-lg-3 d-flex flex-column align-items-end footer-contact-social">
				<div class="contact-actions mb-6">
					<!-- WhatsApp Button -->
					<a href="<?php echo esc_url( get_theme_mod( 'stylena_footer_whatsapp_link', '#' ) ); ?>" class="btn btn-outline-light btn-sm mb-4 d-block" target="_blank" rel="noopener noreferrer">
						<i class="fab fa-whatsapp me-1" aria-hidden="true"></i> <?php echo esc_html( get_theme_mod( 'stylena_footer_whatsapp_label', 'WhatsApp' ) ); ?>
					</a>
					<!-- Phone Number -->
					<a href="tel:<?php echo esc_attr( preg_replace( '/[^+0-9]/', '', get_theme_mod( 'stylena_footer_phone_number', '+1 (555) 123-4567' ) ) ); ?>" class="text-white text-decoration-none d-block text-center mb-4"<?php
						$stylena_footer_phone_label  = get_theme_mod( 'stylena_footer_phone_label', 'Phone' );
						$stylena_footer_phone_number = get_theme_mod( 'stylena_footer_phone_number', '+1 (555) 123-4567' );
					?> aria-label="<?php echo esc_attr( trim( $stylena_footer_phone_label . ' ' . $stylena_footer_phone_number ) ); ?>">
						<i class="fas fa-phone me-1" aria-hidden="true"></i>
						<?php echo esc_html( $stylena_footer_phone_label ); ?>
					</a>
				</div>

				<!-- Social Media Icons -->
				<div class="social-links d-flex justify-content-between align-items-center mb-12">
					<a href="<?php echo esc_url( get_theme_mod( 'stylena_footer_facebook', 'https://www.facebook.com/' ) ); ?>" class="text-white-50 mr-3 footer-social-link" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Follow us on Facebook', 'stylena' ); ?>">
						<i class="fab fa-facebook fa-lg" aria-hidden="true"></i>
					</a>
					<a href="<?php echo esc_url( get_theme_mod( 'stylena_footer_instagram', 'https://www.instagram.com/' ) ); ?>" class="text-white-50 mr-3 footer-social-link" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Follow us on Instagram', 'stylena' ); ?>">
						<i class="fab fa-instagram fa-lg" aria-hidden="true"></i>
					</a>
					<a href="<?php echo esc_url( get_theme_mod( 'stylena_footer_telegram', '#' ) ); ?>" class="text-white-50 mr-3 footer-social-link" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Contact us on Telegram', 'stylena' ); ?>">
						<i class="fab fa-telegram fa-lg" aria-hidden="true"></i>
					</a>
				</div>

			</div>
		</div>

		<!-- Footer Bottom - Copyright -->
		<div class="row mt-4 pt-3 border-top border-secondary">
			<div class="col-12 text-center">
				<p class="mb-0 text-white-50 small"><?php
				/* translators: 1: Copyright year, 2: Site/brand name. */
				echo esc_html( sprintf( __( '&copy; %2$s %1$s. All rights reserved.', 'stylena' ), gmdate( 'Y' ), 'Stylena' ) );
				?></p>
			</div>
		</div>
	</div>
</footer>
<?php
/**
 * Fires after the site footer markup (before wp_footer).
 */
do_action( 'stylena_after_footer' );
?>

<!--WordPress footer-->
<?php wp_footer(); ?>
<!--end WordPress footer-->
</body>

</html>