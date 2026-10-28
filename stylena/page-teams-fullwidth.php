<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/*
Template Name: Teams (Full Width)
Description: Full width page template that displays all team members in a tile grid layout.
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
				echo '<div class="container">';
				the_content();
				echo '</div>';
				// Title positioned over the image on z-axis.
				stylena_render_page_title( get_post_field( 'post_content' ), $stylena_font_family );
				echo '</div>';
			endwhile;
		endif;

		// Get all team members from the database.
		$stylena_team_query = new WP_Query(
			array(
				'post_type'      => 'team',
				'posts_per_page' => -1, // Get all team members.
				'post_status'    => 'publish',
				'meta_key'       => '_team_sort_order',
				'orderby'        => array(
					'meta_value_num' => 'ASC',
					'date'           => 'DESC',
				),
				'order'          => 'ASC',
			)
		);

		// Get theme colors for styling.
		$stylena_primary_color = get_theme_mod( 'stylena_primary_color', '#c4a574' );
		$stylena_body_font     = stylena_get_font_family( get_theme_mod( 'stylena_body_font', 'montserrat' ) );

		if ( $stylena_team_query->have_posts() ) :
			?>
			<div class="container-fluid team-custom">
				<div class="row teams-grid">
					<?php
					while ( $stylena_team_query->have_posts() ) :
						$stylena_team_query->the_post();
						// Get team member meta data.
						$stylena_position    = get_post_meta( get_the_ID(), '_team_position', true );
						$stylena_specialties = get_post_meta( get_the_ID(), '_team_specialties', true );
						$stylena_bio         = get_post_meta( get_the_ID(), '_team_bio', true );
						$stylena_team_image  = get_post_meta( get_the_ID(), '_team_image', true );
						?>
						<div class="col-lg-4 col-md-6 col-sm-12 mb-4">
							<div class="team-tile card h-100">
								<?php if ( ! empty( $stylena_team_image ) && intval( $stylena_team_image ) > 0 ) : ?>
									<div class="team-tile-image">
										<img src="<?php echo esc_url( wp_get_attachment_image_url( $stylena_team_image, 'stylena-team' ) ); ?>"
											alt="<?php echo esc_attr( get_the_title() ); ?>"
											class="card-img-top" />
										<div class="team-tile-overlay"></div>
									</div>
								<?php else : ?>
									<div class="team-tile-image team-tile-placeholder">
										<img src="<?php echo esc_url( stylena_placeholder_url( 'team' ) ); ?>"
											alt="<?php echo esc_attr( get_the_title() ); ?>"
											class="card-img-top" />
										<div class="team-tile-overlay"></div>
									</div>
								<?php endif; ?>

								<div class="card-body team-tile-content">
									<div class="team-tile-header">
										<h3 class="team-tile-name card-title"><?php the_title(); ?></h3>
										<?php if ( ! empty( $stylena_position ) ) : ?>
											<p class="team-tile-position text-muted"><?php echo esc_html( $stylena_position ); ?></p>
										<?php endif; ?>
									</div>

									<?php if ( ! empty( $stylena_bio ) ) : ?>
										<div class="team-tile-bio">
											<p class="card-text"><?php echo esc_html( $stylena_bio ); ?></p>
										</div>
									<?php endif; ?>

									<?php if ( ! empty( $stylena_specialties ) ) : ?>
										<div class="team-tile-specialties">
											<h6 class="specialties-title stylena-tc-color"><?php esc_html_e( 'Specialties:', 'stylena' ); ?></h6>
											<div class="specialties-tags">
												<?php
												$stylena_specialty_list = array_map( 'trim', explode( ',', $stylena_specialties ) );
												foreach ( $stylena_specialty_list as $stylena_specialty ) :
													if ( ! empty( $stylena_specialty ) ) :
														?>
														<span class="specialty-tag badge stylena-tc-outline"><?php echo esc_html( $stylena_specialty ); ?></span>
														<?php
													endif;
												endforeach;
												?>
											</div>
										</div>
									<?php endif; ?>
								</div>
							</div>
						</div>
						<?php
					endwhile;
					wp_reset_postdata();
					?>
				</div>
			</div>
			<?php
		else :
			// No team members found.
			?>
			<div class="container">
				<div class="row">
					<div class="col-12 text-center py-5">
						<div class="no-team-members">
							<i class="fas fa-users fa-3x text-muted mb-3 stylena-tc-faint"></i>
							<h3 class="text-muted stylena-tc-color"><?php esc_html_e( 'No Team Members Found', 'stylena' ); ?></h3>
							<p class="text-muted"><?php esc_html_e( 'There are currently no team members to display. Please check back later.', 'stylena' ); ?></p>
							<?php if ( current_user_can( 'edit_theme_options' ) ) : ?>
								<p class="small"><a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=team' ) ); ?>"><?php esc_html_e( 'Add new', 'stylena' ); ?></a> &middot; <a href="<?php echo esc_url( admin_url( 'themes.php?page=one-click-demo-import' ) ); ?>"><?php esc_html_e( 'Import demo content', 'stylena' ); ?></a></p>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
			<?php
		endif;
		?>
	</div>
</main>

<?php
get_footer();
?>
