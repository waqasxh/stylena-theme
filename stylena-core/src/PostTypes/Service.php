<?php
/**
 * Service post type.
 *
 * @package StylenaCore
 */

namespace StylenaCore\PostTypes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers the `service` post type.
 */
final class Service {

	/**
	 * Register unless already registered (e.g. by a legacy theme).
	 *
	 * @return void
	 */
	public static function register() {
		if ( post_type_exists( 'service' ) ) {
			return;
		}

		register_post_type(
			'service',
			array(
				'labels'        => array(
					'name'          => __( 'Services', 'stylena-core' ),
					'singular_name' => __( 'Service', 'stylena-core' ),
					'menu_name'     => __( 'Services', 'stylena-core' ),
					'all_items'     => __( 'All Services', 'stylena-core' ),
					'add_new'       => __( 'Add Service', 'stylena-core' ),
					'add_new_item'  => __( 'Add Service', 'stylena-core' ),
					'edit_item'     => __( 'Edit Service', 'stylena-core' ),
					'new_item'      => __( 'New Service', 'stylena-core' ),
					'view_item'     => __( 'View Service', 'stylena-core' ),
					'search_items'  => __( 'Search Services', 'stylena-core' ),
				),
				'public'        => true,
				// No archive: the curated "Services" Page owns the bare /services/ URL.
				'has_archive'   => false,
				'rewrite'       => array( 'slug' => 'services' ),
				'supports'      => array( 'title' ),
				'menu_position' => 26,
				'menu_icon'     => 'dashicons-admin-tools',
			)
		);
	}
}
