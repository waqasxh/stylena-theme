<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Display no results from condition if not have posts.
 *
 * @package Stylena
 */
?>
<section class="no-results not-found">
	<header class="page-header">
		<h1 class="page-title"><?php esc_html_e( 'Nothing Found', 'stylena' ); ?></h1>
	</header><!-- .page-header -->

	<div class="page-content row-with-vspace">
		<?php if ( is_home() && current_user_can( 'publish_posts' ) ) { ?>
			<p><?php
				/* translators: %1$s: URL to add new post. */
				printf( wp_kses_post( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'stylena' ) ), esc_url( admin_url( 'post-new.php' ) ) );
			?></p>
		<?php } elseif ( is_search() ) { ?>
			<?php get_search_form(); ?>
			<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'stylena' ); ?></p>
		<?php } else { ?>
			<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'stylena' ); ?></p>
			<?php get_search_form(); ?>
		<?php } //endif;
		?>
	</div><!-- .page-content -->
</section><!-- .no-results -->
