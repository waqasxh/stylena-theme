<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The attachment template.
 *
 * Use for display the attachment from full post content. Such as image, video, audio, any media or attachment file.
 *
 * @package Stylena
 */


// begins template. -------------------------------------------------------------------------.
get_header();
?>
<main id="main" tabindex="-1" class="col-12 site-main" role="main">
	<?php
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			// Inlined content from template-parts/content-attachment.php.
			$stylena_design = new \Stylena\StylenaDesign();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-view-attachment' ); ?>>
				<header class="entry-header">
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

					<div class="entry-meta">
						<?php
						printf(
							/* translators: %s: Publication date (markup is added by the theme). */
							esc_html__( 'Published %s', 'stylena' ),
							sprintf(
								'<span class="entry-date"><time class="entry-date" datetime="%1$s">%2$s</time></span>',
								esc_attr( get_the_date( 'c' ) ),
								esc_html( get_the_date() )
							) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from escaped parts.
						);
						$stylena_metadata = wp_get_attachment_metadata();
						if ( is_array( $stylena_metadata ) && array_key_exists( 'width', $stylena_metadata ) && array_key_exists( 'height', $stylena_metadata ) && ! empty( $stylena_metadata['width'] ) && ! empty( $stylena_metadata['height'] ) ) {
							echo ' ';
							printf(
								/* translators: %s: Linked attachment dimensions, e.g. "800 × 600". */
								esc_html__( 'at %s', 'stylena' ),
								sprintf(
									'<a href="%1$s" title="%2$s">%3$s</a>',
									esc_url( wp_get_attachment_url() ),
									esc_attr__( 'Link to attachment file', 'stylena' ),
									sprintf(
										/* translators: 1: Attachment width, 2: Attachment height. */
										esc_html__( '%1$s &times; %2$s', 'stylena' ),
										esc_html( $stylena_metadata['width'] ),
										esc_html( $stylena_metadata['height'] )
									)
								) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from escaped parts.
							);
						}
						echo ' ';
						printf(
							/* translators: %s: Linked title of the parent post. */
							esc_html__( 'in %s', 'stylena' ),
							sprintf(
								'<a href="%1$s" title="%2$s" rel="gallery">%3$s</a>',
								esc_url( get_permalink( $post->post_parent ) ),
								esc_attr(
									sprintf(
										/* translators: %s: Parent post title. */
										__( 'Return to %s', 'stylena' ),
										wp_strip_all_tags( get_the_title( $post->post_parent ) )
									)
								),
								esc_html( get_the_title( $post->post_parent ) )
							) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from escaped parts.
						);
						if (
							! is_array( $stylena_metadata ) ||
							(
								is_array( $stylena_metadata ) &&
								(
									! array_key_exists( 'width', $stylena_metadata ) ||
									! array_key_exists( 'height', $stylena_metadata ) ||
									empty( $stylena_metadata['width'] ) ||
									empty( $stylena_metadata['height'] )
								)
							)
						) {
							echo ' ';
							printf(
								/* translators: %s: Link to the attachment file. */
								esc_html__( '(%s)', 'stylena' ),
								sprintf(
									'<a href="%1$s" title="%2$s">%3$s</a>',
									esc_url( wp_get_attachment_url() ),
									esc_attr__( 'Link to attachment file', 'stylena' ),
									esc_html__( 'attachment file', 'stylena' )
								) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from escaped parts.
							);
						}

						echo ' ';
						$stylena_design->edit_post_link();
						unset( $stylena_metadata );
						?>
					</div><!-- .entry-meta -->

					<div class="row mb-3">
						<div class="nav-previous col-6"><?php previous_image_link( false, '<span class="meta-nav">&larr;</span> ' . esc_html__( 'Previous', 'stylena' ) ); ?></div>
						<div class="nav-next col-6 text-right"><?php next_image_link( false, esc_html__( 'Next', 'stylena' ) . ' <span class="meta-nav">&rarr;</span>' ); ?></div>
					</div><!-- #image-navigation -->
				</header><!-- .entry-header -->

				<div class="entry-content">
					<div class="entry-attachment">
						<div class="attachment">
							<?php $stylena_design->attachment(); ?>
						</div><!-- .attachment -->

						<?php if ( has_excerpt() ) { ?>
							<div class="entry-caption">
								<?php the_excerpt(); ?>
							</div><!-- .entry-caption -->
						<?php } //endif;
						?>
					</div><!-- .entry-attachment -->

					<?php
					the_content();

					/**
					 * This wp_link_pages option adapt to use bootstrap pagination style.
					 *
					 * This wp_link_pages on attachment.php or image.php page will results in page not found.
					 *
					 * @link https://github.com/WordPress/twentysixteen/issues/438 Some people have issue about this in the topic "Remove wp_link_pages() from image.php".
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
			</article><!-- #post-## -->
			<?php unset( $stylena_design );
		} // endwhile;
	} else {
		get_template_part( 'template-parts/section', 'no-results' );
	} // endif;
	?>
</main>
<?php
get_footer();
