<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * File not found or web page not found template file.
 * Full-width professional 404 page inspired by Stylena theme.
 *
 * @package Stylena
 */

// No container wrapper for full-width 404 page.
get_header();
?>

<main id="main" tabindex="-1" class="site-main error-404-main" role="main">
	<!-- 404 Hero Section -->
	<section class="error-404-hero position-relative">
		<div class="error-404-overlay"></div>

		<div class="error-404-content">
			<div class="container-fluid">
				<div class="row justify-content-center text-center">
					<div class="col-lg-8 col-md-10">
						<div class="error-number">404</div>
						<h1 class="error-title"><?php esc_html_e( 'Page Not Found', 'stylena' ); ?></h1>
						<p class="error-subtitle">
							<?php esc_html_e( 'We\'re sorry, but the page you\'re looking for seems to have taken a different path. Our beauty experts have searched everywhere, but this page appears to have vanished like a perfectly applied concealer.', 'stylena' ); ?>
						</p>

						<!-- Action Buttons -->
						<div class="error-actions">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary error-btn">
								<?php esc_html_e( 'Return Home', 'stylena' ); ?>
							</a>

						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
