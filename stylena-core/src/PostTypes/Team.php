<?php
/**
 * Team post type.
 *
 * @package StylenaCore
 */

namespace StylenaCore\PostTypes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers the `team` post type.
 */
final class Team {

	/**
	 * Register unless already registered (e.g. by a legacy theme).
	 *
	 * @return void
	 */
	public static function register() {
		if ( post_type_exists( 'team' ) ) {
			return;
		}

		register_post_type(
			'team',
			array(
				'labels'             => array(
					'name'          => __( 'Team Members', 'stylena-core' ),
					'singular_name' => __( 'Team Member', 'stylena-core' ),
					'menu_name'     => __( 'Team Members', 'stylena-core' ),
					'all_items'     => __( 'All Team Members', 'stylena-core' ),
					'add_new'       => __( 'Add Team Member', 'stylena-core' ),
					'add_new_item'  => __( 'Add Team Member', 'stylena-core' ),
					'edit_item'     => __( 'Edit Team Member', 'stylena-core' ),
					'new_item'      => __( 'New Team Member', 'stylena-core' ),
					'view_item'     => __( 'View Team Member', 'stylena-core' ),
					'search_items'  => __( 'Search Team Members', 'stylena-core' ),
				),
				'public'             => false,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'has_archive'        => false,
				'publicly_queryable' => false,
				'rewrite'            => false,
				'supports'           => array( 'title' ),
				'menu_position'      => 27,
				'menu_icon'          => 'dashicons-groups',
			)
		);
	}
}
