<?php
/**
 * Portfolio post type.
 *
 * @package StylenaCore
 */

namespace StylenaCore\PostTypes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers the `portfolio` post type.
 */
final class Portfolio {

	/**
	 * Register unless already registered (e.g. by a legacy theme).
	 *
	 * @return void
	 */
	public static function register() {
		if ( post_type_exists( 'portfolio' ) ) {
			return;
		}

		register_post_type(
			'portfolio',
			array(
				'labels'             => array(
					'name'          => __( 'Portfolio Items', 'stylena-core' ),
					'singular_name' => __( 'Portfolio Item', 'stylena-core' ),
					'menu_name'     => __( 'Portfolio Items', 'stylena-core' ),
					'all_items'     => __( 'All Portfolio Items', 'stylena-core' ),
					'add_new'       => __( 'Add Portfolio Item', 'stylena-core' ),
					'add_new_item'  => __( 'Add Portfolio Item', 'stylena-core' ),
					'edit_item'     => __( 'Edit Portfolio Item', 'stylena-core' ),
					'new_item'      => __( 'New Portfolio Item', 'stylena-core' ),
					'view_item'     => __( 'View Portfolio Item', 'stylena-core' ),
					'search_items'  => __( 'Search Portfolio Items', 'stylena-core' ),
				),
				'public'             => false,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'has_archive'        => false,
				'publicly_queryable' => false,
				'rewrite'            => false,
				'supports'           => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
				'menu_position'      => 28,
				'menu_icon'          => 'dashicons-portfolio',
			)
		);
	}
}
