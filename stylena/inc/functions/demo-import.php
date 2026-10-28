<?php
/**
 * Bundled/recommended plugins (TGMPA) and One Click Demo Import glue.
 *
 * Nothing is written on theme activation; all writes run inside OCDI,
 * which performs its own capability and nonce checks. No remote calls.
 *
 * @package Stylena
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$stylena_tgmpa = get_template_directory() . '/inc/vendor/class-tgm-plugin-activation.php';
if ( is_readable( $stylena_tgmpa ) ) {
	require_once $stylena_tgmpa;
}
unset( $stylena_tgmpa );

/**
 * Plugin list shared by TGMPA and OCDI.
 *
 * The stylena-core version MUST equal the plugin header version
 * (asserted by build-package.ps1).
 *
 * @return array[]
 */
function stylena_get_required_plugins() {
	return array(
		array(
			'slug'               => 'stylena-core',
			'name'               => 'Stylena Core',
			'source'             => get_template_directory() . '/inc/plugins/stylena-core.zip',
			'required'           => true,
			'version'            => '1.0.0',
			'force_activation'   => false,
			'force_deactivation' => false,
		),
		array(
			'slug'     => 'contact-form-7',
			'name'     => 'Contact Form 7',
			'required' => false,
		),
		array(
			'slug'     => 'one-click-demo-import',
			'name'     => 'One Click Demo Import',
			'required' => false,
		),
	);
}

/**
 * Register plugins with TGMPA.
 *
 * @return void
 */
function stylena_register_plugins() {
	if ( ! function_exists( 'tgmpa' ) ) {
		return;
	}

	// Optional extras shown only on the TGMPA screen (not part of the demo import list).
	$recommended = array(
		array(
			'slug'     => 'site-reviews',
			'name'     => 'Site Reviews',
			'required' => false,
		),
		array(
			'slug'     => 'wordpress-seo',
			'name'     => 'Yoast SEO',
			'required' => false,
		),
	);

	tgmpa(
		array_merge( stylena_get_required_plugins(), $recommended ),
		array(
			'id'           => 'stylena',
			'domain'       => 'stylena',
			'menu'         => 'tgmpa-install-plugins',
			'parent_slug'  => 'themes.php',
			'capability'   => 'install_plugins',
			'has_notices'  => true,
			'dismissable'  => true,
			'is_automatic' => false,
		)
	);
}
add_action( 'tgmpa_register', 'stylena_register_plugins' );

/**
 * OCDI: plugins installed before import.
 *
 * @return array[]
 */
function stylena_ocdi_register_plugins() {
	return stylena_get_required_plugins();
}
add_filter( 'ocdi/register_plugins', 'stylena_ocdi_register_plugins' );

/**
 * Demo variants. 'default' owns the content; the other variants are style variants
 * (Customizer palette, fonts, hero text and section toggles) that reuse the default content.
 *
 * @return array[] Folder slug => label.
 */
function stylena_demo_variants() {
	return array(
		'default'       => __( 'Stylena Default', 'stylena' ),
		'light-minimal' => __( 'Stylena Light Minimal', 'stylena' ),
		'warm-rose'     => __( 'Stylena Warm Rose', 'stylena' ),
	);
}

/**
 * Where the demo import files live.
 *
 * The theme download does not contain them (they come as a separate "Stylena demo content"
 * download). Extract that download so that `default/content.xml` ends up at
 * wp-content/uploads/stylena-demo/default/content.xml. A `demo/` folder inside the theme is
 * also honoured (development checkouts).
 *
 * @return array|null Array with 'dir' and 'url' (no trailing slash), or null when not found.
 */
function stylena_demo_location() {
	$candidates = array(
		array(
			'dir' => get_template_directory() . '/demo',
			'url' => get_template_directory_uri() . '/demo',
		),
	);
	$uploads    = wp_get_upload_dir();
	if ( empty( $uploads['error'] ) ) {
		$candidates[] = array(
			'dir' => $uploads['basedir'] . '/stylena-demo',
			'url' => $uploads['baseurl'] . '/stylena-demo',
		);
	}
	foreach ( $candidates as $candidate ) {
		if ( is_readable( $candidate['dir'] . '/default/content.xml' ) ) {
			return $candidate;
		}
	}
	return null;
}

/**
 * Tell the administrator how to get the demo files when they are not installed.
 *
 * @return void
 */
