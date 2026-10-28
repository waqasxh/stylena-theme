<?php

/**
 * Stylena Theme Customizer
 * Add font selection and other customization options
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add customizer settings
 */
function stylena_customize_register( $wp_customize ) {
	// Footer Social Links
	$wp_customize->add_setting(
		'stylena_footer_facebook',
		array(
			'default'           => 'https://www.facebook.com/',
			'sanitize_callback' => 'esc_url',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_footer_facebook',
		array(
			'label'   => __( 'Facebook Link', 'stylena' ),
			'section' => 'stylena_footer_section',
			'type'    => 'url',
		)
	);

	$wp_customize->add_setting(
		'stylena_footer_instagram',
		array(
			'default'           => 'https://www.instagram.com/',
			'sanitize_callback' => 'esc_url',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_footer_instagram',
		array(
			'label'   => __( 'Instagram Link', 'stylena' ),
			'section' => 'stylena_footer_section',
			'type'    => 'url',
		)
	);

	$wp_customize->add_setting(
		'stylena_footer_telegram',
		array(
			'default'           => '#',
			'sanitize_callback' => 'esc_url',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_footer_telegram',
		array(
			'label'   => __( 'Telegram Link', 'stylena' ),
			'section' => 'stylena_footer_section',
			'type'    => 'url',
		)
	);
	// Footer WhatsApp Link
	$wp_customize->add_setting(
		'stylena_footer_whatsapp_link',
		array(
			'default'           => '#',
			'sanitize_callback' => 'esc_url',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_footer_whatsapp_link',
		array(
			'label'   => __( 'WhatsApp Link', 'stylena' ),
			'section' => 'stylena_footer_section',
			'type'    => 'url',
		)
	);

	// Footer WhatsApp Label
	$wp_customize->add_setting(
		'stylena_footer_whatsapp_label',
		array(
			'default'           => 'WhatsApp',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_footer_whatsapp_label',
		array(
			'label'   => __( 'WhatsApp Label', 'stylena' ),
			'section' => 'stylena_footer_section',
			'type'    => 'text',
		)
	);

	// Footer Phone Number
	$wp_customize->add_setting(
		'stylena_footer_phone_number',
		array(
			'default'           => '+1 (555) 123-4567',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_footer_phone_number',
		array(
			'label'   => __( 'Phone Number', 'stylena' ),
			'section' => 'stylena_footer_section',
			'type'    => 'text',
		)
	);

	// Footer Phone Label
	$wp_customize->add_setting(
		'stylena_footer_phone_label',
		array(
			'default'           => 'Phone',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_footer_phone_label',
		array(
			'label'   => __( 'Phone Label', 'stylena' ),
			'section' => 'stylena_footer_section',
			'type'    => 'text',
		)
	);

	// --- About Section Settings ---
	$wp_customize->add_section(
		'stylena_about_section',
		array(
			'title'       => __( 'About Section', 'stylena' ),
			'description' => __( 'Customize the About section on the homepage', 'stylena' ),
			'panel'       => 'stylena_panel',
			'priority'    => 35,
		)
	);

	// About Section Title
	$wp_customize->add_setting(
		'stylena_about_title',
		array(
			'default'           => 'Stylena',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_about_title',
		array(
			'label'   => __( 'About Section Title', 'stylena' ),
			'section' => 'stylena_about_section',
			'type'    => 'text',
		)
	);

	// About Section Description 1
	$wp_customize->add_setting(
		'stylena_about_desc1',
		array(
			'default'           => __( 'Your business is more than just a place to visit; it’s where every detail is focused on creating a great experience. Use this space to introduce your team, your approach and the services you offer. Replace this text from Appearance > Customize > About section.', 'stylena' ),
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_about_desc1',
		array(
			'label'   => __( 'About Section Description 1', 'stylena' ),
			'section' => 'stylena_about_section',
			'type'    => 'textarea',
		)
	);

	// About Section Description 2
	$wp_customize->add_setting(
		'stylena_about_desc2',
		array(
			'default'           => __( 'Use this space to share your story and values, and to tell visitors what makes your business different. You can edit this text in the Customizer under About.', 'stylena' ),
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_about_desc2',
		array(
			'label'   => __( 'About Section Description 2', 'stylena' ),
			'section' => 'stylena_about_section',
			'type'    => 'textarea',
		)
	);

	add_action( 'customize_register', 'stylena_customize_register' );
	// Founder Image
	$wp_customize->add_setting(
		'stylena_about_founder_image',
		array(
			'default'           => get_theme_file_uri( '/img/placeholders/founder-placeholder.svg' ),
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'stylena_about_founder_image',
			array(
				'label'    => __( 'Founder Image', 'stylena' ),
				'section'  => 'stylena_about_section',
				'settings' => 'stylena_about_founder_image',
			)
		)
	);

	// Founder Name
	$wp_customize->add_setting(
		'stylena_about_founder_name',
		array(
			'default'           => 'Your Founder Name',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_about_founder_name',
		array(
			'label'   => __( 'Founder Name', 'stylena' ),
			'section' => 'stylena_about_section',
			'type'    => 'text',
		)
	);

	// Founder Title
	$wp_customize->add_setting(
		'stylena_about_founder_title',
		array(
			'default'           => __( 'Founder of Stylena', 'stylena' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_about_founder_title',
		array(
			'label'   => __( 'Founder Title', 'stylena' ),
			'section' => 'stylena_about_section',
			'type'    => 'text',
		)
	);

	// --- Services Section Settings ---
	$wp_customize->add_section(
		'stylena_services_section',
		array(
			'title'       => __( 'Services Section', 'stylena' ),
			'description' => __( 'Customize the Services section on the homepage', 'stylena' ),
			'panel'       => 'stylena_panel',
			'priority'    => 36,
		)
	);

	// Services Section Title
	$wp_customize->add_setting(
		'stylena_services_title',
		array(
			'default'           => 'Our Services',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_services_title',
		array(
			'label'   => __( 'Services Section Title', 'stylena' ),
			'section' => 'stylena_services_section',
			'type'    => 'text',
		)
	);

	// View All button text (shared by the Services, Team and Portfolio sections).
	$wp_customize->add_setting(
		'stylena_view_all_label',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_view_all_label',
		array(
			'label'       => __( 'View All button text', 'stylena' ),
			'description' => __( 'Used by the View All buttons under the Services, Team and Portfolio sections. Leave empty for "View All".', 'stylena' ),
			'section'     => 'stylena_services_section',
			'type'        => 'text',
		)
	);

	// Services Section Subtitle
	$wp_customize->add_setting(
		'stylena_services_subtitle',
		array(
			'default'           => __( 'Discover our premium beauty treatments tailored to enhance your natural elegance', 'stylena' ),
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_services_subtitle',
		array(
			'label'   => __( 'Services Section Subtitle', 'stylena' ),
			'section' => 'stylena_services_section',
			'type'    => 'textarea',
		)
	);

	// Services No Services Text
	$wp_customize->add_setting(
		'stylena_services_no_services_text',
		array(
			'default'           => __( 'No services available at the moment.', 'stylena' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_services_no_services_text',
		array(
			'label'   => __( 'No Services Available Text', 'stylena' ),
			'section' => 'stylena_services_section',
			'type'    => 'text',
		)
	);

	// Number of Services to Display
	$wp_customize->add_setting(
		'stylena_services_display_count',
		array(
			'default'           => 6,
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_services_display_count',
		array(
			'label'       => __( 'Number of Services to Display', 'stylena' ),
			'description' => __( 'Set the maximum number of services to show in the services section', 'stylena' ),
			'section'     => 'stylena_services_section',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 1,
				'max'  => 20,
				'step' => 1,
			),
		)
	);

	// Services Price Symbol
	$wp_customize->add_setting(
		'stylena_services_price_symbol',
		array(
			'default'           => '£',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'stylena_services_price_symbol',
		array(
			'label'       => __( 'Price Symbol', 'stylena' ),
			'description' => __( 'Currency or price symbol to display before service prices (e.g., £, $, €)', 'stylena' ),
			'section'     => 'stylena_services_section',
			'type'        => 'text',
			'input_attrs' => array(
				'maxlength'   => 5,
				'placeholder' => '£',
			),
		)
	);

	// --- Team Section Settings ---
	$wp_customize->add_section(
		'stylena_portfolio_section',
		array(
			'title'       => __( 'Team Section', 'stylena' ),
			'description' => __( 'Customize the Team section that appears below services', 'stylena' ),
			'panel'       => 'stylena_panel',
			'priority'    => 37,
		)
	);

	// Portfolio Section Enable/Disable
	$wp_customize->add_setting(
		'stylena_portfolio_enable',
		array(
			'default'           => true,
			'sanitize_callback' => 'stylena_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_portfolio_enable',
		array(
			'label'   => __( 'Enable Team Section', 'stylena' ),
			'section' => 'stylena_portfolio_section',
			'type'    => 'checkbox',
		)
	);

	// Portfolio Section Title
	$wp_customize->add_setting(
		'stylena_portfolio_title',
		array(
			'default'           => 'Our Team',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_portfolio_title',
		array(
			'label'   => __( 'Team Section Title', 'stylena' ),
			'section' => 'stylena_portfolio_section',
			'type'    => 'text',
		)
	);

	// Portfolio Section Subtitle
	$wp_customize->add_setting(
		'stylena_portfolio_subtitle',
		array(
			'default'           => __( 'Meet our talented team of beauty professionals', 'stylena' ),
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_portfolio_subtitle',
		array(
			'label'   => __( 'Team Section Subtitle', 'stylena' ),
			'section' => 'stylena_portfolio_section',
			'type'    => 'textarea',
		)
	);

	// Portfolio Items to Show
	$wp_customize->add_setting(
		'stylena_portfolio_items_count',
		array(
			'default'           => 6,
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_portfolio_items_count',
		array(
			'label'       => __( 'Number of Team Members to Show', 'stylena' ),
			'section'     => 'stylena_portfolio_section',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 1,
				'max'  => 20,
				'step' => 1,
			),
		)
	);

	// --- Image Slider Section Settings ---
	$wp_customize->add_section(
		'stylena_slider_section',
		array(
			'title'           => __( 'Image Slider', 'stylena' ),
			'description'     => __( 'Configure the image slider settings', 'stylena' ),
			'panel'           => 'stylena_panel',
			'priority'        => 45,
			'active_callback' => 'stylena_core_active',
		)
	);

	// Slider Enable/Disable
	$wp_customize->add_setting(
		'stylena_slider_enable',
		array(
			'default'           => true,
			'sanitize_callback' => 'wp_validate_boolean',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_slider_enable',
		array(
			'label'   => __( 'Enable Image Slider', 'stylena' ),
			'section' => 'stylena_slider_section',
			'type'    => 'checkbox',
		)
	);

	// Slider Height
	$wp_customize->add_setting(
		'stylena_slider_height',
		array(
			'default'           => '400px',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_slider_height',
		array(
			'label'       => __( 'Slider Height', 'stylena' ),
			'section'     => 'stylena_slider_section',
			'type'        => 'text',
			'description' => __( 'Enter height with units (e.g., 400px, 50vh)', 'stylena' ),
		)
	);

	// Autoplay Enable
	$wp_customize->add_setting(
		'stylena_slider_autoplay',
		array(
			'default'           => true,
			'sanitize_callback' => 'wp_validate_boolean',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_slider_autoplay',
		array(
			'label'   => __( 'Enable Autoplay', 'stylena' ),
			'section' => 'stylena_slider_section',
			'type'    => 'checkbox',
		)
	);

	// Autoplay Speed
	$wp_customize->add_setting(
		'stylena_slider_autoplay_speed',
		array(
			'default'           => 4000,
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_slider_autoplay_speed',
		array(
			'label'       => __( 'Autoplay Speed (milliseconds)', 'stylena' ),
			'section'     => 'stylena_slider_section',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 1000,
				'max'  => 30000,
				'step' => 500,
			),
		)
	);

	// Slider stop duration (milliseconds) - how long the marquee stays paused after moving one slide-width
	$wp_customize->add_setting(
		'stylena_slider_stop_duration',
		array(
			'default'           => 1000,
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_slider_stop_duration',
		array(
			'label'       => __( 'Marquee Stop Duration (milliseconds)', 'stylena' ),
			'section'     => 'stylena_slider_section',
			'type'        => 'number',
			'description' => __( 'How long the slider pauses after moving one full slide width.', 'stylena' ),
			'input_attrs' => array(
				'min'  => 250,
				'max'  => 20000,
				'step' => 250,
			),
		)
	);

	// --- Footer Section Settings ---
	$wp_customize->add_section(
		'stylena_footer_section',
		array(
			'title'       => __( 'Footer', 'stylena' ),
			'description' => __( 'Customize the footer logo text', 'stylena' ),
			'panel'       => 'stylena_panel',
			'priority'    => 50,
		)
	);

	// --- Header Section Settings ---
	$wp_customize->add_section(
		'stylena_header_section',
		array(
			'title'       => __( 'Header', 'stylena' ),
			'description' => __( 'Customize the header logo text and contact information', 'stylena' ),
			'panel'       => 'stylena_panel',
			'priority'    => 20,
		)
	);

	// Header Logo Text
	$wp_customize->add_setting(
		'stylena_header_logo_text',
		array(
			'default'           => 'Stylena',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_header_logo_text',
		array(
			'label'   => __( 'Header Logo Text', 'stylena' ),
			'section' => 'stylena_header_section',
			'type'    => 'text',
		)
	);

	// Header logo variants (optional; empty = existing logo behaviour).
	$stylena_logo_variants = array(
		'light'  => array(
			__( 'Logo (light / on dark header)', 'stylena' ),
			__( 'Optional. Replaces the site logo in the header. Leave empty to keep the logo from Site Identity.', 'stylena' ),
		),
		'dark'   => array(
			__( 'Logo (dark variant)', 'stylena' ),
			__( 'Optional. For dark-on-light header styles; used by child themes via stylena_get_logo_url( \'dark\' ).', 'stylena' ),
		),
		'mobile' => array(
			__( 'Logo (mobile)', 'stylena' ),
			__( 'Optional. Shown instead of the header logo on screens narrower than 768px.', 'stylena' ),
		),
	);
	foreach ( $stylena_logo_variants as $variant => $variant_labels ) {
		$wp_customize->add_setting(
			'stylena_logo_' . $variant,
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'stylena_logo_' . $variant,
				array(
					'label'       => $variant_labels[0],
					'description' => $variant_labels[1],
					'section'     => 'stylena_header_section',
					'settings'    => 'stylena_logo_' . $variant,
				)
			)
		);
	}

	// Homepage section visibility toggles (default ON = unchanged output).
	// Team, Reviews, Advice, FAQ and Image Slider already have their own toggles.
	// Section ORDER controls are intentionally out of scope.
	$wp_customize->add_section(
		'stylena_sections_visibility',
		array(
			'title'       => __( 'Homepage Sections', 'stylena' ),
			'description' => __( 'Show or hide homepage sections. Team, Reviews, Advice, FAQ and Slider toggles are in their own sections.', 'stylena' ),
			'panel'       => 'stylena_panel',
			'priority'    => 19,
		)
	);
	$stylena_section_toggles = array(
		'hero'     => __( 'Show Hero section', 'stylena' ),
		'about'    => __( 'Show About section', 'stylena' ),
		'services' => __( 'Show Services section', 'stylena' ),
	);
	foreach ( $stylena_section_toggles as $toggle => $toggle_label ) {
		$wp_customize->add_setting(
			'stylena_show_' . $toggle,
			array(
				'default'           => true,
				'sanitize_callback' => 'stylena_sanitize_checkbox',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			'stylena_show_' . $toggle,
			array(
				'label'   => $toggle_label,
				'section' => 'stylena_sections_visibility',
				'type'    => 'checkbox',
			)
		);
	}

	// WhatsApp Link
	$wp_customize->add_setting(
		'stylena_whatsapp_link',
		array(
			'default'           => '#',
			'sanitize_callback' => 'esc_url',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_whatsapp_link',
		array(
			'label'   => __( 'WhatsApp Link', 'stylena' ),
			'section' => 'stylena_header_section',
			'type'    => 'url',
		)
	);

	// Telephone Link
	$wp_customize->add_setting(
		'stylena_telephone_link',
		array(
			'default'           => '#',
			'sanitize_callback' => 'esc_url',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_telephone_link',
		array(
			'label'   => __( 'Telephone Link', 'stylena' ),
			'section' => 'stylena_header_section',
			'type'    => 'url',
		)
	);

	// Telephone Display Text
	$wp_customize->add_setting(
		'stylena_telephone_text',
		array(
			'default'           => '+1 (555) 123-4567',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_telephone_text',
		array(
			'label'   => __( 'Telephone Number', 'stylena' ),
			'section' => 'stylena_header_section',
			'type'    => 'text',
		)
	);

	// Book Button Link
	$wp_customize->add_setting(
		'stylena_book_link',
		array(
			'default'           => '/book',
			'sanitize_callback' => 'esc_url',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_book_link',
		array(
			'label'   => __( 'Book Button Link', 'stylena' ),
			'section' => 'stylena_header_section',
			'type'    => 'url',
		)
	);

	// Book Button Text
	$wp_customize->add_setting(
		'stylena_book_text',
		array(
			'default'           => 'Book',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_book_text',
		array(
			'label'   => __( 'Book Button Text', 'stylena' ),
			'section' => 'stylena_header_section',
			'type'    => 'text',
		)
	);

	// Book Button Show/Hide
	$wp_customize->add_setting(
		'stylena_book_show',
		array(
			'default'           => true,
			'sanitize_callback' => 'stylena_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_book_show',
		array(
			'label'   => __( 'Show Book Button', 'stylena' ),
			'section' => 'stylena_header_section',
			'type'    => 'checkbox',
		)
	);

	// Open Book link in new tab
	$wp_customize->add_setting(
		'stylena_book_open_new_tab',
		array(
			'default'           => false,
			'sanitize_callback' => 'stylena_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_book_open_new_tab',
		array(
			'label'   => __( 'Open Book link in a new tab', 'stylena' ),
			'section' => 'stylena_header_section',
			'type'    => 'checkbox',
		)
	);

	// Footer Logo Text
	$wp_customize->add_setting(
		'stylena_footer_logo_text',
		array(
			'default'           => 'Stylena',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_footer_logo_text',
		array(
			'label'   => __( 'Logo Text', 'stylena' ),
			'section' => 'stylena_footer_section',
			'type'    => 'text',
		)
	);

	// Footer Logo Image
	$wp_customize->add_setting(
		'stylena_footer_logo_image',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'stylena_footer_logo_image',
			array(
				'label'    => __( 'Logo Image', 'stylena' ),
				'section'  => 'stylena_footer_section',
				'settings' => 'stylena_footer_logo_image',
			)
		)
	);

	// Footer Business Location Address
	$wp_customize->add_setting(
		'stylena_footer_location_address',
		array(
			'default'           => "Your Street Address</br>City </br>State 00000",
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_footer_location_address',
		array(
			'label'       => __( 'Business Address', 'stylena' ),
			'section'     => 'stylena_footer_section',
			'type'        => 'textarea',
			'description' => __( 'Enter the business address for the footer. HTML allowed.', 'stylena' ),
		)
	);

	// Footer Business Email
	$wp_customize->add_setting(
		'stylena_footer_location_email',
		array(
			'default'           => 'hello@example.com',
			'sanitize_callback' => 'sanitize_email',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_footer_location_email',
		array(
			'label'       => __( 'Business Email', 'stylena' ),
			'section'     => 'stylena_footer_section',
			'type'        => 'email',
			'description' => __( 'Enter the business email for the footer.', 'stylena' ),
		)
	);

	// Founder Title
	$wp_customize->add_setting(
		'stylena_about_founder_title',
		array(
			'default'           => __( 'Founder of Stylena', 'stylena' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_about_founder_title',
		array(
			'label'   => __( 'Founder Title', 'stylena' ),
			'section' => 'stylena_about_section',
			'type'    => 'text',
		)
	);

	// Add Stylena Settings Panel
	$wp_customize->add_panel(
		'stylena_panel',
		array(
			'title'       => __( 'Stylena Theme Options', 'stylena' ),
			'description' => __( 'Customize your Stylena theme appearance', 'stylena' ),
			'priority'    => 30,
		)
	);

	// Typography Section
	$wp_customize->add_section(
		'stylena_typography',
		array(
			'title'       => __( 'Typography', 'stylena' ),
			'description' => __( 'Choose fonts for your website', 'stylena' ),
			'panel'       => 'stylena_panel',
			'priority'    => 10,
		)
	);

	// Heading Font Setting
	$wp_customize->add_setting(
		'stylena_heading_font',
		array(
			'default'           => 'montserrat',
			'sanitize_callback' => 'stylena_sanitize_font_choice',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'stylena_heading_font',
		array(
			'type'        => 'select',
			'section'     => 'stylena_typography',
			'label'       => __( 'Heading Font', 'stylena' ),
			'description' => __( 'Choose the font for headings (h1, h2, h3, etc.)', 'stylena' ),
			'choices'     => stylena_get_font_choices(),
		)
	);

	// Body Font Setting
	$wp_customize->add_setting(
		'stylena_body_font',
		array(
			'default'           => 'montserrat',
			'sanitize_callback' => 'stylena_sanitize_font_choice',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'stylena_body_font',
		array(
			'type'        => 'select',
			'section'     => 'stylena_typography',
			'label'       => __( 'Body Font', 'stylena' ),
			'description' => __( 'Choose the font for body text', 'stylena' ),
			'choices'     => stylena_get_font_choices(),
		)
	);

	// Opt-in remote Google Fonts (off by default; Exo, Aboreto and Montserrat are self-hosted)
	$wp_customize->add_setting(
		'stylena_load_google_fonts',
		array(
			'default'           => false,
			'sanitize_callback' => 'stylena_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'stylena_load_google_fonts',
		array(
			'type'        => 'checkbox',
			'section'     => 'stylena_typography',
			'label'       => __( 'Load other fonts from Google Fonts', 'stylena' ),
			'description' => __( 'Exo, Aboreto and Montserrat are bundled and never contact Google. The other listed fonts are not bundled; enabling this loads them from Google servers, which sends visitor IP addresses to Google (check your privacy and GDPR obligations).', 'stylena' ),
		)
	);

	// Color Section
	$wp_customize->add_section(
		'stylena_colors',
		array(
			'title'       => __( 'Colors', 'stylena' ),
			'description' => __( 'Customize theme colors', 'stylena' ),
			'panel'       => 'stylena_panel',
			'priority'    => 20,
		)
	);

	// Primary Color Setting
	$wp_customize->add_setting(
		'stylena_primary_color',
		array(
			'default'           => '#FEFEFE',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'stylena_primary_color',
			array(
				'label'       => __( 'Primary Color', 'stylena' ),
				'description' => __( 'Choose the primary theme color', 'stylena' ),
				'section'     => 'stylena_colors',
			)
		)
	);

	// Accent Color Setting
	// Background Color Setting (site-level)
	$wp_customize->add_setting(
		'stylena_background_color',
		array(
			'default'           => '#737270',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'stylena_background_color',
			array(
				'label'       => __( 'Background Color', 'stylena' ),
				'description' => __( 'Set the default site background color', 'stylena' ),
				'section'     => 'stylena_colors',
			)
		)
	);

	// Header Background Color Setting
	$wp_customize->add_setting(
		'stylena_header_bg_color',
		array(
			'default'           => '#ffffff',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'stylena_header_bg_color',
			array(
				'label'       => __( 'Header Background Color (Override)', 'stylena' ),
				'description' => __( 'Override the default header background (leave as default to maintain original styling)', 'stylena' ),
				'section'     => 'stylena_colors',
			)
		)
	);

	// Header Logo Color Setting
	$wp_customize->add_setting(
		'stylena_header_logo_color',
		array(
			'default'           => '#bc9c25',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'stylena_header_logo_color',
			array(
				'label'       => __( 'Header Logo Color (Override)', 'stylena' ),
				'description' => __( 'Override the default header logo color (leave as default to maintain original styling)', 'stylena' ),
				'section'     => 'stylena_colors',
			)
		)
	);

	// Header Menu/Text Color Setting
	$wp_customize->add_setting(
		'stylena_header_menu_color',
		array(
			'default'           => '#333333',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'stylena_header_menu_color',
			array(
				'label'       => __( 'Header Menu & Text Color (Override)', 'stylena' ),
				'description' => __( 'Override the default header menu and text color (leave as default to maintain original styling)', 'stylena' ),
				'section'     => 'stylena_colors',
			)
		)
	);

	// Footer Background Color Setting
	$wp_customize->add_setting(
		'stylena_footer_bg_color',
		array(
			'default'           => '#2c2c2c',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'stylena_footer_bg_color',
			array(
				'label'       => __( 'Footer Background Color (Override)', 'stylena' ),
				'description' => __( 'Override the default footer background color (leave as default to maintain original styling)', 'stylena' ),
				'section'     => 'stylena_colors',
			)
		)
	);

	// Footer Text Color Setting
	$wp_customize->add_setting(
		'stylena_footer_text_color',
		array(
			'default'           => '#ffffff',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'stylena_footer_text_color',
			array(
				'label'       => __( 'Footer Text Color (Override)', 'stylena' ),
				'description' => __( 'Override the default footer text color (leave as default to maintain original styling)', 'stylena' ),
				'section'     => 'stylena_colors',
			)
		)
	);

	// Hero Section
	$wp_customize->add_section(
		'stylena_hero',
		array(
			'title'       => __( 'Hero Section', 'stylena' ),
			'description' => __( 'Customize hero section elements', 'stylena' ),
			'panel'       => 'stylena_panel',
			'priority'    => 30,
		)
	);

	// Hero Title Setting
	$wp_customize->add_setting(
		'stylena_hero_title',
		array(
			'default'           => 'Stylena',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'stylena_hero_title',
		array(
			'type'        => 'text',
			'section'     => 'stylena_hero',
			'label'       => __( 'Hero Title', 'stylena' ),
			'description' => __( 'Main title on homepage hero section', 'stylena' ),
		)
	);

	// Hero Subtitle Setting
	$wp_customize->add_setting(
		'stylena_hero_subtitle',
		array(
			'default'           => 'A modern beauty and wellness studio. Add your own tagline here.',
			'sanitize_callback' => 'sanitize_textarea_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_hero_subtitle',
		array(
			'type'        => 'textarea',
			'section'     => 'stylena_hero',
			'label'       => __( 'Hero Subtitle', 'stylena' ),
			'description' => __( 'Subtitle text on homepage hero section', 'stylena' ),
		)
	);

	// Hero CTA Button 1 Text
	$wp_customize->add_setting(
		'stylena_hero_cta1_text',
		array(
			'default'           => 'Explore Services',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_hero_cta1_text',
		array(
			'type'        => 'text',
			'section'     => 'stylena_hero',
			'label'       => __( 'Hero CTA Button 1 Text', 'stylena' ),
			'description' => __( 'Text for the first hero CTA button', 'stylena' ),
		)
	);

	// Hero CTA Button 1 URL
	$wp_customize->add_setting(
		'stylena_hero_cta1_url',
		array(
			'default'           => '#services',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_hero_cta1_url',
		array(
			'type'        => 'url',
			'section'     => 'stylena_hero',
			'label'       => __( 'Hero CTA Button 1 URL', 'stylena' ),
			'description' => __( 'URL for the first hero CTA button', 'stylena' ),
		)
	);

	// Hero CTA Button 2 Text
	$wp_customize->add_setting(
		'stylena_hero_cta2_text',
		array(
			'default'           => 'Book Now',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_hero_cta2_text',
		array(
			'type'        => 'text',
			'section'     => 'stylena_hero',
			'label'       => __( 'Hero CTA Button 2 Text', 'stylena' ),
			'description' => __( 'Text for the second hero CTA button', 'stylena' ),
		)
	);

	// Hero CTA Button 2 URL
	$wp_customize->add_setting(
		'stylena_hero_cta2_url',
		array(
			'default'           => '#contact',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_hero_cta2_url',
		array(
			'type'        => 'url',
			'section'     => 'stylena_hero',
			'label'       => __( 'Hero CTA Button 2 URL', 'stylena' ),
			'description' => __( 'URL for the second hero CTA button', 'stylena' ),
		)
	);

	// Hero Video Setting
	$wp_customize->add_setting(
		'stylena_hero_video',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'stylena_hero_video',
			array(
				'label'       => __( 'Hero Video', 'stylena' ),
				'description' => __( 'Upload a background video for the homepage hero (MP4, under 10MB recommended). Leave empty to show only the poster image below.', 'stylena' ),
				'section'     => 'stylena_hero',
				'mime_type'   => 'video',
			)
		)
	);

	// Hero Poster Image Setting
	$wp_customize->add_setting(
		'stylena_hero_poster',
		array(
			'default'           => get_theme_file_uri( '/img/placeholders/hero-poster.svg' ),
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'stylena_hero_poster',
			array(
				'label'       => __( 'Hero Poster Image', 'stylena' ),
				'description' => __( 'Shown while the video loads, and as the hero background when no video is set.', 'stylena' ),
				'section'     => 'stylena_hero',
			)
		)
	);

	// Hero Top Margin Desktop Value Setting
	$wp_customize->add_setting(
		'stylena_hero_top_margin_desktop_value',
		array(
			'default'           => '120',
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_hero_top_margin_desktop_value',
		array(
			'type'        => 'number',
			'section'     => 'stylena_hero',
			'label'       => __( 'Hero Top Margin - Desktop (Value)', 'stylena' ),
			'description' => __( 'Margin value for desktop screens', 'stylena' ),
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 500,
				'step' => 1,
			),
		)
	);

	// Hero Top Margin Desktop Unit Setting
	$wp_customize->add_setting(
		'stylena_hero_top_margin_desktop_unit',
		array(
			'default'           => 'px',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_hero_top_margin_desktop_unit',
		array(
			'type'        => 'select',
			'section'     => 'stylena_hero',
			'label'       => __( 'Hero Top Margin - Desktop (Unit)', 'stylena' ),
			'description' => __( 'Unit for desktop margin', 'stylena' ),
			'choices'     => array(
				'px'  => 'Pixels (px)',
				'rem' => 'REM (rem)',
				'vh'  => 'Viewport Height (vh)',
				'%'   => 'Percentage (%)',
			),
		)
	);

	// Hero Top Margin Mobile Value Setting
	$wp_customize->add_setting(
		'stylena_hero_top_margin_mobile_value',
		array(
			'default'           => '80',
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_hero_top_margin_mobile_value',
		array(
			'type'        => 'number',
			'section'     => 'stylena_hero',
			'label'       => __( 'Hero Top Margin - Mobile (Value)', 'stylena' ),
			'description' => __( 'Margin value for mobile screens', 'stylena' ),
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 200,
				'step' => 1,
			),
		)
	);

	// Hero Top Margin Mobile Unit Setting
	$wp_customize->add_setting(
		'stylena_hero_top_margin_mobile_unit',
		array(
			'default'           => 'px',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_hero_top_margin_mobile_unit',
		array(
			'type'        => 'select',
			'section'     => 'stylena_hero',
			'label'       => __( 'Hero Top Margin - Mobile (Unit)', 'stylena' ),
			'description' => __( 'Unit for mobile margin', 'stylena' ),
			'choices'     => array(
				'px'  => 'Pixels (px)',
				'rem' => 'REM (rem)',
				'vh'  => 'Viewport Height (vh)',
				'%'   => 'Percentage (%)',
			),
		)
	);

	// --- Reviews Section Settings ---
	/**
	 * Custom control: Clear Reviews Cache button
	 * Renders an admin-only button which calls admin-ajax to clear the reviews transients.
	 */
	if ( ! class_exists( 'Stylena_Clear_Reviews_Cache_Control' ) ) {
		class Stylena_Clear_Reviews_Cache_Control extends WP_Customize_Control {

			public $type = 'stylena-clear-reviews-button';

			public function render_content() {
				if ( ! current_user_can( 'manage_options' ) ) {
					echo '<p>' . esc_html__( 'Only administrators can clear the reviews cache.', 'stylena' ) . '</p>';
					return;
				}

				$nonce = wp_create_nonce( 'stylena_clear_reviews_cache' );
				?>
				<label><?php echo esc_html( $this->label ); ?></label>
				<p class="description"><?php echo esc_html( $this->description ); ?></p>
				<p>
					<button type="button" id="stylena-clear-reviews-btn" class="button button-secondary">
						<?php echo esc_html__( 'Clear Reviews Cache', 'stylena' ); ?>
					</button>
				</p>
				<script>
					(function() {
						var btn = document.getElementById('stylena-clear-reviews-btn');
						if (!btn) return;
						btn.addEventListener('click', function(e) {
							e.preventDefault();
							btn.disabled = true;
							var xhr = new XMLHttpRequest();
							xhr.open('POST', '<?php echo esc_js( admin_url( 'admin-ajax.php' ) ); ?>');
							xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
							xhr.onload = function() {
								btn.disabled = false;
								try {
									var resp = JSON.parse(xhr.responseText);
									if (resp.success) {
										alert('<?php echo esc_js( __( 'Reviews cache cleared.', 'stylena' ) ); ?>');
										// Refresh preview by reloading the customizer preview frame
										if (window.parent && window.parent.location) {
											try {
												window.parent.location.reload();
											} catch (e) {
												location.reload();
											}
										} else {
											location.reload();
										}
									} else {
										alert('<?php echo esc_js( __( 'Failed to clear reviews cache:', 'stylena' ) ); ?> ' + (resp.data || ''));
									}
								} catch (err) {
									alert('<?php echo esc_js( __( 'Unexpected response clearing cache', 'stylena' ) ); ?>');
								}
							};
							xhr.send('action=stylena_clear_reviews_cache&nonce=<?php echo esc_js( $nonce ); ?>');
						});
					})();
				</script>
				<?php
			}
		}
	}
	$wp_customize->add_section(
		'stylena_reviews_section',
		array(
			'title'       => __( 'Reviews Section', 'stylena' ),
			'description' => __( 'Configure Google Reviews shown on the homepage', 'stylena' ),
			'panel'       => 'stylena_panel',
			'priority'    => 46,
		)
	);

	$wp_customize->add_setting(
		'stylena_reviews_enable',
		array(
			'default'           => true,
			'sanitize_callback' => 'stylena_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_reviews_enable',
		array(
			'label'   => __( 'Enable Reviews', 'stylena' ),
			'section' => 'stylena_reviews_section',
			'type'    => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'stylena_reviews_use_plugin',
		array(
			'default'           => true,
			'sanitize_callback' => 'stylena_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_reviews_use_plugin',
		array(
			'label'       => __( 'Use the Site Reviews plugin when it is active', 'stylena' ),
			'description' => __( 'Untick to keep showing the built-in Google/custom review cards even if Site Reviews is installed.', 'stylena' ),
			'section'     => 'stylena_reviews_section',
			'type'        => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'stylena_reviews_external_optin',
		array(
			'default'           => false,
			'sanitize_callback' => 'stylena_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_reviews_external_optin',
		array(
			'label'       => __( 'Allow contact with Google (Places API)', 'stylena' ),
			'section'     => 'stylena_reviews_section',
			'type'        => 'checkbox',
			'description' => __( 'Off by default. When enabled, your website server contacts Google to load your reviews, which sends the server\'s IP address, your Place ID and your API key. When off, no request is made to Google and only the fallback reviews below are shown.', 'stylena' ),
		)
	);

	// Stored as a non-autoloaded option (not a theme_mod) so it is excluded from Customizer/demo export.
	$wp_customize->add_setting(
		'stylena_google_places_key',
		array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_google_places_key',
		array(
			'label'       => __( 'Google API Key', 'stylena' ),
			'section'     => 'stylena_reviews_section',
			'type'        => 'password',
			'description' => __( 'A Google Places API key with access to Place Details (keep secret; stored outside theme settings and not exported). You can also define STYLENA_GOOGLE_PLACES_KEY in wp-config.php. If left empty, custom fallback reviews will be shown.', 'stylena' ),
		)
	);

	$wp_customize->add_setting(
		'stylena_reviews_place_id',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_reviews_place_id',
		array(
			'label'       => __( 'Google Place ID', 'stylena' ),
			'section'     => 'stylena_reviews_section',
			'type'        => 'text',
			'description' => __( 'The Place ID of your business (use Google Place ID Finder).', 'stylena' ),
		)
	);

	$wp_customize->add_setting(
		'stylena_reviews_limit',
		array(
			'default'           => 3,
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_reviews_limit',
		array(
			'label'       => __( 'Number of reviews to show', 'stylena' ),
			'section'     => 'stylena_reviews_section',
			'type'        => 'number',
			'input_attrs' => array(
				'min' => 1,
				'max' => 10,
			),
		)
	);

	$wp_customize->add_setting(
		'stylena_reviews_title',
		array(
			'default'           => __( 'WHAT OUR CLIENTS SAY', 'stylena' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_reviews_title',
		array(
			'label'   => __( 'Reviews Section Title', 'stylena' ),
			'section' => 'stylena_reviews_section',
			'type'    => 'text',
		)
	);

	// Optional overrides: place name, total reviews and average rating
	$wp_customize->add_setting(
		'stylena_reviews_place_name',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_reviews_place_name',
		array(
			'label'       => __( 'Place / Business Name (override)', 'stylena' ),
			'section'     => 'stylena_reviews_section',
			'type'        => 'text',
			'description' => __( 'Optionally override the place name shown in the reviews header and stats. Leave empty to use Google Places data.', 'stylena' ),
		)
	);

	$wp_customize->add_setting(
		'stylena_reviews_total_reviews',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_reviews_total_reviews',
		array(
			'label'       => __( 'Total reviews (override)', 'stylena' ),
			'section'     => 'stylena_reviews_section',
			'type'        => 'number',
			'input_attrs' => array( 'min' => 0 ),
			'description' => __( 'Enter a number to override the total reviews count shown in the stats. Leave empty to use the calculated count.', 'stylena' ),
		)
	);

	$wp_customize->add_setting(
		'stylena_reviews_avg_rating',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_reviews_avg_rating',
		array(
			'label'       => __( 'Average rating (override)', 'stylena' ),
			'section'     => 'stylena_reviews_section',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 5,
				'step' => 0.1,
			),
			'description' => __( 'Enter a decimal between 0 and 5 (e.g., 4.8) to override the displayed average rating. Leave empty to use the calculated average.', 'stylena' ),
		)
	);

	// Custom fallback reviews (manual)
	for ( $i = 1; $i <= 5; $i++ ) {
		$wp_customize->add_setting(
			"stylena_reviews_custom_text_{$i}",
			array(
				'default'           => '',
				'sanitize_callback' => 'wp_kses_post',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			"stylena_reviews_custom_text_{$i}",
			array(
				/* translators: %d: fallback review number */
				'label'   => sprintf( __( 'Fallback review text #%d', 'stylena' ), $i ),
				'section' => 'stylena_reviews_section',
				'type'    => 'textarea',
			)
		);

		$wp_customize->add_setting(
			"stylena_reviews_custom_author_{$i}",
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			"stylena_reviews_custom_author_{$i}",
			array(
				/* translators: %d: fallback review number */
				'label'   => sprintf( __( 'Fallback author #%d', 'stylena' ), $i ),
				'section' => 'stylena_reviews_section',
				'type'    => 'text',
			)
		);

		$wp_customize->add_setting(
			"stylena_reviews_custom_rating_{$i}",
			array(
				'default'           => 5,
				'sanitize_callback' => 'absint',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			"stylena_reviews_custom_rating_{$i}",
			array(
				/* translators: %d: fallback review number */
				'label'       => sprintf( __( 'Fallback rating #%d (1-5)', 'stylena' ), $i ),
				'section'     => 'stylena_reviews_section',
				'type'        => 'number',
				'input_attrs' => array(
					'min' => 1,
					'max' => 5,
				),
			)
		);
	}

	// Clear Reviews Cache control (admin-only)
	if ( class_exists( 'Stylena_Clear_Reviews_Cache_Control' ) ) {
		$wp_customize->add_setting(
			'stylena_reviews_clear_cache_dummy',
			array(
				'default'           => '',
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Stylena_Clear_Reviews_Cache_Control(
				$wp_customize,
				'stylena_reviews_clear_cache',
				array(
					'label'       => __( 'Clear Reviews Cache', 'stylena' ),
					'section'     => 'stylena_reviews_section',
					'description' => __( 'Click to clear cached Google Reviews transients (admin only).', 'stylena' ),
					// bind to the dummy setting so the control is valid in the Customizer
					'settings'    => 'stylena_reviews_clear_cache_dummy',
					// ensure only admins see this control in the Customizer
					'capability'  => 'manage_options',
				)
			)
		);
	}

	// --- FAQ Section Settings ---
	$wp_customize->add_section(
		'stylena_faq_section',
		array(
			'title'       => __( 'FAQ Section', 'stylena' ),
			'description' => __( 'Customize the FAQ section that appears before the footer', 'stylena' ),
			'panel'       => 'stylena_panel',
			'priority'    => 48,
		)
	);

	// FAQ Section Enable/Disable
	$wp_customize->add_setting(
		'stylena_faq_enable',
		array(
			'default'           => true,
			'sanitize_callback' => 'stylena_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_faq_enable',
		array(
			'label'       => __( 'Enable FAQ Section', 'stylena' ),
			'description' => __( 'Show or hide the FAQ section on the homepage', 'stylena' ),
			'section'     => 'stylena_faq_section',
			'type'        => 'checkbox',
		)
	);

	// FAQ Section Title
	$wp_customize->add_setting(
		'stylena_faq_section_title',
		array(
			'default'           => 'FREQUENTLY ASKED QUESTIONS ABOUT SPECIFIC SERVICES',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'stylena_faq_section_title',
		array(
			'label'   => __( 'FAQ Section Title', 'stylena' ),
			'section' => 'stylena_faq_section',
			'type'    => 'text',
		)
	);

	// FAQ Section Image
	$wp_customize->add_setting(
		'stylena_faq_section_image',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'stylena_faq_section_image',
			array(
				'label'       => __( 'FAQ Section Image', 'stylena' ),
				'description' => __( 'Upload an image for the FAQ section. If no image is uploaded, a stylized placeholder scene will be displayed.', 'stylena' ),
				'section'     => 'stylena_faq_section',
				'settings'    => 'stylena_faq_section_image',
			)
		)
	);
}
add_action( 'customize_register', 'stylena_customize_register' );

/**
 * Get available font choices
 */
function stylena_get_font_choices() {
	/**
	 * Filters the font choices offered in the Customizer (key => label).
	 * Pair with 'stylena_font_families' to supply the CSS value for new keys.
	 *
	 * @param array $choices Font choices.
	 */
	return apply_filters(
		'stylena_font_choices',
		array(
			'exo'                => __( 'Exo (Sans Serif)', 'stylena' ),
			'aboreto'            => __( 'Aboreto (Display)', 'stylena' ),
			'montserrat-medium'  => __( 'Montserrat Medium (Sans Serif)', 'stylena' ),
			'cormorant-garamond' => __( 'Cormorant Garamond (Serif)', 'stylena' ),
			'montserrat'         => __( 'Montserrat (Sans Serif)', 'stylena' ),
			'roboto'             => __( 'Roboto (Sans Serif)', 'stylena' ),
			'open-sans'          => __( 'Open Sans (Sans Serif)', 'stylena' ),
			'lato'               => __( 'Lato (Sans Serif)', 'stylena' ),
			'poppins'            => __( 'Poppins (Sans Serif)', 'stylena' ),
			'playfair'           => __( 'Playfair Display (Serif)', 'stylena' ),
			'lora'               => __( 'Lora (Serif)', 'stylena' ),
			'merriweather'       => __( 'Merriweather (Serif)', 'stylena' ),
			'crimson-text'       => __( 'Crimson Text (Serif)', 'stylena' ),
			'dancing-script'     => __( 'Dancing Script (Cursive)', 'stylena' ),
			'great-vibes'        => __( 'Great Vibes (Cursive)', 'stylena' ),
			'system-fonts'       => __( 'System Fonts', 'stylena' ),
		)
	);
}

/**
 * Sanitize font choice
 */
function stylena_sanitize_font_choice( $input ) {
	$valid_choices = array_keys( stylena_get_font_choices() );
	return in_array( $input, $valid_choices ) ? $input : 'montserrat';
}

/**
 * Sanitize checkbox values for customizer
 */
function stylena_sanitize_checkbox( $checked ) {
	return ( ( isset( $checked ) && $checked ) ? true : false );
}

/**
 * Get Google Fonts URL based on selected fonts.
 *
 * PRIVACY: Exo, Aboreto and Montserrat are self-hosted (css/fonts.css) and never
 * request Google. Remote loading of any other family only happens when the
 * site owner explicitly enables "stylena_load_google_fonts" (default: off),
 * because it transmits visitor IP addresses to Google.
 */
function stylena_get_google_fonts_url() {
	if ( ! get_theme_mod( 'stylena_load_google_fonts', false ) ) {
		return '';
	}

	$heading_font = get_theme_mod( 'stylena_heading_font', 'montserrat' );
	$body_font    = get_theme_mod( 'stylena_body_font', 'montserrat' );

	$fonts        = array();
	$font_weights = ':wght@400;500;600;700';

	// Map font choices to Google Fonts families
	$google_fonts_map = array(
		'roboto'             => 'Roboto',
		'open-sans'          => 'Open+Sans',
		'lato'               => 'Lato',
		'poppins'            => 'Poppins',
		'playfair'           => 'Playfair+Display',
		'lora'               => 'Lora',
		'merriweather'       => 'Merriweather',
		'crimson-text'       => 'Crimson+Text',
		'dancing-script'     => 'Dancing+Script',
		'great-vibes'        => 'Great+Vibes',
		'cormorant-garamond' => 'Cormorant+Garamond',
	);

	// Add heading font
	if ( isset( $google_fonts_map[ $heading_font ] ) ) {
		$fonts[] = $google_fonts_map[ $heading_font ] . $font_weights;
	}

	// Add body font if different
	if ( $body_font !== $heading_font && isset( $google_fonts_map[ $body_font ] ) ) {
		$fonts[] = $google_fonts_map[ $body_font ] . $font_weights;
	}

	if ( empty( $fonts ) ) {
		return '';
	}

	return 'https://fonts.googleapis.com/css2?family=' . implode( '&family=', $fonts ) . '&display=swap';
}

/**
 * Get CSS font family for selected font
 */
function stylena_get_font_family( $font_choice ) {
	$font_families = array(
		'exo'                => '"Exo", sans-serif',
		'aboreto'            => '"Aboreto", cursive',
		'montserrat-medium'  => '"Montserrat", sans-serif',
		'cormorant-garamond' => '"Cormorant Garamond", serif',
		'montserrat'         => '"Montserrat", sans-serif',
		'roboto'             => '"Roboto", sans-serif',
		'open-sans'          => '"Open Sans", sans-serif',
		'lato'               => '"Lato", sans-serif',
		'poppins'            => '"Poppins", sans-serif',
		'playfair'           => '"Playfair Display", serif',
		'lora'               => '"Lora", serif',
		'merriweather'       => '"Merriweather", serif',
		'crimson-text'       => '"Crimson Text", serif',
		'dancing-script'     => '"Dancing Script", cursive',
		'great-vibes'        => '"Great Vibes", cursive',
		'system-fonts'       => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif',
	);

	/**
	 * Filters the map of font choice keys to CSS font-family values.
	 *
	 * @param array $font_families Key => CSS font-family string.
	 */
	$font_families = apply_filters( 'stylena_font_families', $font_families );

	return isset( $font_families[ $font_choice ] ) ? $font_families[ $font_choice ] : $font_families['montserrat'];
}

/**
 * Enqueue Google Fonts
 */
function stylena_enqueue_google_fonts() {
	$fonts_url = stylena_get_google_fonts_url();
	if ( ! empty( $fonts_url ) ) {
		wp_enqueue_style( 'stylena-google-fonts', $fonts_url, array(), null );
	}
}
add_action( 'wp_enqueue_scripts', 'stylena_enqueue_google_fonts' );

/**
 * Text for the View All buttons on the home page sections.
 *
 * @return string
 */
function stylena_get_view_all_label() {
	$label = trim( (string) get_theme_mod( 'stylena_view_all_label', '' ) );
	return '' !== $label ? $label : __( 'View All', 'stylena' );
}

/**
 * Whether a hex colour is light (needs dark text on top of it).
 *
 * @param string $hex Hex colour such as #ffffff.
 * @return bool
 */
function stylena_hex_is_light( $hex ) {
	$hex = sanitize_hex_color( (string) $hex );
	if ( ! $hex ) {
		return false;
	}
	$rgb = sscanf( $hex, '#%02x%02x%02x' );
	if ( 3 !== count( array_filter( $rgb, 'is_int' ) ) ) {
		$rgb = sscanf( '#' . str_repeat( substr( $hex, 1, 1 ), 2 ) . str_repeat( substr( $hex, 2, 1 ), 2 ) . str_repeat( substr( $hex, 3, 1 ), 2 ), '#%02x%02x%02x' );
	}
	$luminance = ( 0.2126 * $rgb[0] + 0.7152 * $rgb[1] + 0.0722 * $rgb[2] ) / 255;
	return $luminance > 0.55;
}

/**
 * Generate custom CSS for font selections
 */
function stylena_custom_css() {
	$heading_font      = get_theme_mod( 'stylena_heading_font', 'montserrat' );
	$body_font         = get_theme_mod( 'stylena_body_font', 'montserrat' );
	$primary_color     = get_theme_mod( 'stylena_primary_color', '#FEFEFE' );
	$background_color  = get_theme_mod( 'stylena_background_color', '#737270' );
	$header_bg_color   = get_theme_mod( 'stylena_header_bg_color', '#ffffff' );
	$header_logo_color = get_theme_mod( 'stylena_header_logo_color', '#bc9c25' );
	$header_menu_color = get_theme_mod( 'stylena_header_menu_color', '#333333' );
	$footer_bg_color   = get_theme_mod( 'stylena_footer_bg_color', '#2c2c2c' );
	$footer_text_color = get_theme_mod( 'stylena_footer_text_color', '#ffffff' );

	$heading_font_family = stylena_get_font_family( $heading_font );
	$body_font_family    = stylena_get_font_family( $body_font );

	$portfolio_title_color = sanitize_hex_color( (string) get_theme_mod( 'stylena_primary_color', '#FEFEFE' ) );
	if ( ! $portfolio_title_color ) {
		$portfolio_title_color = '#FEFEFE';
	}

	$css = "
    :root { --stylena-portfolio-title-color: {$portfolio_title_color}; --stylena-section-color: {$portfolio_title_color}; --stylena-heading-font: {$heading_font_family}; --stylena-body-font: {$body_font_family}; }
    /* Custom Typography */
    body {
        font-family: {$body_font_family};
    }
    
    h1, h2, h3, h4, h5, h6,
    .site-title-heading,
    .hero-title,
    .founder-name,
    .modal-title,
    .footer-logo,
    .sytlena-logo,
    .brands-section h2 {
        font-family: {$heading_font_family};
    }
    
    /* Logo Styling */
    .site-logo img,
    .logo-img {
        max-height: 40px;
        width: auto;
    }
    
    /* Desktop Logo Styling */
    @media (min-width: 1024px) {
        .site-logo img,
        .logo-img {
            max-height: 65px;
        }
    }
    
    .footer-logo img,
    .footer-logo-img {
        max-height: 60px;
        width: auto;
        aspect-ratio: 12 / 5;
    }
    
    /* Original Colors - Always Applied */
    body {
        color: {$primary_color};
        background-color: {$background_color};
    }
    .top-nav-menu li a:hover,
    .bottom-nav-menu li a:hover,
    .social-link:hover {
        color: {$primary_color};
    }
    .header-separator {
        background: {$primary_color};
    }
    .hero-btn.btn-primary,
    .house-card .btn-primary {
        background: {$primary_color};
        border-color: {$primary_color};
    }
    .hero-btn.btn-primary:hover,
    .house-card .btn-primary:hover {
        /* If you want to use background color for hover, uncomment below */
        /* background: {$background_color};
        border-color: {$background_color}; */
    }
    .location-info h5 {
        color: {$primary_color};
    }
    .consultation-form .form-control:focus {
        border-color: {$primary_color};
        box-shadow: 0 0 0 0.2rem rgba(" . implode( ', ', sscanf( $primary_color, "#%02x%02x%02x" ) ) . ", 0.25);
    }
    ";

	// Only add header background color if it's different from default transparent/white
	if ( $header_bg_color !== '#ffffff' ) {
		$css .= "
        .homepage-header .header-top,
        .homepage-header .header-bottom {
            background-color: {$header_bg_color};
        }
        ";
	}

	$header_is_light = '#ffffff' !== $header_bg_color && stylena_hex_is_light( $header_bg_color );

	// Only add header logo color if it's different from primary color
	if ( '#bc9c25' === $header_logo_color && $header_is_light ) {
		// The default gold logo text is too faint on a light header; use a darker gold.
		$header_logo_color = '#7a5f0f';
	}
	if ( '#bc9c25' !== $header_logo_color ) {
		$css .= "
        .header-logo-text {
            color: {$header_logo_color} !important;
        }
        ";
	}

	// Only add header menu color if it's different from default; the default (#333333) is also
	// applied on a custom light header, where the built-in near-white menu text would vanish.
	if ( $header_menu_color !== '#333333' || $header_is_light ) {
		$css .= "
        .top-nav-menu li a,
        .bottom-nav-menu li a,
        .nav-menu a,
        .header__wa,
        .header-btn {
            color: {$header_menu_color} !important;
        }
        ";
	}

	// Only add footer background color if it's different from default
	if ( $footer_bg_color !== '#2c2c2c' ) {
		$css .= "
        .homepage-footer {
            background-color: {$footer_bg_color} !important;
        }
        ";
	}

	// Only add footer text color if it's different from default white
	if ( $footer_text_color !== '#ffffff' ) {
		$css .= "
        .homepage-footer,
        .homepage-footer p,
        .homepage-footer h6,
        .homepage-footer .footer-menu li a,
    .homepage-footer .sytlena-logo,
        .text-white,
        .text-white-50 {
            color: {$footer_text_color} !important;
        }
        ";
	}

	return $css;
}

/**
 * Output custom CSS in head
 */
function stylena_output_custom_css() {
	$custom_css = stylena_custom_css();
	if ( ! empty( $custom_css ) ) {
		echo "<style type='text/css' id='stylena-customizer-css'>\n" . wp_strip_all_tags( $custom_css ) . "\n</style>\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- CSS stripped of tags via wp_strip_all_tags().
	}
}
add_action( 'wp_head', 'stylena_output_custom_css' );

/**
 * Add customizer live preview script
 */
function stylena_customize_preview_js() {
	wp_enqueue_script( 'stylena-customizer-preview', get_theme_file_uri( '/js/customizer-preview.js' ), array( 'customize-preview' ), wp_get_theme()->get( 'Version' ), true );
}
add_action( 'customize_preview_init', 'stylena_customize_preview_js' );
