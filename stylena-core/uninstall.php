<?php
/**
 * Uninstall routine for Stylena Core.
 *
 * Deletes only the plugin's own bookkeeping options. Content (posts, terms,
 * post meta, term meta) is NEVER deleted so that no site data is lost.
 *
 * @package StylenaCore
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'stylena_core_version' );
delete_option( 'stylena_core_flush_rewrite' );
