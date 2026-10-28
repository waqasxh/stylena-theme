<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The single post.<br>
 * This file works as display full post content page and its comments.
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
				$stylena_design = new \Stylena\StylenaDesign();
				while ( have_posts() ) {
					the_post();
					get_template_part( 'template-parts/content', get_post_format() );
					echo "\n\n";

					$stylena_design->pagination();
					echo "\n\n";

					// display next/previous post. un-comment the code below to display post navigation.
					// @since 1.2.6
					// get_template_part('template-parts/nextprevious-post');.

					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || '0' !== strval( get_comments_number() ) ) {
						comments_template();
					}
					echo "\n\n";
				} // endwhile;


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
