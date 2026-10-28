<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/*
Template Name: Services (Full Width)
Description: Full width page template that lists all services grouped by service category in tabs.
*/
get_header();

if ( ! stylena_core_active() ) {
	echo '<main id="main" tabindex="-1" class="fullwidth-main image-top" role="main">';
	stylena_core_unavailable_notice();
	echo '</main>';
	get_footer();
	return;
}
?>

<main id="main" tabindex="-1" class="fullwidth-main image-top" role="main">
	<div class="container-fluid image-top py-5">
		<?php
		// Title/intro from page content (constrain this part).
		if ( have_posts() ) :
			while ( have_posts() ) :
				the_post();
				// Get heading font from customizer.
				if ( ! function_exists( 'stylena_get_font_family' ) ) {
					require_once get_template_directory() . '/inc/functions/theme-customizer.php';
				}
				$stylena_heading_font = get_theme_mod( 'stylena_heading_font', 'montserrat' );
				$stylena_font_family  = stylena_get_font_family( $stylena_heading_font );

				echo '<div class="position-relative">';
				echo '<div class="container page-header mb-4">';
				the_content();
				echo '</div>';
				// Title positioned over the image on z-axis.
				stylena_render_page_title( get_post_field( 'post_content' ), $stylena_font_family );
				echo '</div>';
			endwhile;
		endif;

		// Get all service categories ordered by term meta 'sort_order' (including empty ones).
		$stylena_terms = get_terms(
			array(
				'taxonomy'   => 'service_category',
				'hide_empty' => false,
				'orderby'    => 'name',
				'order'      => 'ASC',
			)
		);

		// If categories have sort_order meta, sort by that, otherwise keep name order.
		if ( ! empty( $stylena_terms ) && ! is_wp_error( $stylena_terms ) ) {
			usort(
				$stylena_terms,
				function ( $a, $b ) {
					$sort_a = get_term_meta( $a->term_id, 'sort_order', true );
					$sort_b = get_term_meta( $b->term_id, 'sort_order', true );

					// If both have sort_order, compare them.
					if ( '' !== $sort_a && '' !== $sort_b ) {
						return intval( $sort_a ) - intval( $sort_b );
					}

					// If only one has sort_order, prioritize it.
					if ( '' !== $sort_a && '' === $sort_b ) {
						return -1;
					}
					if ( '' === $sort_a && '' !== $sort_b ) {
						return 1;
					}

					// If neither has sort_order, sort by name.
					return strcmp( $a->name, $b->name );
				}
			);
		}

		// If no categories, show a friendly empty state.
		if ( empty( $stylena_terms ) || is_wp_error( $stylena_terms ) ) {
			stylena_empty_state( get_theme_mod( 'stylena_services_no_services_text', __( 'No services available at the moment.', 'stylena' ) ), 'service' );
			echo '</div></main>';
			get_footer();
			return;
		}

		// Styles moved to assets/css/main.css.


		// Build category-only tabs and mobile dropdown.
		echo '<div class="services-navigation">';

		// Mobile dropdown (hidden on desktop).
		echo '<div class="services-dropdown d-md-none">';
		echo '<select class="form-select services-mobile-select" id="servicesMobileSelect">';
		foreach ( $stylena_terms as $stylena_index => $term ) {
			$stylena_selected = ( 0 === $stylena_index ) ? ' selected' : '';
			echo '<option value="panel-' . esc_attr( $term->slug ) . '"' . esc_attr( $stylena_selected ) . '>' . esc_html( $term->name ) . '</option>';
		}
		echo '</select>';
		echo '</div>';

		// Desktop tabs (hidden on mobile).
		echo '<ul class="nav nav-tabs services-tabs d-none d-md-flex" id="stylenaServicesTabs" role="tablist">';
		foreach ( $stylena_terms as $stylena_index => $term ) {
			$stylena_active   = ( 0 === $stylena_index ) ? ' active' : '';
			$stylena_tab_id   = 'tab-' . esc_attr( $term->slug );
			$stylena_panel_id = 'panel-' . esc_attr( $term->slug );
			echo '<li class="nav-item" role="presentation">';
			echo '<a class="nav-link' . esc_attr( $stylena_active ) . '" id="' . esc_attr( $stylena_tab_id ) . '" data-toggle="tab" href="#' . esc_attr( $stylena_panel_id ) . '" role="tab" aria-controls="' . esc_attr( $stylena_panel_id ) . '" aria-selected="' . ( 0 === $stylena_index ? 'true' : 'false' ) . '">' . esc_html( $term->name ) . '</a>';
			echo '</li>';
		}
		echo '</ul>';
		echo '</div>';

		// Panels container.
		echo '<div class="tab-content services-tab-content mt-4" id="stylenaServicesTabsContent">';

		// Panels per category (same alternating full-width rows).
		foreach ( $stylena_terms as $stylena_index => $term ) {
			$stylena_panel_id     = 'panel-' . esc_attr( $term->slug );
			$stylena_panel_active = ( 0 === $stylena_index ) ? ' show active' : '';
			echo '<div class="tab-pane fade' . esc_attr( $stylena_panel_active ) . '" id="' . esc_attr( $stylena_panel_id ) . '" role="tabpanel" aria-labelledby="tab-' . esc_attr( $term->slug ) . '">';

			// Query services in this category ordered by _service_sort_order then title.
			$stylena_cat_query = new WP_Query(
				array(
					'post_type'      => 'service',
					'posts_per_page' => -1,
					'tax_query'      => array(
						array(
							'taxonomy' => 'service_category',
							'field'    => 'slug',
							'terms'    => $term->slug,
						),
					),
					'meta_key'       => '_service_sort_order',
					'orderby'        => array(
						'meta_value_num' => 'ASC',
						'title'          => 'ASC',
					),
				)
			);

			if ( $stylena_cat_query->have_posts() ) {
				$stylena_i = 0;
				while ( $stylena_cat_query->have_posts() ) {
					$stylena_cat_query->the_post();
					$stylena_service_id = get_the_ID();
					// Prefer featured image (Gutenberg) then fallback to custom meta image.
					$stylena_img_url = '';
					if ( has_post_thumbnail( $stylena_service_id ) ) {
						$stylena_img_url = get_the_post_thumbnail_url( $stylena_service_id, 'stylena-service' );
					} else {
						$stylena_image_id = get_post_meta( $stylena_service_id, '_service_image', true );
						if ( $stylena_image_id ) {
							$stylena_img_url = wp_get_attachment_image_url( $stylena_image_id, 'stylena-service' );
						}
					}
					if ( ! $stylena_img_url ) {
						$stylena_img_url = stylena_placeholder_url( 'service' );
					}
					$stylena_description  = get_post_meta( $stylena_service_id, '_service_description', true );
					$stylena_price        = get_post_meta( $stylena_service_id, '_service_price', true );
					$stylena_duration     = get_post_meta( $stylena_service_id, '_service_duration', true );
					$stylena_service_link = get_post_meta( $stylena_service_id, '_service_link', true ) ?: get_permalink( $stylena_service_id );

					$stylena_is_even = ( $stylena_i % 2 ) === 0;

					echo '<div class="service-row">';
					echo '<div class="service-image-col col-12 col-md-4 ' . ( $stylena_is_even ? '' : 'order-md-2' ) . '">';
					if ( $stylena_img_url ) {
						echo '<div class="service-image service-image-fullbleed"><img src="' . esc_url( $stylena_img_url ) . '" alt="' . esc_attr( get_the_title() ) . '" loading="lazy" /></div>';
					} else {
						echo '<div class="service-image service-image--empty">' . esc_html__( 'No image', 'stylena' ) . '</div>';
					}
					echo '</div>';

					echo '<div class="service-content-col col-12 col-md-8 ' . ( $stylena_is_even ? '' : 'order-md-1' ) . '">';
					echo '<h3>' . esc_html( get_the_title() ) . '</h3>';
					if ( $stylena_description ) {
						echo '<div class="service-desc">' . wp_kses_post( wpautop( $stylena_description ) ) . '</div>';
					} else {
						echo '<div class="service-desc">' . wp_kses_post( get_the_excerpt() ) . '</div>';
					}

					// Details (Price & Duration) - shown below description and above the Reserve button.
					echo '<div class="service-details mt-3 mb-3">';
					if ( $stylena_price ) {
						echo '<div class="service-price"><strong>' . esc_html__( 'Price:', 'stylena' ) . '</strong> <span>' . esc_html( stylena_format_price( $stylena_price ) ) . '</span></div>';
					}
					if ( $stylena_duration ) {
						echo '<div class="service-duration"><strong>' . esc_html__( 'Duration:', 'stylena' ) . '</strong> <span>' . esc_html( $stylena_duration ) . '</span></div>';
					}
					echo '</div>';

					echo '<div class="mt-2">';
					echo '<a href="' . esc_url( $stylena_service_link ) . '" class="btn btn-primary">' . esc_html__( 'Reserve', 'stylena' ) . '</a>';
					echo '</div>';
					echo '</div>';

					echo '</div>'; // service-row.

					++$stylena_i;
				}
				wp_reset_postdata();
			} else {
				echo '<p>' . esc_html( get_theme_mod( 'stylena_services_no_services_text', __( 'No services available at the moment.', 'stylena' ) ) ) . '</p>';
			}

			echo '</div>'; // panel for term.
		}

		echo '</div>'; // tab-content.
		?>
	</div>
