<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/*
Template Name: Reviews (Full Width)
Description: Full width page template that displays the Google Reviews section (same block used as the homepage teaser).
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

		// Same renderer as the homepage Reviews section (Site Reviews plugin or built-in fallback).
		if ( function_exists( 'stylena_render_reviews' ) ) {
			stylena_render_reviews();
		}
		?>
	</div>
</main>

<?php
get_footer();
?>
