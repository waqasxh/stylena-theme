<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Functions file.
 *
 * To getting start design the theme, please begins by reading on this link. https://codex.wordpress.org/Theme_Development
 * You can make this theme as your parent theme (design new by modify this theme and make it yours).
 * But I recommend that you use this theme as parent and create your new child theme.
 *
 * Learn more about template hierarchy, please read on this link. https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Stylena
 */


// Required WordPress variable.
if ( ! isset( $content_width ) ) {
	$content_width = 1140; // this will be override again in inc/classes/Stylena.php `detect_content_width()` method.
}


// Configurations ----------------------------------------------------------------------------
// Left sidebar column size. Bootstrap have 12 columns this sidebar column size must not greater than 12.
if ( ! isset( $stylena_sidebar_left_size ) ) {
	$stylena_sidebar_left_size = apply_filters( 'stylena_column_left', 3 );
}
// Right sidebar column size.
if ( ! isset( $stylena_sidebar_right_size ) ) {
	$stylena_sidebar_right_size = apply_filters( 'stylena_column_right', 3 );
}
// Once you specified left and right column size, while widget was activated in all or some sidebar the main column size will be calculate automatically from these size and widgets activated.
// For example: you use only left sidebar (widgets activated) and left sidebar size is 4, the main column size will be 12 - 4 = 8.
//
// Title separator. Please note that this value maybe able overriden by other plugins.
if ( ! isset( $stylena_title_separator ) ) {
	$stylena_title_separator = '|';
}


// Require, include files ---------------------------------------------------------------------.
require get_template_directory() . '/inc/classes/Autoload.php';
require get_template_directory() . '/inc/functions/include-functions.php';
require get_template_directory() . '/inc/functions/extensibility.php';
require get_template_directory() . '/inc/functions/integrations.php';
require get_template_directory() . '/inc/functions/stylena-functions.php';
require get_template_directory() . '/inc/functions/theme-customizer.php';
require get_template_directory() . '/inc/functions/core-plugin-compat.php';
require get_template_directory() . '/inc/functions/portfolio.php';
require get_template_directory() . '/inc/functions/page-title.php';
if ( is_admin() && is_readable( get_template_directory() . '/inc/functions/demo-import.php' ) ) {
	require get_template_directory() . '/inc/functions/demo-import.php';
}
// Live search functionality (production version).
require get_template_directory() . '/ajax-search.php';


// Setup auto load for load the class files without manually include file by file.
$stylena_autoload = new \Stylena\Autoload();
$stylena_autoload->register();
$stylena_autoload->add_namespace( 'Stylena', get_template_directory() . '/inc/classes' );
unset( $stylena_autoload );


// Call to actions/filters of the theme to enable features, register sidebars, enqueue scripts and styles.
$stylena_main = new \Stylena\Stylena();
$stylena_main->add_actions_filters();
unset( $stylena_main );

// Call to actions/filters of theme hook to hook into WordPress and make changes to the theme.
$stylena_hooks = new \Stylena\Hooks\StylenaHooks();
$stylena_hooks->add_actions_filters();
unset( $stylena_hooks );

// Call to actions/filters of theme hook to hook into WordPress widgets.
$stylena_widget_hooks = new \Stylena\Hooks\WidgetHooks();
$stylena_widget_hooks->add_actions_filters();
unset( $stylena_widget_hooks );

// Call to performance optimization actions and filters.
$stylena_performance = new \Stylena\StylenaPerformance();
$stylena_performance->add_actions_filters();
unset( $stylena_performance );

// Ensure menu links that open in a new tab include safe rel attributes.
if ( ! function_exists( 'stylena_safe_menu_target_rel' ) ) {
	function stylena_safe_menu_target_rel( $atts, $item, $args, $depth ) {
		if ( isset( $atts['target'] ) && '_blank' === $atts['target'] ) {
			$rel         = isset( $atts['rel'] ) ? $atts['rel'] : '';
			$rels        = preg_split( '/\s+/', $rel, -1, PREG_SPLIT_NO_EMPTY );
			$rels        = array_filter( $rels );
			$rels        = array_unique( array_merge( $rels, array( 'noopener', 'noreferrer' ) ) );
			$atts['rel'] = implode( ' ', $rels );
		}
		return $atts;
	}
	add_filter( 'nav_menu_link_attributes', 'stylena_safe_menu_target_rel', 10, 4 );
}

// Modify search results to show 12 posts per page.
if ( ! function_exists( 'stylena_search_posts_per_page' ) ) {
	function stylena_search_posts_per_page( $query ) {
		if ( ! is_admin() && $query->is_main_query() && is_search() ) {
			$query->set( 'posts_per_page', 12 );
		}
	}
	add_action( 'pre_get_posts', 'stylena_search_posts_per_page' );
}
