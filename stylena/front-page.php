<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The front page template.
 * This template will be used for the homepage.
 *
 * @package Stylena
 */

// No container wrapper for full-width homepage
get_header();
?>

<?php
// Get hero margin settings
$stylena_desktop_margin_value = get_theme_mod( 'stylena_hero_top_margin_desktop_value', '120' );
$stylena_desktop_margin_unit  = get_theme_mod( 'stylena_hero_top_margin_desktop_unit', 'px' );
$stylena_mobile_margin_value  = get_theme_mod( 'stylena_hero_top_margin_mobile_value', '80' );
$stylena_mobile_margin_unit   = get_theme_mod( 'stylena_hero_top_margin_mobile_unit', 'px' );

$stylena_desktop_margin = $stylena_desktop_margin_value . $stylena_desktop_margin_unit;
$stylena_mobile_margin  = $stylena_mobile_margin_value . $stylena_mobile_margin_unit;
?>

<main id="main" tabindex="-1" class="site-main homepage-main" role="main">
	<?php
	/**
	 * Fires at the start of the homepage main content.
	 */
	do_action( 'stylena_before_front_page_sections' );

	/** This filter is documented in docs/hooks.md. */
	$stylena_hero_classes = apply_filters( 'stylena_hero_classes', 'hero-section position-relative' );
	$stylena_cta_defaults = array(
		'primary'   => 'btn btn-primary hero-btn',
		'secondary' => 'btn btn-outline-light hero-btn',
	);
	$stylena_cta_classes  = apply_filters( 'stylena_hero_cta_classes', $stylena_cta_defaults );
	$stylena_cta_classes  = wp_parse_args( is_array( $stylena_cta_classes ) ? $stylena_cta_classes : array(), $stylena_cta_defaults );
	?>
	<?php if ( stylena_section_enabled( 'hero' ) ) : ?>
	<!-- Hero Section with Video Background -->
	<section class="<?php echo esc_attr( $stylena_hero_classes ); ?>"
		data-desktop-margin="<?php echo esc_attr( $stylena_desktop_margin ); ?>"
		data-mobile-margin="<?php echo esc_attr( $stylena_mobile_margin ); ?>">
		<?php
		$stylena_hero_video_url  = get_theme_mod( 'stylena_hero_video', '' );
		$stylena_hero_poster_url = get_theme_mod( 'stylena_hero_poster', stylena_placeholder_url( 'hero' ) );
		?>
		<div class="hero-video-container" style="--stylena-hero-poster:url('<?php echo esc_url( $stylena_hero_poster_url ); ?>');">
			<?php if ( $stylena_hero_video_url ) : ?>
			<video class="hero-video" muted loop playsinline preload="none" poster="<?php echo esc_url( $stylena_hero_poster_url ); ?>">
				<source data-src="<?php echo esc_url( $stylena_hero_video_url ); ?>" type="video/mp4">
			</video>
			<?php endif; ?>
			<div class="hero-overlay"></div>
		</div>

		<div class="hero-content">
			<div class="container-fluid">
				<div class="row justify-content-center text-center">
					<div class="col-12">
						<h1 class="hero-title"><?php echo esc_html( get_theme_mod( 'stylena_hero_title', 'Stylena' ) ); ?></h1>
						<p class="hero-subtitle"><?php echo esc_html( get_theme_mod( 'stylena_hero_subtitle', 'A modern beauty and wellness studio. Add your own tagline here.' ) ); ?></p>
						<div class="hero-cta">
							<?php
							$stylena_cta1_text = get_theme_mod( 'stylena_hero_cta1_text', 'Explore Services' );
							$stylena_cta1_url  = get_theme_mod( 'stylena_hero_cta1_url', '#services' );
							$stylena_cta2_text = get_theme_mod( 'stylena_hero_cta2_text', 'Book Now' );
							$stylena_cta2_url  = get_theme_mod( 'stylena_hero_cta2_url', '#contact' );
							?>
							<?php if ( $stylena_cta1_text && $stylena_cta1_url ) : ?>
								<a href="<?php echo esc_url( $stylena_cta1_url ); ?>" class="<?php echo esc_attr( $stylena_cta_classes['primary'] ); ?>"><?php echo esc_html( $stylena_cta1_text ); ?></a>
							<?php endif; ?>
							<?php if ( $stylena_cta2_text && $stylena_cta2_url ) : ?>
								<a href="<?php echo esc_url( $stylena_cta2_url ); ?>" class="<?php echo esc_attr( $stylena_cta_classes['secondary'] ); ?>"><?php echo esc_html( $stylena_cta2_text ); ?></a>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php endif; // hero toggle ?>

	<?php if ( stylena_section_enabled( 'about' ) ) : ?>
	<!-- About Section -->
	<section class="about-section m-4 py-5">
		<div class="container">
			<div class="row align-items-center stylena-about-box">
				<div class="row">
					<div class="col-12">
						<h2 class="stylena-heading-font">
							<?php echo esc_html( get_theme_mod( 'stylena_about_title', 'Stylena' ) ); ?>
						</h2>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-7">
						<p><?php echo wp_kses_post( get_theme_mod( 'stylena_about_desc1', 'Your business is more than just a place to visit; it’s where every detail is focused on creating a great experience. Use this space to introduce your team, your approach and the services you offer. Replace this text from Appearance > Customize > About section.' ) ); ?></p>
						<p><?php echo wp_kses_post( get_theme_mod( 'stylena_about_desc2', 'Use this space to share your story and values, and to tell visitors what makes your business different. You can edit this text in the Customizer under About.' ) ); ?></p>
					</div>
					<div class="col-lg-5" id="founder-col">
						<div class="founder-card text-center">
							<div class="founder-image-margin">
								<img src="<?php echo esc_url( get_theme_mod( 'stylena_about_founder_image', stylena_placeholder_url( 'founder' ) ) ); ?>" alt="<?php echo esc_attr( get_theme_mod( 'stylena_about_founder_name', 'Your Founder Name' ) ); ?>" class="founder-image img-fluid">
							</div>
							<div>
								<h4 class="founder-name"><?php echo esc_html( get_theme_mod( 'stylena_about_founder_name', 'Your Founder Name' ) ); ?></h4>
								<h6 class="founder-title"><?php echo esc_html( get_theme_mod( 'stylena_about_founder_title', 'Founder of Stylena' ) ); ?></h6>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php endif; // about toggle ?>

	<!-- Services Section -->
	<?php
	// Check if there are any services to display before showing the section
	$stylena_has_services_to_show = false;
	if ( stylena_section_enabled( 'services' ) && stylena_core_active() ) {
		$stylena_services_check_query = new WP_Query(
			array(
				'post_type'              => 'service',
				'posts_per_page'         => 1,
				'no_found_rows'          => true,
				'update_post_term_cache' => false,
				'post_status'            => 'publish',
				'meta_key'               => '_service_sort_order',
				'orderby'                => 'meta_value_num',
				'order'                  => 'ASC',
			)
		);

		$stylena_has_services_to_show = $stylena_services_check_query->have_posts();
		wp_reset_postdata();
	}

	// Also check if the homepage-services sidebar has any active widgets
	if ( stylena_section_enabled( 'services' ) && ! $stylena_has_services_to_show && is_active_sidebar( 'homepage-services' ) ) {
		ob_start();
		dynamic_sidebar( 'homepage-services' );
		$stylena_widget_output        = ob_get_clean();
		$stylena_has_services_to_show = ! empty( trim( strip_tags( $stylena_widget_output ) ) );
	}

	// Only display the services section if there are services or widgets to show
	if ( $stylena_has_services_to_show ) :
		?>
		<section class="services m-4" id="services">
			<?php
			// Get customizer values for Primary Color and Body Font
			$stylena_primary_color = get_theme_mod( 'stylena_primary_color', '#FEFEFE' );
			$stylena_body_font     = stylena_get_font_family( get_theme_mod( 'stylena_body_font', 'montserrat' ) );
			?>
			<div class="container">
				<div class="title-inner">
					<h2 class="mb-0 me-3 stylena-tc-heading">
						<?php echo esc_html( get_theme_mod( 'stylena_services_title', 'Our Services' ) ); ?>
					</h2>
					<p class="mb-0 me-3 stylena-tc">
						<?php echo wp_kses_post( get_theme_mod( 'stylena_services_subtitle', 'We regularly introduce advanced technologies into our work, so new services regularly appear in our studio.' ) ); ?>
					</p>
					<!-- Desktop buttons stay here -->
					<div class="swiper-btns w-100 desktop-btns">
						<button class="swiper-button-prev me-2 swiper-arrow-btn" tabindex="0" role="button" aria-label="<?php esc_attr_e( "Previous slide", "stylena" ); ?>">
							<img src="<?php echo esc_url( get_theme_file_uri( '/img/arrow.svg' ) ); ?>" alt="" aria-hidden="true" class="arrow-svg flipped" />
						</button>
						<button class="swiper-button-next swiper-arrow-btn" tabindex="0" role="button" aria-label="<?php esc_attr_e( "Next slide", "stylena" ); ?>">
							<img src="<?php echo esc_url( get_theme_file_uri( '/img/arrow.svg' ) ); ?>" alt="" aria-hidden="true" class="arrow-svg" />
						</button>
					</div>
				</div>
				<div class="services-wrapper services-slider">
					<div class="swiper services-swiper">
						<div class="swiper-wrapper">
							<?php
							// Get the number of services to display from customizer setting
							$stylena_services_display_count = get_theme_mod( 'stylena_services_display_count', 6 );

							// Get services ordered by admin sort order then date/title
							$stylena_services_query = stylena_core_active() ? new WP_Query(
								array(
									'post_type'      => 'service',
									'posts_per_page' => $stylena_services_display_count,
									'no_found_rows'  => true,
									'update_post_term_cache' => false,
									'meta_key'       => '_service_sort_order',
									'orderby'        => array(
										'meta_value_num' => 'ASC',
										'date'           => 'DESC',
									),
								)
							) : null;

							$stylena_has_services = false;

							if ( $stylena_services_query && $stylena_services_query->have_posts() ) :
								$stylena_has_services = true;
								while ( $stylena_services_query->have_posts() ) :
									$stylena_services_query->the_post();
									$stylena_price          = get_post_meta( get_the_ID(), '_service_price', true );
									$stylena_benefits       = get_post_meta( get_the_ID(), '_service_benefits', true );
									$stylena_service_image  = get_post_meta( get_the_ID(), '_service_image', true );
									$stylena_is_new         = get_post_meta( get_the_ID(), '_service_is_new', true );
									$stylena_service_link   = get_post_meta( get_the_ID(), '_service_link', true ) ?: get_permalink();
									$stylena_description    = get_post_meta( get_the_ID(), '_service_description', true );
									$stylena_benefits_array = $stylena_benefits ? explode( "\n", trim( $stylena_benefits ) ) : array();
									?>
									<div class="swiper-slide services-slide card">
										<?php
										// Use the custom service image if set, otherwise fall back to a
										// placeholder so the whole card still renders (a missing image
										// must not hide the title/price/description too).
										$stylena_image_url = $stylena_service_image ? wp_get_attachment_image_url( $stylena_service_image, 'stylena-service' ) : '';
										if ( $stylena_image_url ) {
											$stylena_image_alt = get_post_meta( $stylena_service_image, '_wp_attachment_image_alt', true ) ?: get_the_title();
										} else {
											$stylena_image_url = stylena_placeholder_url( 'service' );
											$stylena_image_alt = get_the_title();
										}
										$stylena_image_html = '<img src="' . esc_url( $stylena_image_url ) . '" alt="' . esc_attr( $stylena_image_alt ) . '">';

										if ( $stylena_image_html ) : ?>
											<div class="card-img">
												<?php echo wp_kses_post( $stylena_image_html ); ?>
												<?php if ( $stylena_is_new == '1' ) : ?>
													<div class="card-tag"><?php esc_html_e( "New", "stylena" ); ?></div>
												<?php endif; ?>

												<!-- Content overlay on image -->
												<div class="card-content-wrapper">
													<div class="card-inner">
														<a href="<?php echo esc_url( $stylena_service_link ); ?>" class="card-title stylena-tc">
															<?php the_title(); ?>
														</a>
														<?php if ( $stylena_price ) : ?>
															<p class="card-price stylena-tc">
																<?php esc_html_e( "from", "stylena" ); ?>
																<span class="stylena-tc"><?php echo esc_html( stylena_format_price( $stylena_price ) ); ?></span>
															</p>
														<?php endif; ?>
													</div>
													<div class="card-text stylena-tc stylena-card-text">
														<?php
														// Prefer multi-line description meta; fallback to post content if empty
														if ( ! empty( $stylena_description ) ) {
															// convert new lines to paragraphs and escape
															echo wp_kses_post( wpautop( esc_html( $stylena_description ) ) );
														} else {
															$stylena_desc = get_the_content();
															if ( $stylena_desc ) {
																echo wp_kses_post( wpautop( $stylena_desc ) );
															}
														}
														?>
													</div>
													<div class="card-btns">
														<a href="<?php echo esc_url( $stylena_service_link ); ?>" class="card-btn btn btn-transparent stylena-tc"><?php esc_html_e( "Reserve Now", "stylena" ); ?></a>
													</div>
												</div>
											</div>
										<?php endif; ?>
									</div>
									<?php
								endwhile;
								wp_reset_postdata();
							endif;

							// If no services posts exist, check for widgets in homepage-services sidebar
							if ( ! $stylena_has_services && is_active_sidebar( 'homepage-services' ) ) :
								ob_start();
								dynamic_sidebar( 'homepage-services' );
								$stylena_widget_output = ob_get_clean();
                                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- widget markup is generated and escaped by registered widgets; kses would strip form controls.
								echo $stylena_widget_output;
								$stylena_has_services = ! empty( trim( strip_tags( $stylena_widget_output ) ) );
							endif;

							// If still no services content, show the no services message
							if ( ! $stylena_has_services ) :
								?>
								<div class="swiper-slide">
									<div class="col-12 text-center">
										<p class="stylena-tc">
											<?php echo esc_html( get_theme_mod( 'stylena_services_no_services_text', 'No services available at the moment.' ) ); ?>
										</p>
									</div>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<!-- Mobile buttons appear here -->
				<div class="swiper-btns mobile-btns">
					<button class="swiper-button-prev me-2 swiper-arrow-btn mobile-prev" tabindex="0" role="button" aria-label="<?php esc_attr_e( "Previous slide", "stylena" ); ?>">
						<img src="<?php echo esc_url( get_theme_file_uri( '/img/arrow.svg' ) ); ?>" alt="" aria-hidden="true" class="arrow-svg flipped" />
					</button>
					<button class="swiper-button-next swiper-arrow-btn mobile-next" tabindex="0" role="button" aria-label="<?php esc_attr_e( "Next slide", "stylena" ); ?>">
						<img src="<?php echo esc_url( get_theme_file_uri( '/img/arrow.svg' ) ); ?>" alt="" aria-hidden="true" class="arrow-svg" />
					</button>
				</div>
				<?php $stylena_view_all_services = stylena_fallback_page_url( 'services' ); ?>
				<?php if ( $stylena_view_all_services ) : ?>
					<div class="text-center mt-4 stylena-view-all">
						<a class="<?php echo esc_attr( $stylena_cta_classes['primary'] ); ?>" href="<?php echo esc_url( $stylena_view_all_services ); ?>"><?php echo esc_html( stylena_get_view_all_label() ); ?></a>
					</div>
				<?php endif; ?>
			</div>
		</section>
	<?php endif; // End services section conditional
	?>

	<!-- Team Section -->
	<?php
	// Check if portfolio section is enabled in customizer AND if there are team members to display
	if ( get_theme_mod( 'stylena_portfolio_enable', true ) && stylena_core_active() ) :

		// Check if there are any team members to display before showing the section
		$stylena_team_check_query = new WP_Query(
			array(
				'post_type'              => 'team',
				'posts_per_page'         => 1,
				'no_found_rows'          => true,
				'update_post_term_cache' => false,
				'post_status'            => 'publish',
				'meta_key'               => '_team_sort_order',
				'orderby'                => 'meta_value_num',
				'order'                  => 'ASC',
			)
		);

		$stylena_has_team_to_show = $stylena_team_check_query->have_posts();
		wp_reset_postdata();

		// Only display the team section if there are team members to show
		if ( $stylena_has_team_to_show ) :

			// Get customizer settings
			$stylena_primary_color      = get_theme_mod( 'stylena_primary_color', '#FEFEFE' );
			$stylena_body_font          = stylena_get_font_family( get_theme_mod( 'stylena_body_font', 'montserrat' ) );
			$stylena_portfolio_title    = get_theme_mod( 'stylena_portfolio_title', 'Our Team' );
			$stylena_portfolio_subtitle = get_theme_mod( 'stylena_portfolio_subtitle', 'Meet our talented team of beauty professionals' );
			$stylena_items_count        = get_theme_mod( 'stylena_portfolio_items_count', 6 );
			?>

			<section class="portfolio-section team-section m-4" id="team">
				<div class="container">
					<div class="title-inner">
						<h2 class="mb-0 me-3 stylena-tc-heading">
							<?php echo esc_html( $stylena_portfolio_title ); ?>
						</h2>
						<p class="mb-0 me-3 stylena-tc">
							<?php echo wp_kses_post( $stylena_portfolio_subtitle ); ?>
						</p>
						<!-- Desktop navigation buttons -->
						<div class="swiper-btns w-100 desktop-btns">
							<button class="swiper-button-prev me-2 swiper-arrow-btn" tabindex="0" role="button" aria-label="<?php esc_attr_e( "Previous slide", "stylena" ); ?>">
								<img src="<?php echo esc_url( get_theme_file_uri( '/img/arrow.svg' ) ); ?>" alt="" aria-hidden="true" class="arrow-svg flipped" />
							</button>
							<button class="swiper-button-next swiper-arrow-btn" tabindex="0" role="button" aria-label="<?php esc_attr_e( "Next slide", "stylena" ); ?>">
								<img src="<?php echo esc_url( get_theme_file_uri( '/img/arrow.svg' ) ); ?>" alt="" aria-hidden="true" class="arrow-svg" />
							</button>
						</div>
					</div>
					<div class="portfolio-wrapper portfolio-slider">
						<div class="swiper portfolio-swiper">
							<div class="swiper-wrapper">
								<?php
								// Get team members ordered by admin sort order
								$stylena_team_query = new WP_Query(
									array(
										'post_type'      => 'team',
										'posts_per_page' => $stylena_items_count,
										'no_found_rows'  => true,
										'update_post_term_cache' => false,
										'meta_key'       => '_team_sort_order',
										'orderby'        => array(
											'meta_value_num' => 'ASC',
											'date' => 'DESC',
										),
									)
								);

								$stylena_has_team = false;

								if ( $stylena_team_query->have_posts() ) :
									$stylena_has_team = true;
									while ( $stylena_team_query->have_posts() ) :
										$stylena_team_query->the_post();
										$stylena_position    = get_post_meta( get_the_ID(), '_team_position', true );
										$stylena_specialties = get_post_meta( get_the_ID(), '_team_specialties', true );
										$stylena_bio         = get_post_meta( get_the_ID(), '_team_bio', true );
										$stylena_team_image  = get_post_meta( get_the_ID(), '_team_image', true );
										?>
										<div class="swiper-slide portfolio-slide card">
											<?php
											$stylena_team_image_url = ( ! empty( $stylena_team_image ) && intval( $stylena_team_image ) > 0 ) ? wp_get_attachment_image_url( $stylena_team_image, 'stylena-team' ) : '';
											if ( ! $stylena_team_image_url ) {
												$stylena_team_image_url = stylena_placeholder_url( 'team' );
											}
											?>
											<div class="card-img">
												<img src="<?php echo esc_url( $stylena_team_image_url ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" />
											</div>
											<div class="card-inner">
												<div class="card-header">
													<h3 class="card-title">
														<?php the_title(); ?>
													</h3>
													<?php if ( $stylena_position ) : ?>
														<p class="card-position">
															<?php echo esc_html( $stylena_position ); ?>
														</p>
													<?php endif; ?>
												</div>

												<?php
												// Always show bio and specialties content
												$stylena_has_content = ! empty( $stylena_bio ) || ! empty( $stylena_specialties );
												?>
												<div class="card-text">
													<?php if ( ! empty( $stylena_bio ) ) : ?>
														<p class="bio-text"><?php echo esc_html( $stylena_bio ); ?></p>
													<?php endif; ?>

													<?php if ( ! empty( $stylena_specialties ) ) : ?>
														<p class="specialties-text"><strong>Specialties:</strong> <?php echo esc_html( $stylena_specialties ); ?></p>
													<?php endif; ?>

												</div>
											</div>
										</div>
										<?php
									endwhile;
									wp_reset_postdata();
								endif;

								// If no team members exist, show placeholder message
								if ( ! $stylena_has_team ) :
									?>
									<div class="swiper-slide">
										<div class="col-12 text-center">
											<p class="stylena-tc">
												<?php esc_html_e( 'No team members available at the moment.', 'stylena' ); ?>
											</p>
										</div>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
					<!-- Mobile buttons appear here -->
					<div class="swiper-btns mobile-btns">
						<button class="swiper-button-prev me-2 swiper-arrow-btn mobile-prev" tabindex="0" role="button" aria-label="<?php esc_attr_e( "Previous slide", "stylena" ); ?>">
							<img src="<?php echo esc_url( get_theme_file_uri( '/img/arrow.svg' ) ); ?>" alt="" aria-hidden="true" class="arrow-svg flipped" />
						</button>
						<button class="swiper-button-next swiper-arrow-btn mobile-next" tabindex="0" role="button" aria-label="<?php esc_attr_e( "Next slide", "stylena" ); ?>">
							<img src="<?php echo esc_url( get_theme_file_uri( '/img/arrow.svg' ) ); ?>" alt="" aria-hidden="true" class="arrow-svg" />
						</button>
					</div>
					<?php $stylena_view_all_team = stylena_fallback_page_url( 'team' ); ?>
					<?php if ( $stylena_view_all_team ) : ?>
						<div class="text-center mt-4 stylena-view-all">
							<a class="<?php echo esc_attr( $stylena_cta_classes['primary'] ); ?>" href="<?php echo esc_url( $stylena_view_all_team ); ?>"><?php echo esc_html( stylena_get_view_all_label() ); ?></a>
						</div>
					<?php endif; ?>
				</div>
			</section>

			<!-- Portfolio script moved to js/custom.js -->

		<?php endif; // End team members check
		?>
	<?php endif; // End team enable check
	?>


	<!-- Gutenberg Content Section (Custom HTML blocks, etc.) -->
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) :
			the_post(); ?>
			<?php
			$stylena_content = get_the_content();
			if ( ! empty( trim( $stylena_content ) ) ) :
				?>
				<section class="gutenberg-content-section">
					<?php the_content(); ?>
				</section>
			<?php endif; ?>
		<?php endwhile; ?>
	<?php endif; ?>


	<!-- Image Slider Section (inlined from template-parts/image-slider.php) -->
	<?php if ( get_theme_mod( 'stylena_slider_enable', true ) && function_exists( 'stylena_core_render_slider' ) ) : ?>
		<section class="image-slider-section">
			<div class="px-0">
				<?php
				// Slider settings come from the Customizer via the stylena_core_slider_args filter.
                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- plugin API returns escaped markup.
				echo stylena_core_render_slider();
				?>
			</div>
		</section>
	<?php endif; ?>

	<!-- Portfolio Section (portfolio post type; hidden when disabled, plugin inactive or no items) -->
	<?php
	if ( get_theme_mod( 'stylena_home_portfolio_enable', true ) && stylena_core_active() ) :
		$stylena_home_portfolio_count = stylena_sanitize_home_portfolio_count( get_theme_mod( 'stylena_home_portfolio_count', 6 ) );
		$stylena_home_portfolio_items = stylena_get_portfolio_items( array( 'limit' => $stylena_home_portfolio_count ) );
		if ( ! empty( $stylena_home_portfolio_items ) ) :
			$stylena_home_portfolio_title = get_theme_mod( 'stylena_home_portfolio_title', __( 'Our Work', 'stylena' ) );
			$stylena_home_portfolio_sub   = get_theme_mod( 'stylena_home_portfolio_subtitle', __( 'A selection of our recent projects', 'stylena' ) );
			?>
			<section class="stylena-portfolio-home m-4" id="portfolio" aria-labelledby="home-portfolio-title">
				<div class="container">
					<div class="title-inner">
						<h2 id="home-portfolio-title" class="mb-0 me-3">
							<?php echo esc_html( $stylena_home_portfolio_title ); ?>
						</h2>
						<?php if ( '' !== $stylena_home_portfolio_sub ) : ?>
							<p class="mb-0 me-3">
								<?php echo esc_html( $stylena_home_portfolio_sub ); ?>
							</p>
						<?php endif; ?>
						<!-- Desktop buttons stay here -->
						<div class="swiper-btns w-100 desktop-btns">
							<button class="swiper-button-prev me-2 swiper-arrow-btn" tabindex="0" role="button" aria-label="<?php esc_attr_e( 'Previous slide', 'stylena' ); ?>">
