<?php

/**
 * Stylena utilities.
 *
 * Common helper methods used across the theme.
 *
 * @package Stylena
 */

namespace Stylena;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( '\Stylena\StylenaUtilities' ) ) {
	/**
	 * Utility methods used by the Stylena theme.
	 */
	class StylenaUtilities {

		/**
		 * Return a link found in the current post content, or the post permalink if none found.
		 *
		 * @return string URL to use for linking.
		 */
		public static function get_link_in_content() {
			$content = get_the_content();
			$has_url  = get_url_in_content( $content );

			if ( $has_url ) {
				return $has_url;
			}

			return apply_filters( 'the_permalink', get_permalink() ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Core WordPress hook.
		}

		/**
		 * Calculate the main Bootstrap column size based on active sidebars and their sizes.
		 *
		 * Uses global sidebar size hints and the `stylena_full_column_size` filter.
		 *
		 * @global int $stylena_sidebar_left_size
		 * @global int $stylena_sidebar_right_size
		 * @return int Bootstrap column size for the main content area.
		 */
		public static function get_main_column_size() {
			global $stylena_sidebar_left_size, $stylena_sidebar_right_size;

			if ( ! \is_numeric( $stylena_sidebar_left_size ) ) {
				$stylena_sidebar_left_size = 3;
			}
			if ( ! \is_numeric( $stylena_sidebar_right_size ) ) {
				$stylena_sidebar_right_size = 3;
			}

			$full_column = \apply_filters( 'stylena_full_column_size', 12 );
			if ( ! \is_numeric( $full_column ) ) {
				$full_column = 12;
			}

			if ( \is_active_sidebar( 'sidebar-left' ) && \is_active_sidebar( 'sidebar-right' ) ) {
				return ( $full_column - $stylena_sidebar_left_size - $stylena_sidebar_right_size );
			}

			if ( \is_active_sidebar( 'sidebar-left' ) && ! \is_active_sidebar( 'sidebar-right' ) ) {
				return ( $full_column - $stylena_sidebar_left_size );
			}

			if ( ! \is_active_sidebar( 'sidebar-left' ) && \is_active_sidebar( 'sidebar-right' ) ) {
				return ( $full_column - $stylena_sidebar_right_size );
			}

			return $full_column;
		}

		/**
		 * Determine whether any saved widget blocks include a given block name.
		 *
		 * This helps detect if block-based widgets (Widget Block) are in use.
		 *
		 * @param string $block_name Block name to search for (e.g. 'core/paragraph').
		 * @return bool True if found, false otherwise.
		 */
		public static function has_widget_block( $block_name ) {
			if ( ! \is_string( $block_name ) ) {
				return false;
			}

			$widget_blocks = \get_option( 'widget_block' );
			if ( ( \is_array( $widget_blocks ) || \is_object( $widget_blocks ) ) && ! empty( $widget_blocks ) && \function_exists( 'has_block' ) ) {
				foreach ( $widget_blocks as $widget_block ) {
					if ( isset( $widget_block['content'] ) && ! empty( $widget_block['content'] ) && \has_block( $block_name, $widget_block['content'] ) ) {
						unset( $widget_block, $widget_blocks );
						return true;
					}
				}
			}

			unset( $widget_blocks );
			return false;
		}
	}
}
