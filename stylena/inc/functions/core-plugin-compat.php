<?php
/**
 * Compatibility layer for the Stylena Core companion plugin.
 *
 * Custom post types, the service taxonomy, the slider shortcode and widgets
 * live in the stylena-core plugin. This file only holds presentation glue.
 *
 * @package Stylena
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether the Stylena Core plugin (or a compatible registration of its content types) is active.
 *
 * @return bool
 */
function stylena_core_active() {
	return defined( 'STYLENA_CORE_VERSION' ) || post_type_exists( 'service' );
}

/**
 * URL of a bundled placeholder image (img/placeholders/{name}-placeholder.svg).
 *
 * Child themes can override the files (get_theme_file_uri) or use the filter.
 *
 * @param string $type Placeholder type: service, portfolio, team, slider, hero, founder, advice, faq, blog.
 * @return string Unescaped URL; escape with esc_url() on output.
 */
function stylena_placeholder_url( $type = 'service' ) {
	$type = sanitize_key( $type );
	if ( '' === $type ) {
		$type = 'service';
	}
	$file = 'hero' === $type ? 'hero-poster.svg' : $type . '-placeholder.svg';
	$rel  = 'img/placeholders/' . $file;
	if ( ! file_exists( get_theme_file_path( $rel ) ) ) {
		$rel = 'img/placeholders/service-placeholder.svg';
	}

	/**
	 * Filters the placeholder image URL.
	 *
	 * @param string $url  Placeholder URL.
	 * @param string $type Placeholder type.
	 */
	return apply_filters( 'stylena_placeholder_url', get_theme_file_uri( $rel ), $type );
}

/**
 * Output a short, escaped notice for sections that need the Stylena Core plugin.
 *
 * Visitors get a neutral message; users who can install plugins also get a pointer.
 *
 * @return void
 */
function stylena_core_unavailable_notice() {
	echo '<div class="container py-5 text-center stylena-core-unavailable">';
	echo '<p>' . esc_html__( 'This section is not available yet.', 'stylena' ) . '</p>';
	if ( current_user_can( 'install_plugins' ) ) {
		echo '<p class="small"><em>' . esc_html__( 'Plugins > Stylena Core required: install and activate it to show this content.', 'stylena' ) . '</em> ';
		echo '<a href="' . esc_url( admin_url( 'plugins.php' ) ) . '">' . esc_html__( 'Open Plugins', 'stylena' ) . '</a></p>';
	}
	echo '</div>';
}

/**
 * Output a friendly empty state (plugin active, no posts yet).
 *
 * @param string $message Visitor-facing message (already translated); defaults to a generic one.
 * @param string $post_type Optional post type slug used for the admin-only "Add new" link.
 * @return void
 */
function stylena_empty_state( $message = '', $post_type = '' ) {
	if ( '' === $message ) {
		$message = __( 'No items yet.', 'stylena' );
	}
	echo '<div class="container py-5 text-center stylena-empty-state">';
	echo '<p>' . esc_html( $message ) . '</p>';
	if ( current_user_can( 'edit_theme_options' ) ) {
		echo '<p class="small">';
		if ( '' !== $post_type && post_type_exists( $post_type ) ) {
			echo '<a href="' . esc_url( admin_url( 'post-new.php?post_type=' . sanitize_key( $post_type ) ) ) . '">' . esc_html__( 'Add new', 'stylena' ) . '</a> &middot; ';
		}
		echo '<a href="' . esc_url( admin_url( 'themes.php?page=one-click-demo-import' ) ) . '">' . esc_html__( 'Import demo content', 'stylena' ) . '</a>';
		echo '</p>';
	}
	echo '</div>';
}

/**
 * Feed the Customizer slider settings into the plugin slider defaults.
 *
 * @param array $args Slider default arguments.
 * @return array
 */
function stylena_core_slider_args_from_mods( $args ) {
	$args = (array) $args;

	$args['height']         = get_theme_mod( 'stylena_slider_height', '400px' );
	$args['autoplay']       = get_theme_mod( 'stylena_slider_autoplay', true );
	$args['autoplay_speed'] = get_theme_mod( 'stylena_slider_autoplay_speed', 4000 );
	$args['stop_duration']  = get_theme_mod( 'stylena_slider_stop_duration', 1000 );
	$args['primary_color']  = get_theme_mod( 'stylena_primary_color', '#c4a574' );

	return $args;
}
add_filter( 'stylena_core_slider_args', 'stylena_core_slider_args_from_mods' );

/**
 * Count rows of the legacy content types (works while the types are unregistered).
 *
 * @return int
 */
function stylena_legacy_content_count() {
	$cached = get_transient( 'stylena_legacy_content_count' );
	if ( false !== $cached ) {
		return (int) $cached;
	}

	global $wpdb;
	$types        = array( 'service', 'team', 'portfolio', 'stylena_faq', 'stylena_slider' );
	$placeholders = implode( ',', array_fill( 0, count( $types ), '%s' ) );
	// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- placeholders are generated, values are bound.
	$count = (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(ID) FROM {$wpdb->posts} WHERE post_type IN ({$placeholders}) AND post_status NOT IN ('auto-draft','trash')", $types ) );

	set_transient( 'stylena_legacy_content_count', $count, 12 * HOUR_IN_SECONDS );

	return $count;
}

/**
 * Handle dismissal of the Stylena Core notice (per user, nonce protected).
 *
 * @return void
 */
function stylena_core_notice_dismiss() {
	if ( ! isset( $_GET['stylena_dismiss_core_notice'], $_GET['_wpnonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'stylena_dismiss_core_notice' ) ) {
		return;
	}
	if ( ! current_user_can( 'install_plugins' ) ) {
		return;
	}
	update_user_meta( get_current_user_id(), 'stylena_core_notice_dismissed', 1 );
}
add_action( 'admin_init', 'stylena_core_notice_dismiss' );

/**
 * Tell admins that existing content needs the Stylena Core plugin.
 *
 * @return void
 */
function stylena_core_missing_notice() {
	if ( ! current_user_can( 'install_plugins' ) || stylena_core_active() ) {
		return;
	}
	if ( get_user_meta( get_current_user_id(), 'stylena_core_notice_dismissed', true ) ) {
		return;
	}
	if ( 0 === stylena_legacy_content_count() ) {
		return;
	}

	$plugins_url = admin_url( 'plugins.php' );
	if ( class_exists( 'TGM_Plugin_Activation' ) ) {
		$plugins_url = admin_url( 'themes.php?page=tgmpa-install-plugins' );
	}
	$dismiss_url = wp_nonce_url( add_query_arg( 'stylena_dismiss_core_notice', '1' ), 'stylena_dismiss_core_notice' );
	?>
	<div class="notice notice-warning is-dismissible">
		<p>
			<?php esc_html_e( 'Your services, team, portfolio, FAQ and slider content is safe, but it is hidden until the Stylena Core plugin is active.', 'stylena' ); ?>
			<a href="<?php echo esc_url( $plugins_url ); ?>"><?php esc_html_e( 'Install or activate Stylena Core', 'stylena' ); ?></a>
			|
			<a href="<?php echo esc_url( $dismiss_url ); ?>"><?php esc_html_e( 'Dismiss', 'stylena' ); ?></a>
		</p>
	</div>
	<?php
}
add_action( 'admin_notices', 'stylena_core_missing_notice' );
