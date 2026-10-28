<?php
/**
 * Portfolio helpers: data layer, grid renderer, Customizer settings, lightbox assets.
 *
 * Source is the `portfolio` post type registered by the Stylena Core plugin. Everything here
 * degrades to an empty result when the plugin is inactive.
 *
 * @package Stylena
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the portfolio image size.
 *
 * @return void
 */
function stylena_portfolio_image_sizes() {
	add_image_size( 'stylena-portfolio', 800, 600, true );
}
add_action( 'after_setup_theme', 'stylena_portfolio_image_sizes' );

/**
 * Clamp the number of portfolio items shown first on the Portfolio page.
 *
 * @param mixed $value Raw value.
 * @return int 1-50.
 */
function stylena_sanitize_portfolio_posts_count( $value ) {
	return max( 1, min( 50, absint( $value ) ) );
}

/**
 * Sanitize a bounded integer setting for the portfolio home count.
 *
 * @param mixed $value Raw value.
 * @return int 1-12.
 */
function stylena_sanitize_home_portfolio_count( $value ) {
	return max( 1, min( 12, absint( $value ) ) );
}

/**
 * Attachment ID used as the image of a portfolio item (featured image, else legacy meta).
 *
 * @param int $post_id Portfolio post ID.
 * @return int 0 when none.
 */
function stylena_portfolio_image_id( $post_id ) {
	$id = (int) get_post_thumbnail_id( $post_id );
	if ( $id <= 0 ) {
		$id = absint( get_post_meta( $post_id, '_portfolio_image', true ) );
	}
	return ( $id > 0 && wp_attachment_is_image( $id ) ) ? $id : 0;
}

/**
 * Fetch published portfolio items as normalised cards.
 *
 * Ordering: `_portfolio_sort_order` ascending, then newest first. Items without a sort value are
 * kept (placed after items that have one).
 *
 * @param array $args {
 *     Optional.
 *
 *     @type int    $limit    Max items, 0 = all (capped at 200).
 *     @type string $category portfolio_category slug to restrict to.
 * }
 * @return array[] Cards: id, title, caption, image_id, image_url, link, categories (slug => name).
 */
function stylena_get_portfolio_items( $args = array() ) {
	if ( ! post_type_exists( 'portfolio' ) ) {
		return array();
	}

	$args  = wp_parse_args(
		$args,
		array(
			'limit'    => 0,
			'category' => '',
		)
	);
	$limit = absint( $args['limit'] );

	$query_args = array(
		'post_type'              => 'portfolio',
		'post_status'            => 'publish',
		'posts_per_page'         => 200, // phpcs:ignore WordPress.WP.PostsPerPage.posts_per_page_posts_per_page -- bounded cap; sorted in PHP.
		'no_found_rows'          => true,
		'update_post_meta_cache' => true,
		'update_post_term_cache' => true,
		'orderby'                => 'date',
		'order'                  => 'DESC',
	);

	$category = sanitize_title( (string) $args['category'] );
	if ( '' !== $category && taxonomy_exists( 'portfolio_category' ) ) {
		$query_args['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
			array(
				'taxonomy' => 'portfolio_category',
				'field'    => 'slug',
				'terms'    => $category,
			),
		);
	}

	$query = new WP_Query( $query_args );
	$posts = $query->posts;

	// Stable sort: explicit sort order first (ascending), then the date order from the query.
	$ranked = array();
	foreach ( $posts as $position => $post ) {
		$raw      = get_post_meta( $post->ID, '_portfolio_sort_order', true );
		$ranked[] = array(
			'post' => $post,
			'rank' => ( '' === $raw || false === $raw ) ? PHP_INT_MAX : (int) $raw,
			'pos'  => $position,
		);
	}
	usort(
		$ranked,
		static function ( $a, $b ) {
			if ( $a['rank'] === $b['rank'] ) {
				return $a['pos'] <=> $b['pos'];
			}
			return $a['rank'] <=> $b['rank'];
		}
	);
	if ( $limit > 0 ) {
		$ranked = array_slice( $ranked, 0, $limit );
	}

	$items = array();
	foreach ( $ranked as $row ) {
		$post     = $row['post'];
		$image_id = stylena_portfolio_image_id( $post->ID );
		$link     = (string) get_post_meta( $post->ID, '_portfolio_external_link', true );
		if ( '' === $link ) {
			$link = (string) get_post_meta( $post->ID, '_portfolio_instagram_link', true );
		}

		$cats  = array();
		$terms = get_the_terms( $post->ID, 'portfolio_category' );
		if ( is_array( $terms ) ) {
			foreach ( $terms as $term ) {
				$cats[ $term->slug ] = $term->name;
			}
		}

		$items[] = array(
			'id'         => (int) $post->ID,
			'title'      => get_the_title( $post ),
			'caption'    => wp_trim_words( wp_strip_all_tags( strip_shortcodes( $post->post_content ) ), 30, '...' ),
			'image_id'   => $image_id,
			'image_url'  => $image_id ? (string) wp_get_attachment_image_url( $image_id, 'large' ) : '',
			'link'       => esc_url_raw( $link ),
			'categories' => $cats,
		);
	}

	/**
	 * Filters the normalised portfolio cards.
	 *
	 * @param array[] $items Cards.
	 * @param array   $args  Query args.
	 */
	return apply_filters( 'stylena_portfolio_items', $items, $args );
}

