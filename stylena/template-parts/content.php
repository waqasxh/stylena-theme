<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Display the post content in "generic" or "standard" format.
 * This will be use in the loop and full page display.
 *
 * @package Stylena
 */

$stylena_design = new \Stylena\StylenaDesign();
?>
<?php
// If this is a single service and it has a Gutenberg featured image (post thumbnail),
// render a full-bleed wrapper that the theme CSS will stretch to the viewport width.
if ( is_singular( 'service' ) && has_post_thumbnail() ) {
	echo '<div class="stretch-featured-image">';
	// the_post_thumbnail will output an <img> element which our CSS targets.
	the_post_thumbnail(
		'full',
		array(
			'loading' => 'lazy',
			'alt'     => the_title_attribute( array( 'echo' => false ) ),
		)
	);
	echo '</div>';
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>

		<?php if ( 'post' === get_post_type() ) { ?>
			<div class="entry-meta">
				<?php $stylena_design->post_on(); ?>
			</div><!-- .entry-meta -->
		<?php } //endif;
		?>
	</header><!-- .entry-header -->

	<?php if ( 'post' === get_post_type() && ! is_singular() ) { ?>
		<div class="entry-thumbnail">
			<a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
				<?php
				if ( has_post_thumbnail() ) {
					the_post_thumbnail(
						'large',
						array(
							'class'   => 'img-fluid',
							'loading' => 'lazy',
							'alt'     => '',
						)
					);
				} else {
					echo '<img class="img-fluid" src="' . esc_url( stylena_placeholder_url( 'blog' ) ) . '" alt="" loading="lazy" />';
				}
				?>
			</a>
		</div>
	<?php } ?>

	<?php if ( is_search() ) { // Only display Excerpts for Search.
		?>
		<div class="entry-summary">
			<?php the_excerpt(); ?>
			<div class="clearfix"></div>
		</div><!-- .entry-summary -->
	<?php } else { ?>
		<div class="entry-content">
			<?php the_content( $stylena_design->continue_reading( true ) ); ?>
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
	<?php } //endif;
	?>

	<footer class="entry-meta">
		<?php if ( 'post' === get_post_type() ) { // Hide category and tag text for pages on Search.
			?>
			<div class="entry-meta-category-tag">
				<?php
				/* translators: used between list items, there is a space after the comma */
				$stylena_categories_list = get_the_category_list( __( ', ', 'stylena' ) );
				if ( ! empty( $stylena_categories_list ) ) {
					?>
					<span class="cat-links">
						<?php $stylena_design->categories_list( $stylena_categories_list ); ?>
					</span>
				<?php } // End if categories
				?>

				<?php
				/* translators: used between list items, there is a space after the comma */
				$stylena_tags_list = get_the_tag_list( '', __( ', ', 'stylena' ) );
				if ( $stylena_tags_list ) {
					?>
					<span class="tags-links">
						<?php $stylena_design->tags_list( $stylena_tags_list ); ?>
					</span>
				<?php } // End if $stylena_tags_list
				?>
			</div><!--.entry-meta-category-tag-->
		<?php } // End if 'post' == get_post_type()
		?>

		<div class="entry-meta-comment-tools">
			<?php if ( ! post_password_required() && ( comments_open() || '0' !== strval( get_comments_number() ) ) ) { ?>
				<span class="comments-link"><?php $stylena_design->comments_link(); ?></span>
			<?php } //endif;
			?>

			<?php $stylena_design->edit_post_link(); ?>
		</div><!--.entry-meta-comment-tools-->
	</footer><!-- .entry-meta -->
</article><!-- #post-## -->
<?php unset( $stylena_design ); ?>