<?php
/**
 * Template Name: Portfolio (Full - Width)
 *
 * Portfolio page: shows the published Portfolio Items (Stylena Core plugin) as a grid with
 * optional category filters and lightbox. Without the plugin the page still renders and
 * shows a neutral notice in place of the grid.
 *
 * @package Stylena
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header();

$stylena_portfolio_core  = stylena_core_active();
$stylena_portfolio_step  = max( 1, min( 50, (int) get_theme_mod( 'stylena_portfolio_posts_count', 12 ) ) );
$stylena_portfolio_items = $stylena_portfolio_core ? stylena_get_portfolio_items() : array();
?>
<main id="main" tabindex="-1" class="fullwidth-main image-top" role="main">
	<div class="container-fluid image-top py-5">
		<?php
		// Title/intro from page content (constrain this part).
		if ( have_posts() ) :
			while ( have_posts() ) :
				the_post();
				$stylena_font_family = stylena_get_font_family( get_theme_mod( 'stylena_heading_font', 'montserrat' ) );

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

		<div class="container">
			<div class="row">
				<div class="col-12">
					<section class="portfolio-section portfolio-page-section" id="portfolio-page">
						<div class="container">
							<div class="portfolio-wrapper portfolio-grid">
								<?php
								if ( ! $stylena_portfolio_core ) {
									stylena_core_unavailable_notice();
								} elseif ( ! empty( $stylena_portfolio_items ) ) {
									stylena_render_portfolio_grid(
										$stylena_portfolio_items,
										array(
											'initial' => $stylena_portfolio_step,
											'filters' => (bool) get_theme_mod( 'stylena_portfolio_show_filters', true ),
										)
									);
								} else {
									stylena_render_portfolio_empty();
								}
								?>
							</div>
						</div>
					</section>
				</div>
			</div>
		</div>
	</div>
</main>

<?php get_footer(); ?>