/**
 * Print the portfolio grid (cards, optional category filters, reveal-more button, lightbox hooks).
 *
 * @param array[] $items Cards from stylena_get_portfolio_items().
 * @param array   $args  {
 *     Optional.
 *
 *     @type int  $initial  Cards shown before "Load more" (0 = all).
 *     @type bool $filters  Show category filter buttons (>= 2 used categories).
 *     @type bool $lightbox Open images in the lightbox.
 *     @type string $columns Bootstrap column classes for a card.
 * }
 * @return void
 */
function stylena_render_portfolio_grid( $items, $args = array() ) {
	if ( empty( $items ) ) {
		return;
	}

	$args = wp_parse_args(
		$args,
		array(
			'initial'  => 0,
			'filters'  => false,
			'lightbox' => (bool) get_theme_mod( 'stylena_portfolio_lightbox', true ),
			'columns'  => 'col-lg-4 col-md-6 col-sm-12',
			'slider'   => false,
		)
	);

	$initial  = absint( $args['initial'] );
	$lightbox = (bool) $args['lightbox'];

	if ( $lightbox ) {
		stylena_enqueue_portfolio_lightbox();
	}

	// Category filters.
	$all_cats = array();
	foreach ( $items as $item ) {
		$all_cats = array_merge( $all_cats, $item['categories'] );
	}
	if ( $args['filters'] && count( $all_cats ) >= 2 ) {
		stylena_enqueue_portfolio_lightbox();
		echo '<div class="stylena-portfolio-filters" role="group" aria-label="' . esc_attr__( 'Filter portfolio by category', 'stylena' ) . '">';
		echo '<button type="button" class="btn btn-outline-light btn-sm" data-filter="*" aria-pressed="true">' . esc_html__( 'All', 'stylena' ) . '</button>';
		foreach ( $all_cats as $slug => $name ) {
			echo '<button type="button" class="btn btn-outline-light btn-sm" data-filter="' . esc_attr( sanitize_html_class( $slug ) ) . '" aria-pressed="false">' . esc_html( $name ) . '</button>';
		}
		echo '</div>';
	}

	$slider = ! empty( $args['slider'] );
	if ( $slider ) {
		echo '<div class="swiper portfolio-swiper stylena-portfolio-swiper" role="region" aria-roledescription="' . esc_attr__( 'carousel', 'stylena' ) . '" aria-label="' . esc_attr__( 'Portfolio', 'stylena' ) . '"><div class="swiper-wrapper stylena-portfolio-items">';
	} else {
		echo '<div class="row stylena-portfolio-items">';
	}
	$count = 0;
	foreach ( $items as $index => $item ) {
		++$count;
		$is_extra = ( $initial > 0 && $count > $initial );
		$classes  = ( $slider ? 'swiper-slide' : $args['columns'] . ' mb-4' ) . ' stylena-portfolio-col' . ( $is_extra ? ' stylena-portfolio-extra' : '' );
		$cats     = implode( ' ', array_map( 'sanitize_html_class', array_keys( $item['categories'] ) ) );
		?>
		<div class="<?php echo esc_attr( $classes ); ?>" data-cats="<?php echo esc_attr( $cats ); ?>"<?php echo $is_extra ? ' hidden' : ''; ?>>
			<div class="portfolio-slide portfolio-grid-item card">
				<div class="card-img">
					<?php
					if ( $item['image_id'] ) {
						$img = wp_get_attachment_image(
							$item['image_id'],
							'stylena-portfolio',
							false,
							array(
								'alt'      => $item['title'],
								'loading'  => ( $index < 3 && ! $is_extra ) ? 'eager' : 'lazy',
								'decoding' => 'async',
								'class'    => 'stylena-portfolio-img',
							)
						);
					} else {
						$img = '<img class="stylena-portfolio-img" src="' . esc_url( stylena_placeholder_url( 'portfolio' ) ) . '" alt="' . esc_attr( $item['title'] ) . '" loading="lazy" decoding="async" />';
					}

					if ( $lightbox && '' !== $item['image_url'] ) {
						?>
						<button type="button" class="stylena-lb-trigger"
							data-lb-src="<?php echo esc_url( $item['image_url'] ); ?>"
							data-lb-title="<?php echo esc_attr( $item['title'] ); ?>"
							data-lb-caption="<?php echo esc_attr( $item['caption'] ); ?>"
							aria-haspopup="dialog"
							aria-label="<?php echo esc_attr( sprintf( /* translators: %s: portfolio item title */ __( 'Open image: %s', 'stylena' ), $item['title'] ) ); ?>">
							<?php echo wp_kses_post( $img ); ?>
						</button>
						<?php
					} else {
						echo wp_kses_post( $img );
					}
					?>
				</div>
				<div class="card-inner">
					<div class="card-header">
						<h3 class="card-title"><?php echo esc_html( $item['title'] ); ?></h3>
					</div>
					<div class="card-text">
						<?php if ( '' !== $item['caption'] ) : ?>
							<p class="caption-text"><?php echo esc_html( $item['caption'] ); ?></p>
						<?php endif; ?>
					</div>
					<div class="card-actions">
						<?php if ( '' !== $item['link'] ) : ?>
							<a href="<?php echo esc_url( $item['link'] ); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-outline-light btn-sm">
								<i class="fas fa-external-link-alt" aria-hidden="true"></i>
								<?php esc_html_e( 'View Project', 'stylena' ); ?>
								<span class="screen-reader-text"><?php esc_html_e( '(opens in a new tab)', 'stylena' ); ?></span>
							</a>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
	echo $slider ? '</div></div>' : '</div>';

	if ( $initial > 0 && $count > $initial ) {
		?>
		<div class="text-center mt-4">
			<button type="button" id="load-more-portfolio" class="btn btn-primary btn-gold" data-step="<?php echo esc_attr( (string) $initial ); ?>">
				<?php esc_html_e( 'Load More', 'stylena' ); ?>
			</button>
		</div>
		<?php
	}
}

/**
 * Print the empty state shown on the portfolio page.
 *
 * @return void
 */
function stylena_render_portfolio_empty() {
	echo '<div class="no-portfolio-content">';
	echo '<h3>' . esc_html__( 'Portfolio coming soon', 'stylena' ) . '</h3>';
	echo '<p>' . esc_html__( 'No portfolio items have been added yet. Check back soon for our latest work!', 'stylena' ) . '</p>';
	if ( current_user_can( 'edit_theme_options' ) ) {
		echo '<p class="small"><a href="' . esc_url( admin_url( 'post-new.php?post_type=portfolio' ) ) . '">' . esc_html__( 'Add portfolio item', 'stylena' ) . '</a> &middot; <a href="' . esc_url( admin_url( 'themes.php?page=one-click-demo-import' ) ) . '">' . esc_html__( 'Import demo content', 'stylena' ) . '</a></p>';
	}
	echo '</div>';
}

/**
 * Enqueue the lightbox/filter script (footer, only where a grid renders).
 *
 * @return void
 */
function stylena_enqueue_portfolio_lightbox() {
	if ( wp_script_is( 'stylena-portfolio-lightbox', 'enqueued' ) ) {
		return;
	}
	$path = get_theme_file_path( '/js/portfolio-lightbox.js' );
	wp_enqueue_script(
		'stylena-portfolio-lightbox',
		get_theme_file_uri( '/js/portfolio-lightbox.js' ),
		array(),
		file_exists( $path ) ? (string) filemtime( $path ) : '1.0.0',
		true
	);
	wp_localize_script(
		'stylena-portfolio-lightbox',
		'stylenaPortfolioLb',
		array(
			'label' => __( 'Image viewer', 'stylena' ),
			'close' => __( 'Close', 'stylena' ),
			'prev'  => __( 'Previous image', 'stylena' ),
			'next'  => __( 'Next image', 'stylena' ),
		)
	);
}

/**
 * Customizer: Portfolio section (lightbox + home section).
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 * @return void
 */
function stylena_portfolio_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'stylena_portfolio_display_section',
		array(
			'title'       => __( 'Portfolio', 'stylena' ),
			'description' => __( 'Settings for the portfolio grid and the home page Portfolio section. Add items under Portfolio Items.', 'stylena' ),
			'panel'       => 'stylena_panel',
			'priority'    => 38,
		)
	);

	$wp_customize->add_setting(
		'stylena_home_portfolio_enable',
		array(
			'default'           => true,
			'sanitize_callback' => 'stylena_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_home_portfolio_enable',
		array(
			'label'   => __( 'Show Portfolio section on the home page', 'stylena' ),
			'section' => 'stylena_portfolio_display_section',
			'type'    => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'stylena_home_portfolio_title',
		array(
			'default'           => __( 'Our Work', 'stylena' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_home_portfolio_title',
		array(
			'label'   => __( 'Home Portfolio title', 'stylena' ),
			'section' => 'stylena_portfolio_display_section',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'stylena_home_portfolio_subtitle',
		array(
			'default'           => __( 'A selection of our recent projects', 'stylena' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_home_portfolio_subtitle',
		array(
			'label'   => __( 'Home Portfolio subtitle', 'stylena' ),
			'section' => 'stylena_portfolio_display_section',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'stylena_home_portfolio_count',
		array(
			'default'           => 6,
			'sanitize_callback' => 'stylena_sanitize_home_portfolio_count',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_home_portfolio_count',
		array(
			'label'       => __( 'Number of items on the home page', 'stylena' ),
			'description' => __( 'The home page Portfolio is a slider showing 3 items at a time on desktop, 2 on tablet and 1 on phone; this is the total number of slides.', 'stylena' ),
			'section'     => 'stylena_portfolio_display_section',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 1,
				'max'  => 12,
				'step' => 1,
			),
		)
	);

	$wp_customize->add_setting(
		'stylena_portfolio_posts_count',
		array(
			'default'           => 12,
			'sanitize_callback' => 'stylena_sanitize_portfolio_posts_count',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_portfolio_posts_count',
		array(
			'label'       => __( 'Portfolio items shown first on the Portfolio page', 'stylena' ),
			'description' => __( 'The Load More button reveals this many further items each click.', 'stylena' ),
			'section'     => 'stylena_portfolio_display_section',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 1,
				'max'  => 50,
				'step' => 1,
			),
		)
	);

	$wp_customize->add_setting(
		'stylena_portfolio_lightbox',
		array(
			'default'           => true,
			'sanitize_callback' => 'stylena_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_portfolio_lightbox',
		array(
			'label'   => __( 'Open portfolio images in a lightbox', 'stylena' ),
			'section' => 'stylena_portfolio_display_section',
			'type'    => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'stylena_portfolio_show_filters',
		array(
			'default'           => true,
			'sanitize_callback' => 'stylena_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_portfolio_show_filters',
		array(
			'label'   => __( 'Show category filters on the Portfolio page', 'stylena' ),
			'section' => 'stylena_portfolio_display_section',
			'type'    => 'checkbox',
		)
	);
}
add_action( 'customize_register', 'stylena_portfolio_customize_register' );
