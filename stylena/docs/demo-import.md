# Demo Import

Stylena uses the **One Click Demo Import** (OCDI) plugin. Menu: Appearance > Import Demo Data.

## Before you start
- Use a fresh site. Importing twice creates duplicate pages, posts and menu items (the screen warns you when the demo was imported before).
- Install and activate **Stylena Core** first. The import stops with a message if the content types are missing.
- Appearance > Install Plugins can install OCDI and Contact Form 7 for you.

## What is imported
The import uses the files shipped in `demo/default/`: `content.xml` (pages, posts, services, team, portfolio, FAQs, sliders, menus, forms), `widgets.wie` (widgets) and `customizer.dat` (Customizer settings). After the content import the theme links the sample images to the demo posts and sets the hero poster and hero video. The import only changes content it created; your own posts and pages with the same titles are not modified.

## Demo variants
The import screen offers three demos: **Stylena Default**, **Stylena Light Minimal** (light backgrounds, dark text, Exo) and **Stylena Warm Rose** (rose and neutral palette, Aboreto headings). The two extra ones are style variants: each folder (`stylena-demo/light-minimal/`, `stylena-demo/warm-rose/`) holds only a `customizer.dat` (colours, bundled fonts, hero text, section toggles) and a `preview.jpg` palette card. They reuse the default content, widgets and sample media.
- On a fresh site a variant also imports the shared default content and widgets, so it works as a first import.
- Once the demo was imported (the importer records this), a variant imports **only its Customizer settings**: no pages, posts, menu items, widgets or media are duplicated. Switch between variants as often as you like; each overwrites the same set of settings.
- Variants never contain image or video URLs, API keys or consent opt-ins; those stay off.
- Importing Default after a variant does not reset the colours and fonts the variant set; use Customizer > Reset or change them by hand.

## External service (what is downloaded)
The sample photographs and the short hero video are AI-generated and are NOT in the theme download. During import they are downloaded from the demo server (`stylena.zylonex.com`, with a mirror path on the same host) into your Media Library: about 30 images and one looping video. Your server's IP address is visible to that host. This happens only when an administrator runs the import, never on normal page views. Replace the images and video with your own before launching. Provenance is listed in `demo/README.txt`.

## Offline or server unavailable
- If a file cannot be downloaded, the import continues without it and the theme shows its built-in neutral placeholder.
- After three failed downloads in a row the importer stops trying for that run.
- The import screen then shows a notice with the number of images that failed. Add your own images, or run the import again later on a fresh site.
- Developers can point the importer at their own host with the `stylena_demo_assets_base_urls` filter.

## After the import
- Edit or delete sample content from Pages, Services, Team Members, Portfolio Items, FAQs and Image Sliders.
- Replace the AI-generated images with your own photography.
- Review the Customizer, menus and the Contact Form 7 form text.
