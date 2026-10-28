# Stylena hooks and extension points

Use these from a child theme or plugin. All names are prefixed `stylena_`.

## Actions

| Hook | Where | Notes |
|------|-------|-------|
| `stylena_before_header` | header.php | Before the `<header>` element. |
| `stylena_after_header` | header.php | After the `</header>` element. |
| `stylena_before_footer` | footer.php | Before the footer markup (after the FAQ section). |
| `stylena_after_footer` | footer.php | After the footer markup, before `wp_footer()`. |
| `stylena_before_front_page_sections` | front-page.php | Start of `<main>` on the homepage. |
| `stylena_after_front_page_sections` | front-page.php | End of `<main>` on the homepage. |
| `stylena_migrate( $from, $to )` | inc/functions/extensibility.php | Runs on `admin_init` only when the stored `stylena_db_version` differs from `STYLENA_DB_VERSION`. |

## Filters

| Filter | Default | Purpose |
|--------|---------|---------|
| `stylena_font_families` | font key => CSS family map | Add or change font stacks. |
| `stylena_font_choices` | key => label map | Customizer font dropdown options. |
| `stylena_hero_classes` | `hero-section position-relative` | Class string on the hero `<section>`. |
| `stylena_hero_cta_classes` | `['primary' => 'btn btn-primary hero-btn', 'secondary' => 'btn btn-outline-light hero-btn']` | Hero button classes. |
| `stylena_section_enabled( $enabled, $section )` | Customizer toggle | Force a homepage section (hero, about, services) on or off. |
| `stylena_logo_url( $url, $variant )` | Customizer value | Header logo variant URL (light, dark, mobile). |
| `stylena_content_width` | existing | Content width. |

## Child theme asset overrides

The theme enqueues its own CSS and JS through `get_theme_file_uri()`, so a child theme can override any of these by shipping a file at the same relative path: `css/custom.css`, `css/responsive.css`, `css/fonts.css`, `css/live-search.css`, `js/custom.js`, `js/live-search.js`, `js/hero-margins.js`, `assets/css/main.css`, `assets/js/main.js`, and the vendored Bootstrap and Font Awesome files. Without a child theme the URLs are unchanged.

## Creating a child theme

`style.css` needs `Theme Name`, `Template: stylena` and a `Version`. Enqueue the parent stylesheet in the child `functions.php`:

```php
add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'stylena-parent', get_template_directory_uri() . '/style.css', array(), wp_get_theme( 'stylena' )->get( 'Version' ) );
} );
```

Copy a template file into the child theme to override it.

## Database version

`stylena_db_version` (option, integer, currently 1) records the migration level. Migrations run only in the admin; the frontend never writes it.

## Homepage section toggles

Customizer: Stylena > Homepage Sections (Hero, About, Services; default on). Team, Reviews, Advice, FAQ and Slider keep their existing toggles. Section ordering controls are not available yet.

## Header logo variants

Customizer: Stylena > Header. Light replaces the Site Identity logo in the header; Mobile is used below 768px; Dark is exposed through `stylena_get_logo_url( 'dark' )` for child themes. Empty means the previous behaviour.
