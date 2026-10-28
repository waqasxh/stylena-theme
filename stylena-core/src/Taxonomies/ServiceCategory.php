<?php
/**
 * Service category taxonomy.
 *
 * @package StylenaCore
 */

namespace StylenaCore\Taxonomies;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers the `service_category` taxonomy.
 */
final class ServiceCategory {

	/**
	 * Register unless already registered (e.g. by a legacy theme).
	 *
	 * @return void
	 */
	public static function register() {
		if ( taxonomy_exists( 'service_category' ) ) {
			return;
		}

		register_taxonomy(
			'service_category',
			'service',
			array(
				'labels'             => array(
					'name'                       => __( 'Service Categories', 'stylena-core' ),
					'singular_name'              => __( 'Service Category', 'stylena-core' ),
					'menu_name'                  => __( 'Service Categories', 'stylena-core' ),
					'all_items'                  => __( 'All Categories', 'stylena-core' ),
					'edit_item'                  => __( 'Edit Category', 'stylena-core' ),
					'update_item'                => __( 'Update Category', 'stylena-core' ),
					'add_new_item'               => __( 'Add New Category', 'stylena-core' ),
					'new_item_name'              => __( 'New Category Name', 'stylena-core' ),
					'parent_item'                => __( 'Parent Category', 'stylena-core' ),
					'parent_item_colon'          => __( 'Parent Category:', 'stylena-core' ),
					'search_items'               => __( 'Search Categories', 'stylena-core' ),
					'popular_items'              => __( 'Popular Categories', 'stylena-core' ),
					'separate_items_with_commas' => __( 'Separate categories with commas', 'stylena-core' ),
					'add_or_remove_items'        => __( 'Add or remove categories', 'stylena-core' ),
					'choose_from_most_used'      => __( 'Choose from most used categories', 'stylena-core' ),
					'not_found'                  => __( 'No categories found', 'stylena-core' ),
				),
				'public'             => true,
				'publicly_queryable' => true,
				'hierarchical'       => true,
				'show_ui'            => true,
				'show_admin_column'  => true,
				'show_in_nav_menus'  => true,
				'show_tagcloud'      => true,
				'show_in_rest'       => true,
				'rewrite'            => array(
					'slug'         => 'service-category',
					'with_front'   => false,
					'hierarchical' => true,
				),
				'query_var'          => true,
				'meta_box_cb'        => 'post_categories_meta_box',
			)
		);
	}
}
