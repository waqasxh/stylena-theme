<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The archive template.
 *
 * Use for display author archive, category, custom post archive, custom taxonomy archive, tag, date archive.<br>
 * These archive can override by each archive file name such as category will be override by category.php.<br>
 * To learn more, please read on this link. https://developer.wordpress.org/themes/basics/template-hierarchy/
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
			<?php if ( have_posts() ) { ?>
				<header class="page-header">
					<?php
					the_archive_title( '<h1 class="page-title">', '</h1>' );
					the_archive_description( '<div class="taxonomy-description">', '</div>' );
					?>
				</header><!-- .page-header -->

				<?php
				// Start the Loop.
				while ( have_posts() ) {
					the_post();
					get_template_part( 'template-parts/content', get_post_format() );
				} //endwhile;

				$stylena_design = new \Stylena\StylenaDesign();
				$stylena_design->pagination();
				unset( $stylena_design );
			} else {
				get_template_part( 'template-parts/section', 'no-results' );
			} //endif;
			?>
		</main>
		<?php get_sidebar( 'right' ); ?>
	</div>
</div>
<?php
get_footer();