function stylena_demo_missing_notice() {
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( ! $screen || false === strpos( $screen->id, 'one-click-demo-import' ) || stylena_demo_location() ) {
		return;
	}
	printf(
		'<div class="notice notice-warning"><p>%s</p></div>',
		esc_html__( 'Stylena demo files not found. Download the separate "Stylena demo content" package, extract it and upload the stylena-demo folder to wp-content/uploads/ so that wp-content/uploads/stylena-demo/default/content.xml exists. See the documentation (Demo Import) for details.', 'stylena' )
	);
}
add_action( 'admin_notices', 'stylena_demo_missing_notice' );

/**
 * OCDI: demo definitions. Only offered when the local files exist.
 *
 * The default demo imports content, widgets and Customizer settings. A style variant imports
 * only its Customizer settings when the demo content is already on the site (so a second
 * import never duplicates pages, posts or menu items); on a fresh site it also brings in the
 * shared default content and widgets so it works as a stand-alone first import.
 *
 * @return array[]
 */
function stylena_ocdi_import_files() {
	$root = stylena_demo_location();
	if ( ! $root ) {
		return array();
	}
	$base    = $root['dir'] . '/default/';
	$content = $base . 'content.xml';
	if ( ! is_readable( $content ) ) {
		return array();
	}

	$already = (bool) get_option( 'stylena_demo_imported' );
	$demos   = array();
	foreach ( stylena_demo_variants() as $slug => $label ) {
		$dir        = $root['dir'] . '/' . $slug . '/';
		$is_default = 'default' === $slug;
		if ( ! $is_default && ! is_readable( $dir . 'customizer.dat' ) ) {
			continue;
		}

		$demo = array( 'import_file_name' => $label );
		if ( $is_default || ! $already ) {
			$demo['local_import_file'] = $content;
			if ( is_readable( $base . 'widgets.wie' ) ) {
				$demo['local_import_widget_file'] = $base . 'widgets.wie';
			}
		}
		if ( is_readable( $dir . 'customizer.dat' ) ) {
			$demo['local_import_customizer_file'] = $dir . 'customizer.dat';
		}
		if ( is_readable( $dir . 'preview.jpg' ) ) {
			$demo['import_preview_image_url'] = $root['url'] . '/' . $slug . '/preview.jpg';
		}
		$demo['import_notice'] = $is_default
			? __( 'Import on a fresh site. Importing twice creates duplicate content.', 'stylena' )
			: __( 'A style variant: colours, fonts, hero text and section choices. It reuses the default demo content, which is imported only if it is not on this site yet.', 'stylena' );
		$demos[]               = $demo;
	}

	return $demos;
}
add_filter( 'ocdi/import_files', 'stylena_ocdi_import_files' );

/**
 * OCDI: abort when the Stylena Core post types are not registered.
 *
 * @return void
 */
function stylena_ocdi_before_content_import() {
	if ( ! post_type_exists( 'service' ) ) {
		wp_die(
			esc_html__( 'Please install and activate the Stylena Core plugin before importing demo content.', 'stylena' ),
			esc_html__( 'Stylena demo import', 'stylena' ),
			array( 'back_link' => true )
		);
	}
}
add_action( 'ocdi/before_content_import', 'stylena_ocdi_before_content_import' );

/**
 * OCDI: remember which content existed before the import, so the after-import
 * steps only ever touch posts the import created and never a site owner's own
 * posts that happen to share a demo title.
 *
 * @return void
 */
function stylena_ocdi_snapshot_existing_content() {
	delete_option( 'stylena_demo_media_failed' );
	stylena_demo_media_unreachable( false );

	$ids = get_posts(
		array(
			'post_type'        => array( 'post', 'page', 'service', 'team', 'portfolio', 'stylena_slider', 'stylena_faq', 'wpcf7_contact_form' ),
			'post_status'      => 'any',
			'numberposts'      => -1,
			'fields'           => 'ids',
			'suppress_filters' => true,
		)
	);
	update_option( 'stylena_demo_preexisting_ids', array_map( 'intval', $ids ), false );
}
add_action( 'ocdi/before_content_import', 'stylena_ocdi_snapshot_existing_content' );

/**
 * OCDI: warn on the import screen when demo content was already imported.
 *
 * @param string $text Default intro text.
 * @return string
 */
