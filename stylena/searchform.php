<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Widget template for display search form in search widget.
 *
 * @package Stylena
 */

$variant = isset( $args['stylena']['variant'] ) ? $args['stylena']['variant'] : '';
if ( 'header' === $variant ) {
	?>
<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="header-search-form">
	<div class="header-search-inner">
		<label for="header-search-input" class="screen-reader-text"><?php esc_html_e( 'Search', 'stylena' ); ?></label>
		<input type="text" id="header-search-input" name="s" class="header-search-input" placeholder="<?php esc_attr_e( 'Search...', 'stylena' ); ?>" autocomplete="off" />
		<button type="submit" class="screen-reader-text"><?php esc_html_e( 'Search', 'stylena' ); ?></button>
	</div>
</form>
	<?php
	return;
}
if ( 'mobile' === $variant ) {
	?>
<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="mobile-search-below-form">
	<label for="mobile-search-input" class="screen-reader-text"><?php esc_html_e( 'Search', 'stylena' ); ?></label>
	<input type="text" id="mobile-search-input" name="s" class="mobile-search-below-input" placeholder="<?php esc_attr_e( 'Search...', 'stylena' ); ?>" />
	<button type="submit" class="screen-reader-text"><?php esc_html_e( 'Search', 'stylena' ); ?></button>
</form>
	<?php
	return;
}

$aria_label = '';
if ( isset( $args['aria_label'] ) && ! empty( $args['aria_label'] ) ) {
	$aria_label = ' aria-label="' . esc_attr( $args['aria_label'] ) . '"';
}
$form_classes = '';
if ( isset( $args['stylena']['form_classes'] ) ) {
	$form_classes = ' ' . $args['stylena']['form_classes'];
}
$search_id   = wp_unique_id( 'search-form-' );
$show_button = true;
if ( isset( $args['stylena']['show_button'] ) && is_bool( $args['stylena']['show_button'] ) ) {
	$show_button = $args['stylena']['show_button'];
}
?>
<form class="search-form form<?php echo esc_attr( $form_classes ); ?>" <?php echo $aria_label; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built with esc_attr() above. ?> role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div class="input-group">
		<label for="<?php echo esc_attr( $search_id ); ?>" class="screen-reader-text"><?php esc_html_e( 'Search for:', 'stylena' ); ?></label>
		<input class="form-control" type="search" id="<?php echo esc_attr( $search_id ); ?>" name="s" value="<?php echo esc_attr( get_search_query( false ) ); ?>" placeholder="<?php esc_attr_e( 'Search &hellip;', 'stylena' ); ?>" title="<?php esc_attr_e( 'Search &hellip;', 'stylena' ); ?>">
		<?php if ( true === $show_button ) { ?>

			<button class="btn btn-outline-secondary" type="submit"><?php esc_html_e( 'Search', 'stylena' ); ?></button>

		<?php } // endif;
		?>
	</div>
</form>
