<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/*
Template Name: Contact Us (Full Width)
Description: Full width page template with no title overlay - used for pages whose content already includes its own hero/title (e.g. the seeded Contact Us page).
*/
get_header();
?>

<main id="main" tabindex="-1" class="site-main fullwidth-main" role="main">
	<?php
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();
			the_content();
		endwhile;
	endif;
	?>
</main>

<?php
get_footer();
?>
