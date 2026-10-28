/**
 * Stylena Theme Customizer Live Preview
 */

(function ($) {
    'use strict';

    // Font choices mapping
    const fontFamilies = {
        'exo': '"Exo", sans-serif',
        'montserrat': '"Montserrat", sans-serif',
        'montserrat-medium': '"Montserrat", sans-serif',
        'aboreto': '"Aboreto", cursive',
        'roboto': '"Roboto", sans-serif',
        'open-sans': '"Open Sans", sans-serif',
        'lato': '"Lato", sans-serif',
        'poppins': '"Poppins", sans-serif',
        'playfair': '"Playfair Display", serif',
        'lora': '"Lora", serif',
        'merriweather': '"Merriweather", serif',
        'crimson-text': '"Crimson Text", serif',
        'dancing-script': '"Dancing Script", cursive',
        'great-vibes': '"Great Vibes", cursive',
        'system-fonts': '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif'
    };

    // Heading Font
    wp.customize('stylena_heading_font', function (value) {
        value.bind(function (newval) {
            const fontFamily = fontFamilies[newval] || fontFamilies.montserrat;
            const headingSelectors = 'h1, h2, h3, h4, h5, h6, .site-title-heading, .hero-title, .founder-name, .modal-title';

            // Remove existing heading font style
            $('#stylena-heading-font-style').remove();

            // Add new style
            $('head').append('<style id="stylena-heading-font-style">' + headingSelectors + ' { font-family: ' + fontFamily + ' !important; }</style>');
        });
    });

    // Body Font
    wp.customize('stylena_body_font', function (value) {
        value.bind(function (newval) {
            const fontFamily = fontFamilies[newval] || fontFamilies.montserrat;

            // Remove existing body font style
            $('#stylena-body-font-style').remove();

            // Add new style
            $('head').append('<style id="stylena-body-font-style">body { font-family: ' + fontFamily + ' !important; }</style>');
        });
    });

    // Primary Color
    wp.customize('stylena_primary_color', function (value) {
        value.bind(function (newval) {
            // Remove existing primary color style
            $('#stylena-primary-color-style').remove();

            // Calculate RGB values for box-shadow
            const hex = newval.replace('#', '');
            const r = parseInt(hex.substr(0, 2), 16);
            const g = parseInt(hex.substr(2, 2), 16);
            const b = parseInt(hex.substr(4, 2), 16);

            const css = `
                .top-nav-menu li a:hover,
                .bottom-nav-menu li a:hover,
                .social-link:hover { color: ${newval} !important; }
                
                .header-separator { background: linear-gradient(90deg, transparent, ${newval}, transparent) !important; }
                
                .hero-btn.btn-primary,
                .house-card .btn-primary { 
                    background: ${newval} !important; 
                    border-color: ${newval} !important; 
                }
                
                .location-info h5 { color: ${newval} !important; }
                
                .consultation-form .form-control:focus { 
                    border-color: ${newval} !important; 
                    box-shadow: 0 0 0 0.2rem rgba(${r}, ${g}, ${b}, 0.25) !important; 
                }
            `;

            // Add new style
            $('head').append('<style id="stylena-primary-color-style">' + css + '</style>');
        });
    });

    // Background Color
    wp.customize('stylena_background_color', function (value) {
        value.bind(function (newval) {
            // Remove existing background color style
            $('#stylena-background-color-style').remove();

            const css = `
                body { background-color: ${newval} !important; }
            `;

            // Add new style
            $('head').append('<style id="stylena-background-color-style">' + css + '</style>');
        });
    });

    // Hero Title
    wp.customize('stylena_hero_title', function (value) {
        value.bind(function (newval) {
            $('.hero-title').text(newval);
        });
    });

    // Hero Subtitle
    wp.customize('stylena_hero_subtitle', function (value) {
        value.bind(function (newval) {
            $('.hero-subtitle').text(newval);
        });
    });

    // Hero Top Margin - Desktop Value
    wp.customize('stylena_hero_top_margin_desktop_value', function (value) {
        value.bind(function (newval) {
            updateHeroMargins();
        });
    });

    // Hero Top Margin - Desktop Unit
    wp.customize('stylena_hero_top_margin_desktop_unit', function (value) {
        value.bind(function (newval) {
            updateHeroMargins();
        });
    });

    // Hero Top Margin - Mobile Value
    wp.customize('stylena_hero_top_margin_mobile_value', function (value) {
        value.bind(function (newval) {
            updateHeroMargins();
        });
    });

    // Hero Top Margin - Mobile Unit
    wp.customize('stylena_hero_top_margin_mobile_unit', function (value) {
        value.bind(function (newval) {
            updateHeroMargins();
        });
    });

    // Function to update hero margins based on all settings
    function updateHeroMargins() {
        const desktopValue = wp.customize('stylena_hero_top_margin_desktop_value').get() || '120';
        const desktopUnit = wp.customize('stylena_hero_top_margin_desktop_unit').get() || 'px';
        const mobileValue = wp.customize('stylena_hero_top_margin_mobile_value').get() || '80';
        const mobileUnit = wp.customize('stylena_hero_top_margin_mobile_unit').get() || 'px';

        const desktopMargin = desktopValue + desktopUnit;
        const mobileMargin = mobileValue + mobileUnit;

        // Remove existing hero margin style
        $('#stylena-hero-margin-style').remove();

        // Generate CSS based on margin values
        let css = '';
        if (parseInt(desktopValue) > 0 || parseInt(mobileValue) > 0) {
            css = `
            .hero-section {
                margin-top: ${desktopMargin} !important;
                height: calc(100vh - ${desktopMargin}) !important;
            }
            .hero-video-container {
                height: calc(100vh - ${desktopMargin}) !important;
            }
            @media (max-width: 1023.98px) {
                .hero-section {
                    margin-top: ${mobileMargin} !important;
                    height: calc(45vh - ${mobileMargin}) !important;
                }
                .hero-video-container {
                    height: calc(45vh - ${mobileMargin}) !important;
                }
            }
            `;
        } else {
            css = `
            .hero-section {
                margin-top: 0 !important;
                height: 100vh !important;
            }
            .hero-video-container {
                height: 100vh !important;
            }
            @media (max-width: 1023.98px) {
                .hero-section {
                    height: 45vh !important;
                }
                .hero-video-container {
                    height: 45vh !important;
                }
            }
            `;
        }

        $('head').append('<style id="stylena-hero-margin-style">' + css + '</style>');
    }

    // Services Price Symbol
    wp.customize('stylena_services_price_symbol', function (value) {
        value.bind(function (newval) {
            // Update all price displays with new symbol
            $('.card-price span, .service-price span').each(function () {
                var $span = $(this);
                var priceText = $span.text();

                // Remove existing currency symbols at the beginning
                var cleanPrice = priceText.replace(/^[£$€¥₹¢₽₩₪₦₡₴₨₱₵₸₴₼₾]/, '');

                // Add new symbol
                if (cleanPrice.trim() !== '') {
                    $span.text(newval + cleanPrice);
                }
            });
        });
    });

})(jQuery);