function stylena_ocdi_plugin_intro_text( $text ) {
	$host  = wp_parse_url( stylena_demo_assets_base_url(), PHP_URL_HOST );
	$text .= '<div class="notice notice-info inline"><p>'
		. sprintf(
			/* translators: %s: host name of the demo media server */
			esc_html__( 'External service: the demo import downloads about 30 sample images from %s into your Media Library, which sends your server IP address to that host. The images are AI-generated stand-ins, may become unavailable at any time (the demo then imports without them), and should be replaced with your own photography.', 'stylena' ),
			'<code>' . esc_html( (string) $host ) . '</code>'
		)
		. '</p></div>';

	$failed = get_option( 'stylena_demo_media_failed', array() );
	if ( is_array( $failed ) && $failed ) {
		$text .= '<div class="notice notice-warning inline"><p>'
			. sprintf(
				/* translators: %d: number of demo images that could not be downloaded */
				esc_html( _n( '%d demo image could not be downloaded during the last import (the demo media server may be offline). The demo imported without it and the built-in placeholder is shown instead. You can add your own image, or run the import again later.', '%d demo images could not be downloaded during the last import (the demo media server may be offline). The demo imported without them and the built-in placeholders are shown instead. You can add your own images, or run the import again later.', count( $failed ), 'stylena' ) ),
				count( $failed )
			)
			. '</p></div>';
	}

	if ( get_option( 'stylena_demo_imported' ) ) {
		$text .= '<div class="notice notice-warning inline"><p>'
			. esc_html__( 'Demo content was already imported on this site. Importing again creates duplicate pages, posts and menu items. Use a fresh site for a clean result.', 'stylena' )
			. '</p></div>';
	}

	return $text;
}
add_filter( 'ocdi/plugin_intro_text', 'stylena_ocdi_plugin_intro_text' );

/**
 * Find the first post ID of a type whose title matches exactly.
 *
 * @param string $title     Post title.
 * @param string $post_type Post type.
 * @param int[]  $exclude_ids Post IDs to ignore (content that existed before the import).
 * @return int 0 when not found.
 */
function stylena_demo_find_id_by_title( $title, $post_type, $exclude_ids = array() ) {
	$posts = get_posts(
		array(
			'post_type'        => $post_type,
			'post_status'      => 'any',
			'numberposts'      => -1,
			'orderby'          => 'ID',
			'order'            => 'ASC',
			'suppress_filters' => true,
		)
	);
	foreach ( $posts as $post ) {
		if ( in_array( (int) $post->ID, $exclude_ids, true ) ) {
			continue;
		}
		if ( $post->post_title === $title ) {
			return (int) $post->ID;
		}
	}

	return 0;
}

/**
 * Base URL of the demo media. The demo photographs are AI-generated, so they are
 * hosted on the demo server and are not part of the theme download (marketplace rule).
 *
 * @return string URL with a trailing slash.
 */
function stylena_demo_assets_base_url() {
	$urls = stylena_demo_assets_base_urls();

	return $urls[0];
}

/**
 * All hosts the demo media can be downloaded from, tried in order. The first is the demo
 * server; add a mirror with the stylena_demo_assets_base_urls filter or by extending the
 * default list below.
 *
 * @return string[] HTTPS URLs with a trailing slash (never empty).
 */
function stylena_demo_assets_base_urls() {
	$urls = apply_filters(
		'stylena_demo_assets_base_urls',
		array(
			'https://stylena.zylonex.com/demo-assets/stylena/',
			'https://stylena.zylonex.com/demo-assets-mirror/stylena/',
		)
	);
	$urls = is_array( $urls ) ? array_values( array_filter( array_map( 'strval', $urls ) ) ) : array();
	if ( ! $urls ) {
		$urls = array( 'https://stylena.zylonex.com/demo-assets/stylena/' );
	}

	return array_map( 'trailingslashit', $urls );
}

/**
 * Circuit breaker for the demo media hosts: after three files in a row could not be downloaded
 * from any host, stop trying for the rest of the request (a dead host would otherwise cost
 * 30 seconds per image and can exceed PHP time limits).
 *
 * @param bool|null $failed True records a failed file, false records a success (or resets),
 *                          null only reads the state.
 * @return bool True when downloads should be skipped.
 */
function stylena_demo_media_unreachable( $failed = null ) {
	static $consecutive = 0;
	if ( true === $failed ) {
		++$consecutive;
	} elseif ( false === $failed ) {
		$consecutive = 0;
	}

	return $consecutive >= 3;
}

