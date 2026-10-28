<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The page template file.<br>
 * This file works as display page content (post type "page") and its comments.
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
					// Inlined content from template-parts/content-page.php.
					$stylena_design = new \Stylena\StylenaDesign();
					?>
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<header class="entry-header">
							<h1 class="entry-title"><?php the_title(); ?></h1>
						</header><!-- .entry-header -->

						<div class="entry-content">
							<?php the_content(); ?>
							<div class="clearfix"></div>
							<?php
							/**
							 * This wp_link_pages option adapt to use bootstrap pagination style.
							 */
							wp_link_pages(
								array(
									'before'    => '<div class="page-links">' . __( 'Pages:', 'stylena' ) . ' <ul class="pagination">',
									'after'     => '</ul></div>',
									'separator' => '',
								)
							);
							?>
						</div><!-- .entry-content -->

						<footer class="entry-meta">
							<?php $stylena_design->edit_post_link(); ?>
						</footer>
					</article><!-- #post-## -->
					<?php unset( $stylena_design );

					echo "\n\n";

					$stylena_design = new \Stylena\StylenaDesign();
					$stylena_design->pagination();
					echo "\n\n";

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
