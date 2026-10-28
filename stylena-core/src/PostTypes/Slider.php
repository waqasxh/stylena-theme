<?php
/**
 * Image slider post type.
 *
 * @package StylenaCore
 */

namespace StylenaCore\PostTypes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers the `stylena_slider` post type.
 */
final class Slider {

	/**
	 * Register unless already registered (e.g. by a legacy theme, which does so at init priority 0).
	 *
	 * @return void
	 */
	public static function register() {
		if ( post_type_exists( 'stylena_slider' ) ) {
			return;
		}

		$labels = array(
			'name'                  => _x( 'Image Sliders', 'Post Type General Name', 'stylena-core' ),
			'singular_name'         => _x( 'Slider Item', 'Post Type Singular Name', 'stylena-core' ),
			'menu_name'             => __( 'Image Sliders', 'stylena-core' ),
			'name_admin_bar'        => __( 'Slider Item', 'stylena-core' ),
			'archives'              => __( 'Slider Archives', 'stylena-core' ),
			'attributes'            => __( 'Slider Attributes', 'stylena-core' ),
			'parent_item_colon'     => __( 'Parent Slider:', 'stylena-core' ),
			'all_items'             => __( 'All Image Sliders', 'stylena-core' ),
			'add_new_item'          => __( 'Add Image Slider', 'stylena-core' ),
			'add_new'               => __( 'Add New', 'stylena-core' ),
			'new_item'              => __( 'New Slider Item', 'stylena-core' ),
			'edit_item'             => __( 'Edit Slider Item', 'stylena-core' ),
			'update_item'           => __( 'Update Slider Item', 'stylena-core' ),
			'view_item'             => __( 'View Slider Item', 'stylena-core' ),
			'view_items'            => __( 'View Slider Items', 'stylena-core' ),
			'search_items'          => __( 'Search Slider Items', 'stylena-core' ),
			'not_found'             => __( 'Not found', 'stylena-core' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'stylena-core' ),
			'featured_image'        => __( 'Slider Image', 'stylena-core' ),
			'set_featured_image'    => __( 'Set slider image', 'stylena-core' ),
			'remove_featured_image' => __( 'Remove slider image', 'stylena-core' ),
			'use_featured_image'    => __( 'Use as slider image', 'stylena-core' ),
			'insert_into_item'      => __( 'Insert into slider item', 'stylena-core' ),
			'uploaded_to_this_item' => __( 'Uploaded to this slider item', 'stylena-core' ),
			'items_list'            => __( 'Slider items list', 'stylena-core' ),
			'items_list_navigation' => __( 'Slider items list navigation', 'stylena-core' ),
			'filter_items_list'     => __( 'Filter slider items list', 'stylena-core' ),
		);

		register_post_type(
			'stylena_slider',
			array(
				'label'               => __( 'Slider Item', 'stylena-core' ),
				'description'         => __( 'Image Slider Items', 'stylena-core' ),
				'labels'              => $labels,
				'supports'            => array( 'title', 'thumbnail', 'page-attributes' ),
				'hierarchical'        => false,
				'public'              => false,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'menu_position'       => 28,
				'menu_icon'           => 'dashicons-images-alt2',
				'show_in_admin_bar'   => true,
				'show_in_nav_menus'   => false,
				'can_export'          => true,
				'has_archive'         => false,
				'exclude_from_search' => true,
				'publicly_queryable'  => false,
				'capability_type'     => 'post',
				'show_in_rest'        => true,
			)
		);
	}
}