</main>

<script>
	// Mobile dropdown functionality for services tabs
	document.addEventListener('DOMContentLoaded', function() {
		const mobileSelect = document.getElementById('servicesMobileSelect');

		if (mobileSelect) {
			mobileSelect.addEventListener('change', function() {
				// Hide all tab panels
				const allPanels = document.querySelectorAll('.tab-pane');
				allPanels.forEach(panel => {
					panel.classList.remove('show', 'active');
				});

				// Show selected panel
				const selectedPanelId = this.value;
				const selectedPanel = document.getElementById(selectedPanelId);
				if (selectedPanel) {
					selectedPanel.classList.add('show', 'active');
				}

				// Update desktop tabs to match (for consistency)
				const allDesktopTabs = document.querySelectorAll('.services-tabs .nav-link');
				allDesktopTabs.forEach(tab => {
					tab.classList.remove('active');
					tab.setAttribute('aria-selected', 'false');
				});

				// Find and activate corresponding desktop tab
				const correspondingTab = document.querySelector(`[href="#${selectedPanelId}"]`);
				if (correspondingTab) {
					correspondingTab.classList.add('active');
					correspondingTab.setAttribute('aria-selected', 'true');
				}
			});
		}

		// Desktop tab clicks should update mobile dropdown
		const desktopTabs = document.querySelectorAll('.services-tabs .nav-link');
		desktopTabs.forEach(tab => {
			tab.addEventListener('click', function() {
				const targetPanel = this.getAttribute('href').substring(1); // Remove #
				if (mobileSelect) {
					mobileSelect.value = targetPanel;
				}
			});
		});
	});
</script>

<?php
get_footer();