/**
 * Remember a demo image that could not be downloaded, so the import screen can tell the owner.
 *
 * @param string $file File name.
 * @return void
 */
function stylena_demo_note_failed_media( $file ) {
	$failed = get_option( 'stylena_demo_media_failed', array() );
	$failed = is_array( $failed ) ? $failed : array();
	if ( ! in_array( $file, $failed, true ) ) {
		$failed[] = $file;
		update_option( 'stylena_demo_media_failed', $failed, false );
	}
}

/**
 * Bring one demo image into the media library: from img/demo inside the theme when that
 * folder exists (development checkouts), otherwise downloaded from the demo server.
 * Returns 0 when it is unavailable (offline, 404), so the neutral theme placeholders stay.
 *
 * @param string $file    Bare file name, for example "hero-poster.jpg".
 * @param int    $post_id Post to attach the media item to (0 for none).
 * @return int Attachment ID, 0 on failure.
 */
function stylena_demo_sideload( $file, $post_id = 0 ) {
	$file = sanitize_file_name( basename( (string) $file ) );
	if ( '' === $file || ! preg_match( '/\.(jpe?g|png|webp|mp4)$/i', $file ) ) {
		return 0;
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$local = get_theme_file_path( 'img/demo/' . $file );
	if ( file_exists( $local ) ) {
		$tmp = wp_tempnam( $file );
		if ( ! $tmp || ! copy( $local, $tmp ) ) {
			return 0;
		}
	} else {
		if ( stylena_demo_media_unreachable() ) {
			stylena_demo_note_failed_media( $file );
			return 0;
		}
		$tmp = new WP_Error( 'stylena_demo_no_host', 'No demo media host configured.' );
		foreach ( stylena_demo_assets_base_urls() as $base ) {
			$tmp = download_url( $base . rawurlencode( $file ), 30 );
			if ( ! is_wp_error( $tmp ) ) {
				break;
			}
		}
		if ( is_wp_error( $tmp ) ) {
			stylena_demo_media_unreachable( true );
			stylena_demo_note_failed_media( $file );
			return 0;
		}
		stylena_demo_media_unreachable( false );
	}

	$id = media_handle_sideload(
		array(
			'name'     => $file,
			'tmp_name' => $tmp,
		),
		$post_id
	);
	if ( is_wp_error( $id ) ) {
		wp_delete_file( $tmp );
		return 0;
	}

	return (int) $id;
}

/**
 * Bring a demo image into the media library and attach it to a post.
 * Idempotent: does nothing when the post already has a valid image.
 *
 * @param int    $post_id  Target post.
 * @param string $rel      Image path or file name (only the file name is used).
 * @param string $meta_key Meta key that stores the attachment ID; empty uses the featured image.
 * @return int Attachment ID, 0 on failure or when the file is missing.
 */
function stylena_demo_attach_image( $post_id, $rel, $meta_key = '' ) {
	$existing = $meta_key ? (int) get_post_meta( $post_id, $meta_key, true ) : (int) get_post_thumbnail_id( $post_id );
	if ( $existing && wp_attachment_is_image( $existing ) ) {
		return $existing;
	}
	$id = stylena_demo_sideload( basename( $rel ), $post_id );
	if ( ! $id ) {
		return 0;
	}
	if ( $meta_key ) {
		update_post_meta( $post_id, $meta_key, (int) $id );
	} else {
		set_post_thumbnail( $post_id, (int) $id );
	}

	return (int) $id;
}

/**
 * OCDI: finish the import. Runs inside OCDI (capability and nonce already
 * checked by the plugin). Every step is idempotent: lookups by slug/title,
 * updates instead of inserts.
 *
 * @return void
 */
function stylena_ocdi_after_import() {
	// 1. Assign the imported menus to the theme locations.
	$menu_slugs = array(
		'primary'  => 'primary-menu',
		'top-left' => 'top-left-menu',
	);
	$locations  = get_theme_mod( 'nav_menu_locations', array() );
	$locations  = is_array( $locations ) ? $locations : array();
	foreach ( $menu_slugs as $location => $slug ) {
		$menu = wp_get_nav_menu_object( $slug );
		if ( $menu ) {
			$locations[ $location ] = (int) $menu->term_id;
		}
	}
	set_theme_mod( 'nav_menu_locations', $locations );

	// Content that existed before the import (snapshot taken on ocdi/before_content_import).
	// Every step below ignores it, so a site owner's own pages, forms and posts are never modified.
	$snapshot    = get_option( 'stylena_demo_preexisting_ids', null );
	$preexisting = is_array( $snapshot ) ? array_map( 'intval', $snapshot ) : array();
	$demo_pages  = array(
		'home'       => 'Home',
		'blog'       => 'Blog',
		'contact-us' => 'Contact Us',
		'membership' => 'Membership',
	);
	foreach ( $demo_pages as $slug => $title ) {
		$page_id             = stylena_demo_find_id_by_title( $title, 'page', $preexisting );
		$demo_pages[ $slug ] = $page_id ? get_post( $page_id ) : null;
	}

	// 2. Static front page and posts page.
	$home = $demo_pages['home'];
	$blog = $demo_pages['blog'];
	if ( $home ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', (int) $home->ID );
	}
	if ( $blog ) {
		update_option( 'page_for_posts', (int) $blog->ID );
	}

	// 3. Replace the Contact Form 7 placeholders with the real form IDs.
	if ( post_type_exists( 'wpcf7_contact_form' ) ) {
		$forms = array(
			'contact-us' => array( '__CF7_CONTACT__', 'Contact form' ),
			'membership' => array( '__CF7_MEMBERSHIP__', 'Membership Application' ),
		);
		foreach ( $forms as $page_slug => $form ) {
			$page = $demo_pages[ $page_slug ];
			if ( ! $page || false === strpos( $page->post_content, $form[0] ) ) {
				continue;
			}
			$form_id = stylena_demo_find_id_by_title( $form[1], 'wpcf7_contact_form', $preexisting );
			if ( ! $form_id ) {
				continue;
			}
			wp_update_post(
				wp_slash(
					array(
						'ID'           => (int) $page->ID,
						'post_content' => str_replace( $form[0], (string) $form_id, $page->post_content ),
					)
				)
			);
		}
	}

	// 3b. Demo images inside page content (__THEME_URI__/img/demo/<file>): bring each one into
	// the media library and point the page at it. When an image is unavailable (demo server
	// offline) its <img> tag is removed so the page never shows a broken image.
	foreach ( array( 'contact-us', 'membership' ) as $page_slug ) {
		$page = $demo_pages[ $page_slug ];
		if ( ! $page || false === strpos( $page->post_content, '__THEME_URI__/img/demo/' ) ) {
			continue;
		}
		$content = $page->post_content;
		if ( preg_match_all( '~__THEME_URI__/img/demo/([A-Za-z0-9._-]+)~', $content, $matches ) ) {
			foreach ( array_unique( $matches[1] ) as $file ) {
				$token = '__THEME_URI__/img/demo/' . $file;
				$id    = stylena_demo_sideload( $file );
				$url   = $id ? wp_get_attachment_url( $id ) : '';
				if ( $url ) {
					$content = str_replace( $token, wp_make_link_relative( $url ), $content );
				} else {
					$content = preg_replace( '~<img\b[^>]*' . preg_quote( $token, '~' ) . '[^>]*>\s*~i', '', $content );
				}
			}
		}
		wp_update_post(
			wp_slash(
				array(
					'ID'           => (int) $page->ID,
					'post_content' => $content,
				)
			)
		);
	}

	// 4. Demo images: point the image mods at the AI-generated demo media (hosted on the demo
	// server, see stylena_demo_sideload()). Theme defaults stay neutral placeholders, and a mod
	// that already points at a media-library image is left alone.
	$demo_images = array(
		'stylena_about_founder_image' => 'founder-portrait.jpg',
		'stylena_hero_poster'         => 'hero-poster.jpg',
		'stylena_advice_image'        => 'advice-consultation.jpg',
		'stylena_faq_section_image'   => 'faq-salon-corner.jpg',
	);
	foreach ( $demo_images as $mod => $file ) {
		$current = get_theme_mod( $mod );
		if ( is_string( $current ) && '' !== $current && attachment_url_to_postid( $current ) ) {
			continue;
		}
		$id = stylena_demo_sideload( $file );
		if ( $id ) {
			set_theme_mod( $mod, wp_get_attachment_url( $id ) );
		}
	}

	// 4a. Hero background video (same hosting rule as the images; a missing file leaves the poster only).
	$current_video = get_theme_mod( 'stylena_hero_video' );
	if ( ! ( is_string( $current_video ) && '' !== $current_video && attachment_url_to_postid( $current_video ) ) ) {
		$video_id = stylena_demo_sideload( 'hero-video.mp4' );
		if ( $video_id ) {
			set_theme_mod( 'stylena_hero_video', wp_get_attachment_url( $video_id ) );
		}
	}

	// 4b. Attach the bundled demo images to the imported services, team, slides, portfolio items and posts.
	// Only posts created by this import qualify (see stylena_ocdi_snapshot_existing_content()).
	// Without a snapshot (the hook did not run) nothing is attached rather than guessing by title.
	$attachments = array(
		array(
			'service',
			'_service_image',
			array(
				'Express Session'     => 'service-manicure-pedicure.jpg',
				'Standard Session'    => 'service-hairdressing.jpg',
				'Signature Treatment' => 'service-coloring.jpg',
				'Premium Package'     => 'service-makeup-facial.jpg',
				'Relaxation Session'  => 'service-hair-care.jpg',
				'Renewal Programme'   => 'service-eyebrow-care.jpg',
			),
		),
		array(
			'team',
			'_team_image',
			array(
				'Sample Founder' => 'founder-portrait.jpg',
				'Sample Senior Specialist' => 'team-hair-stylist.jpg',
				'Sample Specialist' => 'team-esthetician.jpg',
				'Sample Coordinator' => 'team-makeup-artist.jpg',
			),
		),
		array(
			'portfolio',
			'_portfolio_image',
			array(
				'Featured Work 01' => 'portfolio-balayage.jpg',
				'Featured Work 02' => 'portfolio-updo.jpg',
				'Featured Work 03' => 'portfolio-bridal.jpg',
				'Featured Work 04' => 'portfolio-manicure.jpg',
				'Featured Work 05' => 'portfolio-facial.jpg',
				'Featured Work 06' => 'portfolio-barber.jpg',
			),
		),
		array(
			'post',
			'',
			array(
				'Welcome to Our New Website'              => 'blog-shelf.jpg',
				'What to Expect on Your First Visit'      => 'blog-massage.jpg',
				'Five Simple Tips for Staying Consistent' => 'blog-skincare.jpg',
			),
		),
		array(
			'stylena_slider',
			'',
			array(
				'Welcome'           => 'slider-reception.jpg',
				'Our Services'      => 'slider-hair-styling.jpg',
				'Meet the Team'     => 'slider-nail-studio.jpg',
				'Relax and Restore' => 'slider-spa-room.jpg',
				'Makeup and Beauty' => 'slider-makeup-studio.jpg',
				'Grooming Corner'   => 'slider-grooming-corner.jpg',
			),
		),
	);
	foreach ( is_array( $snapshot ) ? $attachments : array() as $set ) {
		foreach ( $set[2] as $title => $file ) {
			$post_id = stylena_demo_find_id_by_title( $title, $set[0], $preexisting );
			if ( $post_id ) {
				stylena_demo_attach_image( $post_id, 'img/demo/' . $file, $set[1] );
			}
		}
	}
	// Give the imported portfolio items generic categories so the filter buttons work out of the box.
	if ( is_array( $snapshot ) && taxonomy_exists( 'portfolio_category' ) ) {
		$portfolio_terms = array(
			'Featured Work 01' => 'Hair',
			'Featured Work 02' => 'Hair',
			'Featured Work 03' => 'Bridal',
			'Featured Work 04' => 'Nails',
			'Featured Work 05' => 'Skin',
			'Featured Work 06' => 'Hair',
		);
		foreach ( $portfolio_terms as $title => $term_name ) {
			$post_id = stylena_demo_find_id_by_title( $title, 'portfolio', $preexisting );
			if ( $post_id ) {
				wp_set_object_terms( $post_id, $term_name, 'portfolio_category' );
			}
		}
	}
	delete_option( 'stylena_demo_preexisting_ids' );

	// 5. Remember that the demo was imported (theme version).
	$theme = wp_get_theme( get_template() );
	update_option( 'stylena_demo_imported', (string) $theme->get( 'Version' ) );

	// 6. Rewrite rules once, after the post types and pages exist.
	flush_rewrite_rules();
}
add_action( 'ocdi/after_import', 'stylena_ocdi_after_import' );

add_filter( 'ocdi/disable_pt_branding', '__return_true' );
