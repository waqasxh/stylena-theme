<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The search template - Full width layout with theme styling.
 *
 * @package Stylena
 */

// begins template. -------------------------------------------------------------------------.
get_header();
?>
<div class="container-fluid search-page-container">
	<div class="row">
		<main id="main" tabindex="-1" class="col-12 site-main search-main" role="main">
			<?php
			if ( have_posts() ) {
				?>
				<header class="search-page-header">
					<div class="container">
						<h1 class="search-page-title"><?php
														printf(
															/* translators: %s: The search query string (search value). */
															esc_html__( 'Search Results for: %s', 'stylena' ),
															'<span class="search-query">' . get_search_query() . '</span>'
														);
														?></h1>
						<div class="search-page-content">
							<?php get_search_form(); ?>
						</div>
					</div>
				</header><!-- .search-page-header -->

				<div class="search-results-container">
					<div class="container">
						<div class="search-results-grid">
							<?php
							while ( have_posts() ) {
								the_post();
								?>
								<article id="search-post-<?php the_ID(); ?>" <?php post_class( 'search-result-item' ); ?>>
									<div class="search-result-content">


										<div class="search-result-text<?php echo get_post_type() === 'post' ? ' no-thumbnail' : ''; ?>">
											<header class="search-result-header">
												<h2 class="search-result-title">
													<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
												</h2>
												<div class="search-result-meta">
													<span class="search-result-type"><?php echo esc_html( get_post_type_object( get_post_type() )->labels->singular_name ); ?></span>
													<span class="search-result-date"><?php echo get_the_date(); ?></span>
												</div>
											</header>

											<div class="search-result-excerpt">
												<?php the_excerpt(); ?>
											</div>

											<a href="<?php the_permalink(); ?>" class="search-result-link">Read More</a>
										</div>
									</div>
								</article>
								<?php
							} // endwhile;

							$stylena_design = new \Stylena\StylenaDesign();
							?>
						</div>
						<div class="search-pagination">
							<?php $stylena_design->pagination(); ?>
						</div>
						<?php
						unset( $stylena_design );
						?>
					</div>
				</div>
				<?php
			} else {
				?>
				<div class="search-no-results">
					<div class="container">
						<?php get_template_part( 'template-parts/section', 'no-results' ); ?>
					</div>
				</div>
				<?php
			} // endif;
			?>
		</main>
	</div>
</div>
<?php
get_footer();
