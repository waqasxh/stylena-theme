<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The theme header for homepage.
 * Custom header with two-row navigation and logo in center.
 *
 * @package Stylena
 */
?>
<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

	<!-- Fonts (Exo, Aboreto, Montserrat) are self-hosted via css/fonts.css; no third-party font requests -->
	<!--WordPress head-->
	<?php wp_head(); ?>
	<!--end WordPress head-->
</head>

<body <?php body_class( is_front_page() ? 'homepage' : '' ); ?>>
	<?php
	if ( function_exists( 'wp_body_open' ) ) {
		wp_body_open();
	}
	?>
	<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'stylena' ); ?></a>

	<?php
	// Ensure Book target attribute is always defined to avoid undefined variable notices.
	$stylena_book_new_tab     = get_theme_mod( 'stylena_book_open_new_tab', false );
	$stylena_book_target_attr = $stylena_book_new_tab ? ' target="_blank" rel="noopener"' : '';
	?>

	<?php
	/**
	 * Fires before the site header markup.
	 */
	do_action( 'stylena_before_header' );
	?>
	<!-- Custom Homepage Header -->
	<header class="homepage-header position-fixed w-100">

		<!-- Top Row Navigation -->
		<div class="header-top">
			<div class="container-fluid">
				<div class="row align-items-center">
					<!-- Left Menu Items -->
					<div class="col-md-5">
						<nav class="top-nav-left" aria-label="<?php esc_attr_e( 'Secondary menu', 'stylena' ); ?>">
							<?php if ( has_nav_menu( 'top-left' ) ) {
								wp_nav_menu(
									array(
										'theme_location' => 'top-left',
										'container'      => false,
										'menu_class'     => 'nav-menu top-nav-menu',
										'depth'          => 1,
									)
								);
							} else { ?>
								<?php // Fallback nav uses $stylena_book_target_attr defined above.
								?>
								<ul class="nav-menu top-nav-menu">
									<?php $stylena_u = stylena_fallback_page_url( 'about-us' ); if ( $stylena_u ) :
										?><li><a href="<?php echo esc_url( $stylena_u ); ?>"><?php esc_html_e( 'About Us', 'stylena' ); ?></a></li><?php endif; ?>
									<?php $stylena_u = stylena_fallback_page_url( 'contact-us' ); if ( $stylena_u ) :
										?><li><a href="<?php echo esc_url( $stylena_u ); ?>"><?php esc_html_e( 'Contact Us', 'stylena' ); ?></a></li><?php endif; ?>
									<?php $stylena_u = stylena_fallback_page_url( 'reviews' ); if ( $stylena_u ) :
										?><li><a href="<?php echo esc_url( $stylena_u ); ?>"><?php esc_html_e( 'Reviews', 'stylena' ); ?></a></li><?php endif; ?>
								</ul>
							<?php } ?>
						</nav>
					</div>

					<!-- Center Logo -->
					<div class="col-md-2 text-center">
						<div class="site-logo">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo-link">
								<?php
								$stylena_custom_logo_id            = get_theme_mod( 'custom_logo' );
								$stylena_logo_variant_html = stylena_get_logo_variant_html();
								if ( $stylena_logo_variant_html ) {
									// Markup is escaped inside stylena_get_logo_variant_html().
									echo $stylena_logo_variant_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								} elseif ( $stylena_custom_logo_id ) {
									$stylena_logo_alt = get_post_meta( $stylena_custom_logo_id, '_wp_attachment_image_alt', true );
									if ( empty( $stylena_logo_alt ) ) {
										$stylena_logo_alt = get_bloginfo( 'name' ) . ' Logo';
									}
									$stylena_logo_image = wp_get_attachment_image(
										$stylena_custom_logo_id,
										'full',
										false,
										array(
											'class' => 'custom-logo',
											'alt'   => esc_attr( $stylena_logo_alt ),
										)
									);
									echo wp_kses_post( $stylena_logo_image );
								} else {
									if ( ! function_exists( 'stylena_get_font_family' ) ) {
										require_once get_template_directory() . '/inc/functions/theme-customizer.php';
									}
									$stylena_heading_font      = get_theme_mod( 'stylena_heading_font', 'montserrat' );
									$stylena_font_family       = stylena_get_font_family( $stylena_heading_font );
									$stylena_header_logo_color = get_theme_mod( 'stylena_header_logo_color', '#bc9c25' );
									$stylena_header_logo_text  = get_theme_mod( 'stylena_header_logo_text', 'Stylena' );

									// Only apply custom color if it's different from default.
									if ( '#bc9c25' !== $stylena_header_logo_color ) {
										echo '<span class="header-logo-text">' . esc_html( $stylena_header_logo_text ) . '</span>';
									} else {
										// Use original styling.
										echo '<span class="header-logo-text">' . esc_html( $stylena_header_logo_text ) . '</span>';
									}
								}
								?>
							</a>
						</div>
					</div>

					<!-- Right Menu Items -->
					<div class="col-md-5 text-right d-none d-md-block">
						<nav class="top-nav-right" aria-label="<?php esc_attr_e( 'Contact and search', 'stylena' ); ?>">
							<ul class="nav-menu top-nav-menu">
								<li>
									<a href="<?php echo esc_url( get_theme_mod( 'stylena_whatsapp_link', '#' ) ); ?>" target="_blank" rel="nofollow" class="header-wa">
										<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
											<path fill-rule="evenodd" clip-rule="evenodd" d="M16.4005 3.62703C14.733 1.92432 12.4278 1 10.0736 1C5.07085 1 1.04905 5.03784 1.09809 9.95135C1.09809 11.5081 1.53951 13.0162 2.2752 14.3784L1 19L5.75749 17.7838C7.08174 18.5135 8.55313 18.8541 10.0245 18.8541C14.9782 18.8541 19 14.8162 19 9.9027C19 7.51892 18.0681 5.28108 16.4005 3.62703ZM13.8501 11.7027L14.3896 11.9459C14.4877 11.9946 14.5368 12.0432 14.5368 12.0432V12.3838C14.4877 12.627 14.4387 12.8703 14.3406 13.0649C14.2916 13.1622 14.1935 13.2595 14.0954 13.3568C14.0463 13.4054 13.9973 13.4419 13.9482 13.4784C13.8992 13.5149 13.8501 13.5514 13.8011 13.6L13.703 13.6973C13.6479 13.7519 13.5928 13.7759 13.5291 13.8036C13.4793 13.8253 13.4242 13.8492 13.3597 13.8919C13.1635 13.9892 12.8692 14.0865 12.624 14.0865H12.1335C12.0599 14.0622 11.9741 14.05 11.8883 14.0378C11.8025 14.0257 11.7166 14.0135 11.6431 13.9892C10.3188 13.6486 9.14169 13.0162 8.11172 12.1405C8.04763 12.0452 7.96261 11.9706 7.87034 11.8897C7.82137 11.8467 7.77036 11.8019 7.71935 11.7514L7.52316 11.5568C6.73842 10.827 6.14986 10 5.70845 9.07568L5.6594 8.92973C5.51226 8.58919 5.41417 8.2 5.41417 7.81081C5.41417 7.27568 5.61035 6.74054 5.95368 6.3027C6.00273 6.25405 6.03951 6.20541 6.07629 6.15676C6.11308 6.10811 6.14986 6.05946 6.19891 6.01081C6.24796 5.98649 6.297 5.95 6.34605 5.91351C6.3951 5.87703 6.44414 5.84054 6.49319 5.81622L6.54223 5.76757C6.59128 5.76757 6.64033 5.75541 6.68937 5.74324C6.73842 5.73108 6.78747 5.71892 6.83651 5.71892H7.22888H7.37602C7.52316 5.76757 7.62125 5.81622 7.71935 5.96216C7.86649 6.25405 8.45504 7.56757 8.50409 7.81081C8.55313 7.95676 8.50409 8.15135 8.40599 8.2973C8.35695 8.34595 8.32016 8.40676 8.28338 8.46757C8.24659 8.52838 8.20981 8.58919 8.16076 8.63784C8.01362 8.78378 7.81744 9.02703 7.81744 9.02703C7.76839 9.07568 7.76839 9.17297 7.76839 9.27027C7.78713 9.30744 7.79871 9.3375 7.80859 9.36318C7.82459 9.40473 7.83617 9.4348 7.86649 9.46486L7.91553 9.56216C8.16076 10 8.50409 10.3892 8.84741 10.7297C8.89646 10.7784 8.95777 10.827 9.01907 10.8757C9.08038 10.9243 9.14169 10.973 9.19074 11.0216C9.58311 11.4108 10.0736 11.7027 10.6131 11.9459L10.8583 12.0432C10.9074 12.0919 11.0055 12.1405 11.0545 12.1405H11.1035C11.2016 12.1405 11.2997 12.0919 11.3488 11.9946C12.0354 11.2162 12.0845 11.1676 12.0845 11.1676C12.1826 11.1189 12.2807 11.0703 12.4278 11.0703C12.4523 11.0703 12.4768 11.0824 12.5014 11.0946C12.5259 11.1068 12.5504 11.1189 12.5749 11.1189C13.0654 11.3622 13.8501 11.7027 13.8501 11.7027Z" fill="#E6E5E0"></path>
										</svg>
										<?php esc_html_e( 'WhatsApp', 'stylena' ); ?>
									</a>
								</li>
								<li>
									<a href="<?php echo esc_url( get_theme_mod( 'stylena_telephone_link', '#' ) ); ?>" target="_blank" rel="nofollow" class="header-wa">
										<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
											<g id="call">
												<g id="Icons">
													<g id="Vector">
														<path d="M16.8915 14.1681L13.3915 10.7967C13.3458 10.7529 13.2892 10.7223 13.2276 10.7081C13.166 10.6939 13.1016 10.6967 13.0415 10.7162L11.148 11.3463L8.65244 8.85363L9.29994 6.95611C9.3194 6.89592 9.32218 6.83159 9.30801 6.76994C9.29383 6.7083 9.26323 6.65165 9.21944 6.60601L5.84892 3.10503C5.81651 3.07195 5.77785 3.04564 5.73519 3.02761C5.69254 3.00958 5.64673 3.0002 5.60042 3.00001C5.55436 2.99974 5.5087 3.00857 5.46605 3.02599C5.42341 3.04342 5.38462 3.06909 5.35192 3.10153L3.95541 4.47392C3.3435 5.08926 3 5.92189 3 6.78981C3 7.65773 3.3435 8.49036 3.95541 9.1057L10.8925 16.0446C11.507 16.6565 12.3389 17 13.206 17C14.0731 17 14.9049 16.6565 15.5195 16.0446L16.8985 14.6653C16.9309 14.6325 16.9566 14.5937 16.974 14.5511C16.9914 14.5084 17.0003 14.4628 17 14.4167C16.9993 14.3701 16.9894 14.3241 16.9708 14.2815C16.9522 14.2388 16.9252 14.2002 16.8915 14.1681ZM15.026 15.5685C14.5432 16.0497 13.8893 16.32 13.2077 16.32C12.5261 16.32 11.8723 16.0497 11.3895 15.5685L4.44891 8.59806C3.9695 8.11421 3.70053 7.46054 3.70053 6.77931C3.70053 6.09808 3.9695 5.44441 4.44891 4.96055L5.57592 3.83324L8.55094 6.9246L7.91743 8.82563C7.89843 8.88612 7.8962 8.95064 7.911 9.0123C7.92579 9.07397 7.95705 9.13045 8.00144 9.17572L10.8015 11.9765C10.8463 12.0215 10.9026 12.0534 10.9643 12.0688C11.026 12.0843 11.0907 12.0826 11.1515 12.064L13.0485 11.4304L16.139 14.4062L15.026 15.5685Z" fill="#E6E5E0" />
														<path d="M15.026 15.5685C14.5432 16.0497 13.8893 16.32 13.2077 16.32C12.5261 16.32 11.8723 16.0497 11.3895 15.5685L4.44891 8.59806C3.9695 8.11421 3.70053 7.46054 3.70053 6.77931C3.70053 6.09808 3.9695 5.44441 4.44891 4.96055L5.57592 3.83324L8.55094 6.9246L7.91743 8.82563C7.89843 8.88612 7.8962 8.95064 7.911 9.0123C7.92579 9.07397 7.95705 9.13045 8.00144 9.17572L10.8015 11.9765C10.8463 12.0215 10.9026 12.0534 10.9643 12.0688C11.026 12.0843 11.0907 12.0826 11.1515 12.064L13.0485 11.4304L16.139 14.4062L15.026 15.5685Z" fill="#E6E5E0" />
													</g>
												</g>
											</g>
										</svg>
										<?php echo esc_html( get_theme_mod( 'stylena_telephone_text', '+1 (555) 123-4567' ) ); ?>
									</a>
								</li>
								<li>
									<a href="#header-search-area" role="button" class="search-icon-link" id="search-toggle-btn" aria-label="<?php esc_attr_e( 'Search', 'stylena' ); ?>" aria-expanded="false" aria-controls="header-search-area">
										<img src="<?php echo esc_url( get_theme_file_uri( '/img/search.svg' ) ); ?>" alt="" aria-hidden="true" class="header-search-icon" />
									</a>
								</li>
								<!-- Slide-down Search Area -->
								<div id="header-search-area">
									<?php get_search_form( array( 'stylena' => array( 'variant' => 'header' ) ) ); ?>
								</div>
								<?php if ( get_theme_mod( 'stylena_book_show', true ) ) : ?>
									<li>
										<a href="<?php echo esc_url( get_theme_mod( 'stylena_book_link', '/book' ) ); ?>" <?php echo $stylena_book_target_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- hardcoded literal attributes. ?> class="btn header-btn btn-outline-light mr-3"><?php echo esc_html( get_theme_mod( 'stylena_book_text', 'Book' ) ); ?></a>
									</li>
								<?php endif; ?>
							</ul>
						</nav>
					</div>
				</div>
			</div>
		</div>

		<!-- Bottom Row Navigation -->
		<div class="header-bottom d-none">
			<div class="container-fluid">
				<div class="row">
					<div class="col-12">
						<nav class="bottom-nav" aria-label="<?php esc_attr_e( 'Primary menu', 'stylena' ); ?>">
							<?php if ( has_nav_menu( 'primary' ) ) {
								wp_nav_menu(
									array(
										'theme_location' => 'primary',
										'container'      => false,
										'menu_class'     => 'nav-menu bottom-nav-menu justify-content-center',
										'walker'         => new \Stylena\StylenaWalkerNavMenu(),
										'depth'          => 2,
									)
								);
							} else { ?>
								<ul class="nav-menu bottom-nav-menu justify-content-center">
									<?php $stylena_u = stylena_fallback_page_url( 'services' ); if ( $stylena_u ) :
										?><li class="menu-item menu-item-type-post_type menu-item-object-page nav-item"><a class="nav-link  menu-item menu-item-type-post_type menu-item-object-page" href="<?php echo esc_url( $stylena_u ); ?>"><?php esc_html_e( 'Services', 'stylena' ); ?></a></li><?php endif; ?>
									<?php $stylena_u = stylena_fallback_page_url( 'team' ); if ( $stylena_u ) :
										?><li class="menu-item menu-item-type-post_type menu-item-object-page nav-item"><a class="nav-link  menu-item menu-item-type-post_type menu-item-object-page" href="<?php echo esc_url( $stylena_u ); ?>"><?php esc_html_e( 'Team', 'stylena' ); ?></a></li><?php endif; ?>
									<?php $stylena_u = stylena_fallback_page_url( 'membership' ); if ( $stylena_u ) :
										?><li class="menu-item menu-item-type-post_type menu-item-object-page nav-item"><a class="nav-link  menu-item menu-item-type-post_type menu-item-object-page" href="<?php echo esc_url( $stylena_u ); ?>"><?php esc_html_e( 'Membership', 'stylena' ); ?></a></li><?php endif; ?>
									<?php $stylena_u = stylena_fallback_page_url( 'portfolio' ); if ( $stylena_u ) :
										?><li class="menu-item menu-item-type-post_type menu-item-object-page nav-item"><a class="nav-link  menu-item menu-item-type-post_type menu-item-object-page" href="<?php echo esc_url( $stylena_u ); ?>"><?php esc_html_e( 'Portfolio', 'stylena' ); ?></a></li><?php endif; ?>
								</ul>
							<?php } ?>
						</nav>
					</div>
				</div>
			</div>
		</div>

		<!-- Mobile Menu Toggle and Contact Icons -->
		<div class="mobile-menu d-flex align-items-center d-md-none">
			<button class="navbar-toggler" type="button" id="mobile-nav-toggle" aria-label="<?php esc_attr_e( 'Toggle navigation', 'stylena' ); ?>" aria-expanded="false" aria-controls="mobile-nav-overlay" aria-haspopup="dialog">
				<img src="<?php echo esc_url( get_theme_file_uri( '/img/menu.svg' ) ); ?>" alt="" aria-hidden="true" class="mobile-menu-icon" />
			</button>
			<div class="mobile-inline-icons d-flex align-items-center">
				<a href="<?php echo esc_url( get_theme_mod( 'stylena_whatsapp_link', '#' ) ); ?>" target="_blank" rel="nofollow" class="mobile-inline-icon mobile-inline-icon-white" aria-label="<?php esc_attr_e( 'WhatsApp (opens in a new tab)', 'stylena' ); ?>">
					<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
						<path fill-rule="evenodd" clip-rule="evenodd" d="M16.4005 3.62703C14.733 1.92432 12.4278 1 10.0736 1C5.07085 1 1.04905 5.03784 1.09809 9.95135C1.09809 11.5081 1.53951 13.0162 2.2752 14.3784L1 19L5.75749 17.7838C7.08174 18.5135 8.55313 18.8541 10.0245 18.8541C14.9782 18.8541 19 14.8162 19 9.9027C19 7.51892 18.0681 5.28108 16.4005 3.62703ZM13.8501 11.7027L14.3896 11.9459C14.4877 11.9946 14.5368 12.0432 14.5368 12.0432V12.3838C14.4877 12.627 14.4387 12.8703 14.3406 13.0649C14.2916 13.1622 14.1935 13.2595 14.0954 13.3568C14.0463 13.4054 13.9973 13.4419 13.9482 13.4784C13.8992 13.5149 13.8501 13.5514 13.8011 13.6L13.703 13.6973C13.6479 13.7519 13.5928 13.7759 13.5291 13.8036C13.4793 13.8253 13.4242 13.8492 13.3597 13.8919C13.1635 13.9892 12.8692 14.0865 12.624 14.0865H12.1335C12.0599 14.0622 11.9741 14.05 11.8883 14.0378C11.8025 14.0257 11.7166 14.0135 11.6431 13.9892C10.3188 13.6486 9.14169 13.0162 8.11172 12.1405C8.04763 12.0452 7.96261 11.9706 7.87034 11.8897C7.82137 11.8467 7.77036 11.8019 7.71935 11.7514L7.52316 11.5568C6.73842 10.827 6.14986 10 5.70845 9.07568L5.6594 8.92973C5.51226 8.58919 5.41417 8.2 5.41417 7.81081C5.41417 7.27568 5.61035 6.74054 5.95368 6.3027C6.00273 6.25405 6.03951 6.20541 6.07629 6.15676C6.11308 6.10811 6.14986 6.05946 6.19891 6.01081C6.24796 5.98649 6.297 5.95 6.34605 5.91351C6.3951 5.87703 6.44414 5.84054 6.49319 5.81622L6.54223 5.76757C6.59128 5.76757 6.64033 5.75541 6.68937 5.74324C6.73842 5.73108 6.78747 5.71892 6.83651 5.71892H7.22888H7.37602C7.52316 5.76757 7.62125 5.81622 7.71935 5.96216C7.86649 6.25405 8.45504 7.56757 8.50409 7.81081C8.55313 7.95676 8.50409 8.15135 8.40599 8.2973C8.35695 8.34595 8.32016 8.40676 8.28338 8.46757C8.24659 8.52838 8.20981 8.58919 8.16076 8.63784C8.01362 8.78378 7.81744 9.02703 7.81744 9.02703C7.76839 9.07568 7.76839 9.17297 7.76839 9.27027C7.78713 9.30744 7.79871 9.3375 7.80859 9.36318C7.82459 9.40473 7.83617 9.4348 7.86649 9.46486L7.91553 9.56216C8.16076 10 8.50409 10.3892 8.84741 10.7297C8.89646 10.7784 8.95777 10.827 9.01907 10.8757C9.08038 10.9243 9.14169 10.973 9.19074 11.0216C9.58311 11.4108 10.0736 11.7027 10.6131 11.9459L10.8583 12.0432C10.9074 12.0919 11.0055 12.1405 11.0545 12.1405H11.1035C11.2016 12.1405 11.2997 12.0919 11.3488 11.9946C12.0354 11.2162 12.0845 11.1676 12.0845 11.1676C12.1826 11.1189 12.2807 11.0703 12.4278 11.0703C12.4523 11.0703 12.4768 11.0824 12.5014 11.0946C12.5259 11.1068 12.5504 11.1189 12.5749 11.1189C13.0654 11.3622 13.8501 11.7027 13.8501 11.7027Z" fill="#fff"></path>
					</svg>
				</a>
				<a href="<?php echo esc_url( get_theme_mod( 'stylena_telephone_link', '#' ) ); ?>" class="mobile-inline-icon mobile-inline-icon-white" aria-label="<?php esc_attr_e( 'Call us', 'stylena' ); ?>">
					<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
						<path d="M16.8915 14.1681L13.3915 10.7967C13.3458 10.7529 13.2892 10.7223 13.2276 10.7081C13.166 10.6939 13.1016 10.6967 13.0415 10.7162L11.148 11.3463L8.65244 8.85363L9.29994 6.95611C9.3194 6.89592 9.32218 6.83159 9.30801 6.76994C9.29383 6.7083 9.26323 6.65165 9.21944 6.60601L5.84892 3.10503C5.81651 3.07195 5.77785 3.04564 5.73519 3.02761C5.69254 3.00958 5.64673 3.0002 5.60042 3.00001C5.55436 2.99974 5.5087 3.00857 5.46605 3.02599C5.42341 3.04342 5.38462 3.06909 5.35192 3.10153L3.95541 4.47392C3.3435 5.08926 3 5.92189 3 6.78981C3 7.65773 3.3435 8.49036 3.95541 9.1057L10.8925 16.0446C11.507 16.6565 12.3389 17 13.206 17C14.0731 17 14.9049 16.6565 15.5195 16.0446L16.8985 14.6653C16.9309 14.6325 16.9566 14.5937 16.974 14.5511C16.9914 14.5084 17.0003 14.4628 17 14.4167C16.9993 14.3701 16.9894 14.3241 16.9708 14.2815C16.9522 14.2388 16.9252 14.2002 16.8915 14.1681ZM15.026 15.5685C14.5432 16.0497 13.8893 16.32 13.2077 16.32C12.5261 16.32 11.8723 16.0497 11.3895 15.5685L4.44891 8.59806C3.9695 8.11421 3.70053 7.46054 3.70053 6.77931C3.70053 6.09808 3.9695 5.44441 4.44891 4.96055L5.57592 3.83324L8.55094 6.9246L7.91743 8.82563C7.89843 8.88612 7.8962 8.95064 7.911 9.0123C7.92579 9.07397 7.95705 9.13045 8.00144 9.17572L10.8015 11.9765C10.8463 12.0215 10.9026 12.0534 10.9643 12.0688C11.026 12.0843 11.0907 12.0826 11.1515 12.064L13.0485 11.4304L16.139 14.4062L15.026 15.5685Z" fill="#fff" />
					</svg>
				</a>
			</div>
			<?php if ( get_theme_mod( 'stylena_book_show', true ) ) : ?>
				<a href="<?php echo esc_url( get_theme_mod( 'stylena_book_link', '/book' ) ); ?>" <?php echo $stylena_book_target_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- hardcoded literal attributes. ?> class="btn header-btn btn-outline-light ml-auto mobile-header-book-btn">
					<?php echo esc_html( get_theme_mod( 'stylena_book_text', 'Book' ) ); ?>
				</a>
			<?php endif; ?>
		</div>

		<!-- Mobile Navigation -->
		<div class="mobile-nav-overlay d-md-none" id="mobile-nav-overlay" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Site navigation', 'stylena' ); ?>">
			<div class="mobile-nav-container">
				<div class="mobile-nav-header">
					<!-- Top-left menu items for mobile -->
					<nav class="mobile-top-menu-section" aria-label="<?php esc_attr_e( 'Secondary menu', 'stylena' ); ?>">
						<?php if ( has_nav_menu( 'top-left' ) ) {
							wp_nav_menu(
								array(
									'theme_location' => 'top-left',
									'container'      => false,
									'menu_class'     => 'mobile-nav-menu mobile-top-left-menu',
									'walker'         => new \Stylena\StylenaWalkerNavMenu(),
								)
							);
						} else { ?>
							<ul class="mobile-nav-menu mobile-top-left-menu">
								<?php $stylena_u = stylena_fallback_page_url( 'about-us' ); if ( $stylena_u ) :
									?><li><a href="<?php echo esc_url( $stylena_u ); ?>"><?php esc_html_e( 'About Us', 'stylena' ); ?></a></li><?php endif; ?>
								<?php $stylena_u = stylena_fallback_page_url( 'contact-us' ); if ( $stylena_u ) :
									?><li><a href="<?php echo esc_url( $stylena_u ); ?>"><?php esc_html_e( 'Contact Us', 'stylena' ); ?></a></li><?php endif; ?>
								<?php $stylena_u = stylena_fallback_page_url( 'reviews' ); if ( $stylena_u ) :
									?><li><a href="<?php echo esc_url( $stylena_u ); ?>"><?php esc_html_e( 'Reviews', 'stylena' ); ?></a></li><?php endif; ?>
							</ul>
						<?php } ?>
					</nav>

					<!-- Clear separator between top-left and primary menus -->
					<div class="mobile-menu-separator"></div>

					<!-- Primary menu items for mobile -->
					<nav class="mobile-primary-menu-section" aria-label="<?php esc_attr_e( 'Primary menu', 'stylena' ); ?>">
						<?php
						if ( has_nav_menu( 'primary' ) ) {
							wp_nav_menu(
								array(
									'theme_location' => 'primary',
									'container'      => false,
									'menu_class'     => 'mobile-nav-menu mobile-primary-menu',
									'walker'         => new \Stylena\StylenaWalkerNavMenu(),
								)
							);
						} else {
							?>
							<ul class="mobile-nav-menu mobile-primary-menu">
								<?php $stylena_u = stylena_fallback_page_url( 'services' ); if ( $stylena_u ) :
									?><li><a href="<?php echo esc_url( $stylena_u ); ?>"><?php esc_html_e( 'Services', 'stylena' ); ?></a></li><?php endif; ?>
								<?php $stylena_u = stylena_fallback_page_url( 'team' ); if ( $stylena_u ) :
									?><li><a href="<?php echo esc_url( $stylena_u ); ?>"><?php esc_html_e( 'Team', 'stylena' ); ?></a></li><?php endif; ?>
								<?php $stylena_u = stylena_fallback_page_url( 'membership' ); if ( $stylena_u ) :
									?><li><a href="<?php echo esc_url( $stylena_u ); ?>"><?php esc_html_e( 'Membership', 'stylena' ); ?></a></li><?php endif; ?>
								<?php $stylena_u = stylena_fallback_page_url( 'portfolio' ); if ( $stylena_u ) :
									?><li><a href="<?php echo esc_url( $stylena_u ); ?>"><?php esc_html_e( 'Portfolio', 'stylena' ); ?></a></li><?php endif; ?>
							</ul>
						<?php } ?>
					</nav>
					<button type="button" class="mobile-nav-close" aria-label="<?php esc_attr_e( 'Close navigation', 'stylena' ); ?>">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
			</div>
		</div> <!-- Mobile Search Box (Below Header) -->
		<div class="mobile-search-below-header">
			<div class="container-fluid">
				<?php get_search_form( array( 'stylena' => array( 'variant' => 'mobile' ) ) ); ?>
			</div>
		</div>
	</header>
	<?php
	/**
	 * Fires after the site header markup.
	 */
	do_action( 'stylena_after_header' );
	?>