<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/*
Template Name: Full Width
*/
get_header();
?>

<main id="main" tabindex="-1" class="site-main fullwidth-main" role="main">
	<?php
	// Load page-specific content.
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();
			// Get heading font from customizer.
			if ( ! function_exists( 'stylena_get_font_family' ) ) {
				require_once get_template_directory() . '/inc/functions/theme-customizer.php';
			}
			$stylena_heading_font = get_theme_mod( 'stylena_heading_font', 'montserrat' );
			$stylena_font_family  = stylena_get_font_family( $stylena_heading_font );

			echo '<div class="container page-header mb-4 fullwidth-custom">';
			stylena_render_page_title( get_post_field( 'post_content' ), $stylena_font_family, false );
			the_content();
			echo '</div>';
		endwhile;
	endif;
	?>
</main>

<?php
get_footer();
?>