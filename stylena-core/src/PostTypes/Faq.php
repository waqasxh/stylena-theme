<?php
/**
 * FAQ post type.
 *
 * @package StylenaCore
 */

namespace StylenaCore\PostTypes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers the `stylena_faq` post type.
 */
final class Faq {

	/**
	 * Register unless already registered (e.g. by a legacy theme).
	 *
	 * @return void
	 */
	public static function register() {
		if ( post_type_exists( 'stylena_faq' ) ) {
			return;
		}

		$labels = array(
			'name'                  => _x( 'FAQs', 'Post type general name', 'stylena-core' ),
			'singular_name'         => _x( 'FAQ', 'Post type singular name', 'stylena-core' ),
			'menu_name'             => _x( 'FAQs', 'Admin Menu text', 'stylena-core' ),
			'name_admin_bar'        => _x( 'FAQ', 'Add New on Toolbar', 'stylena-core' ),
			'add_new'               => __( 'Add New', 'stylena-core' ),
			'add_new_item'          => __( 'Add New FAQ', 'stylena-core' ),
			'new_item'              => __( 'New FAQ', 'stylena-core' ),
			'edit_item'             => __( 'Edit FAQ', 'stylena-core' ),
			'view_item'             => __( 'View FAQ', 'stylena-core' ),
			'all_items'             => __( 'All FAQs', 'stylena-core' ),
			'search_items'          => __( 'Search FAQs', 'stylena-core' ),
			'parent_item_colon'     => __( 'Parent FAQs:', 'stylena-core' ),
			'not_found'             => __( 'No FAQs found.', 'stylena-core' ),
			'not_found_in_trash'    => __( 'No FAQs found in Trash.', 'stylena-core' ),
			'featured_image'        => _x( 'FAQ Featured Image', 'Overrides the "Featured Image" phrase', 'stylena-core' ),
			'set_featured_image'    => _x( 'Set featured image', 'Overrides the "Set featured image" phrase', 'stylena-core' ),
			'remove_featured_image' => _x( 'Remove featured image', 'Overrides the "Remove featured image" phrase', 'stylena-core' ),
			'use_featured_image'    => _x( 'Use as featured image', 'Overrides the "Use as featured image" phrase', 'stylena-core' ),
			'archives'              => _x( 'FAQ archives', 'The post type archive label', 'stylena-core' ),
			'insert_into_item'      => _x( 'Insert into FAQ', 'Overrides the "Insert into post" phrase', 'stylena-core' ),
			'uploaded_to_this_item' => _x( 'Uploaded to this FAQ', 'Overrides the "Uploaded to this post" phrase', 'stylena-core' ),
			'filter_items_list'     => _x( 'Filter FAQs list', 'Screen reader text for the filter links', 'stylena-core' ),
			'items_list_navigation' => _x( 'FAQs list navigation', 'Screen reader text for the pagination', 'stylena-core' ),
			'items_list'            => _x( 'FAQs list', 'Screen reader text for the items list', 'stylena-core' ),
		);

		register_post_type(
			'stylena_faq',
			array(
				'labels'             => $labels,
				'public'             => false,
				'publicly_queryable' => false,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'query_var'          => true,
				'rewrite'            => array( 'slug' => 'faq' ),
				'capability_type'    => 'post',
				'has_archive'        => false,
				'hierarchical'       => false,
				'menu_position'      => 29,
				'menu_icon'          => 'dashicons-editor-help',
				'supports'           => array( 'title', 'editor', 'page-attributes' ),
				'show_in_rest'       => true,
			)
		);
	}
}
