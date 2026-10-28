<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The main template file.
 *
 * To override home page (for listing latest post) add home.php into the theme.<br>
 * If front page displays is set to static, the index.php file will be use.<br>
 * If front-page.php exists, it will be override any home page file such as home.php, index.php.<br>
 * To learn more please go to https://developer.wordpress.org/themes/basics/template-hierarchy/ .
 *
 * @package Stylena
 */


// begins template. -------------------------------------------------------------------------.
get_header();
?>
<div class="container page-container">
	<div class="row">
		<?php get_sidebar(); ?>
		<main id="main" tabindex="-1" class="col-md-<?php echo esc_attr( \Stylena\StylenaUtilities::get_main_column_size() ); ?> site-main" role="main">
			<?php
			if ( have_posts() ) {
				while ( have_posts() ) {
					the_post();
					get_template_part( 'template-parts/content', get_post_format() );
				} // endwhile;

				$stylena_design = new \Stylena\StylenaDesign();
				$stylena_design->pagination();
				unset( $stylena_design );
			} else {
				get_template_part( 'template-parts/section', 'no-results' );
			} // endif;
			?>
		</main>
		<?php get_sidebar( 'right' ); ?>
	</div>
</div>
<?php
get_footer();
