<?php
/**
 * Portfolio category taxonomy.
 *
 * @package StylenaCore
 */

namespace StylenaCore\Taxonomies;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers the `portfolio_category` taxonomy (used for front-end filters).
 */
final class PortfolioCategory {

	/**
	 * Register unless already registered.
	 *
	 * @return void
	 */
	public static function register() {
		if ( taxonomy_exists( 'portfolio_category' ) ) {
			return;
		}

		register_taxonomy(
			'portfolio_category',
			'portfolio',
			array(
				'labels'             => array(
					'name'          => __( 'Portfolio Categories', 'stylena-core' ),
					'singular_name' => __( 'Portfolio Category', 'stylena-core' ),
					'menu_name'     => __( 'Categories', 'stylena-core' ),
					'all_items'     => __( 'All Categories', 'stylena-core' ),
					'edit_item'     => __( 'Edit Category', 'stylena-core' ),
					'update_item'   => __( 'Update Category', 'stylena-core' ),
					'add_new_item'  => __( 'Add New Category', 'stylena-core' ),
					'new_item_name' => __( 'New Category Name', 'stylena-core' ),
					'parent_item'   => __( 'Parent Category', 'stylena-core' ),
					'search_items'  => __( 'Search Categories', 'stylena-core' ),
					'not_found'     => __( 'No categories found', 'stylena-core' ),
				),
				'public'             => false,
				'publicly_queryable' => false,
				'hierarchical'       => true,
				'show_ui'            => true,
				'show_admin_column'  => true,
				'show_in_nav_menus'  => false,
				'show_tagcloud'      => false,
				'show_in_rest'       => true,
				'rewrite'            => false,
				'query_var'          => false,
			)
		);
	}
}
