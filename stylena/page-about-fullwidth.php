<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/*
Template Name: About Us (Full Width)
Description: Full width page template showing the founder/about story, matching the homepage About section.
*/
get_header();
?>

<main id="main" tabindex="-1" class="fullwidth-main image-top" role="main">
	<div class="container-fluid image-top py-5">
		<?php
		// Title/intro from page content (constrain this part).
		if ( have_posts() ) :
			while ( have_posts() ) :
				the_post();
				// Get heading font from customizer.
				if ( ! function_exists( 'stylena_get_font_family' ) ) {
					require_once get_template_directory() . '/inc/functions/theme-customizer.php';
				}
				$stylena_heading_font = get_theme_mod( 'stylena_heading_font', 'montserrat' );
				$stylena_font_family  = stylena_get_font_family( $stylena_heading_font );

				echo '<div class="position-relative">';
				echo '<div class="container page-header mb-4">';
				the_content();
				echo '</div>';
				// Title positioned over the image on z-axis.
				stylena_render_page_title( get_post_field( 'post_content' ), $stylena_font_family );
				echo '</div>';
			endwhile;
		endif;
		?>

		<!-- About Section (same content/markup as the homepage About teaser) -->
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
	</div>
</main>

<?php
get_footer();
?>