<img src="<?php echo esc_url( get_theme_file_uri( '/img/arrow.svg' ) ); ?>" alt="" aria-hidden="true" class="arrow-svg flipped" />
</button>
							<button class="swiper-button-next swiper-arrow-btn" tabindex="0" role="button" aria-label="<?php esc_attr_e( 'Next slide', 'stylena' ); ?>">
<img src="<?php echo esc_url( get_theme_file_uri( '/img/arrow.svg' ) ); ?>" alt="" aria-hidden="true" class="arrow-svg" />
</button>
						</div>
					</div>
					<div class="portfolio-wrapper portfolio-slider">
						<?php stylena_render_portfolio_grid( $stylena_home_portfolio_items, array( 'slider' => true ) ); ?>
					</div>
					<!-- Mobile buttons appear here -->
					<div class="swiper-btns mobile-btns">
						<button class="swiper-button-prev me-2 swiper-arrow-btn mobile-prev" tabindex="0" role="button" aria-label="<?php esc_attr_e( 'Previous slide', 'stylena' ); ?>">
<img src="<?php echo esc_url( get_theme_file_uri( '/img/arrow.svg' ) ); ?>" alt="" aria-hidden="true" class="arrow-svg flipped" />
</button>
						<button class="swiper-button-next swiper-arrow-btn mobile-next" tabindex="0" role="button" aria-label="<?php esc_attr_e( 'Next slide', 'stylena' ); ?>">
<img src="<?php echo esc_url( get_theme_file_uri( '/img/arrow.svg' ) ); ?>" alt="" aria-hidden="true" class="arrow-svg" />
</button>
					</div>
					<?php $stylena_home_portfolio_page = stylena_fallback_page_url( 'portfolio' ); ?>
					<?php if ( $stylena_home_portfolio_page ) : ?>
						<div class="text-center mt-4 stylena-view-all">
							<a class="<?php echo esc_attr( $stylena_cta_classes['primary'] ); ?>" href="<?php echo esc_url( $stylena_home_portfolio_page ); ?>"><?php echo esc_html( stylena_get_view_all_label() ); ?></a>
						</div>
					<?php endif; ?>
				</div>
			</section>
		<?php endif; ?>
	<?php endif; // End home portfolio ?>

	<!-- Reviews Section -->
	<?php stylena_render_reviews(); ?>

	<!-- Advice Section -->
	<?php stylena_render_advice_section(); ?>

	<!-- Services script moved to js/custom.js -->
	<?php
	/**
	 * Fires at the end of the homepage main content.
	 */
	do_action( 'stylena_after_front_page_sections' );
	?>

</main>

<?php
get_footer();
?>
