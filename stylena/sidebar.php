<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The left sidebar.
 *
 * @package Stylena
 */


global $stylena_sidebar_left_size;
if ( empty( $stylena_sidebar_left_size ) || ! is_numeric( $stylena_sidebar_left_size ) ) {
	$stylena_sidebar_left_size = 3;
}

if ( is_active_sidebar( 'sidebar-left' ) ) {
	?>
	<div id="sidebar-left" class="col-md-<?php echo esc_attr( $stylena_sidebar_left_size ); ?>">
		<?php do_action( 'before_sidebar' ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Public legacy action kept for backwards compatibility. ?>
		<?php dynamic_sidebar( 'sidebar-left' ); ?>
	</div>
	<?php
}
