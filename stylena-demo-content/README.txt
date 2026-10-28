Stylena demo data (demo/default)
================================

Files
-----
content.xml     WordPress eXtended RSS (WXR 1.2): pages, posts, services,
                team, portfolio, FAQ, slider slides, navigation menus and two
                Contact Form 7 forms. No media attachments, no featured images.
customizer.dat  Customizer Export/Import format (serialized PHP). Text, colour
                and toggle settings only. No image/video URLs, no API keys.
widgets.wie     Widget Importer & Exporter JSON (core and Stylena widgets).

How these files were generated
------------------------------
They are produced by scripts/demo-build.php in the development repository
(not shipped in the package):

    php scripts/demo-build.php

The script is deterministic and refuses to finish if the output contains
non-example.com e-mail addresses or URLs, scripts, iframes, forms or
attachments. Do not edit the generated files by hand; edit the script.

Licences
--------
All text in these files was written for Stylena and is released under
GPL-2.0-or-later, the same licence as the theme.
No images, video, fonts or other third-party assets are included in the demo
data. Where an image is expected, the theme falls back to the placeholder
graphics in img/placeholders/ (original artwork, GPL-2.0-or-later).
Names, addresses, phone numbers and e-mail addresses are fictional
(example.com, +1 (000) 000-0000).

Notes
-----
* Import on a fresh site. Importing twice creates duplicate content.
* The Contact Form 7 mail sender is wordpress@example.com; change the
  "From" address in each form to an address on your own domain.
* Pages contain the placeholders __CF7_CONTACT__ and __CF7_MEMBERSHIP__ in
  their Contact Form 7 shortcodes; the One Click Demo Import after-import
  step replaces them with the real form IDs.

AI-generated images (provenance log,
-------------------------------------------
Files live in stylena/img/demo/ in the development repository. They are NOT shipped in the theme download (the release build excludes img/demo and fails if it is present); the demo importer downloads them from the demo server (stylena_demo_assets_base_url()). Each entry: file | tool and model | date | prompt | licence basis.

* founder-portrait.jpg | Google Gemini image generation API (via the google-image-gen plugin) | 2026-10-22 |
  "Professional studio headshot of a confident beauty salon founder and business owner, warm and approachable
  expression, soft neutral studio background in warm gray tones, natural soft lighting, business-casual attire
  appropriate for a beauty industry professional. Square crop, subject centered, shoulders-up. Photography
  style, not illustration. No text, no logos, no jewellery brand marks." (1:1, converted PNG to JPG q85) |
  AI-generated synthetic person, a stand-in only; commercial-use basis: Google Gemini API terms; the tool reported the free tier
  (Google does not claim ownership of generated outputs; plan and tier to be confirmed by the author). Not a real person.

* hero-poster.jpg | Google Gemini image generation API (google-image-gen plugin) | 2026-10-22 | "A single still frame: interior of a modern luxury beauty salon, warm golden-hour lighting, neutral gray and cream tones with gold accents, soft bokeh in the background, marble countertop with a small gold-rimmed mirror and a candle in the foreground. Calm, minimal, high-end editorial photography style. No people, no text, no logos." (16:9, 1376x768, JPG q72) | AI-generated, no people; same licence basis as above. Below the 1920x1080 spec: upscale or regenerate before release.
* advice-consultation.jpg | same tool | 2026-10-22 | "A beauty consultant and client sitting together at a salon consultation desk, looking at a tablet or color swatch book, warm natural light, soft neutral tones with gold accent details, candid but polished editorial style, both subjects relaxed and smiling softly. Shot from a slight side angle, shallow depth of field. No visible text, logos, or screens with readable content." (5:4, 1152x928, JPG q82) | AI-generated synthetic people, stand-ins only.
* faq-salon-corner.jpg | same tool | 2026-10-22 | "A vertical portrait-orientation photo of a relaxed salon interior corner - a styling chair, a small gold-framed mirror, soft directional light, warm neutral palette with gold accents, minimal styling tools laid out neatly on a counter. Calm, high-end editorial beauty-brand photography. No people, no text." (3:4, 896x1200, JPG q82) | AI-generated, no people.

Wiring: the demo importer (stylena_ocdi_after_import) sets stylena_about_founder_image, stylena_hero_poster,
stylena_advice_image and stylena_faq_section_image to these files. Theme defaults stay neutral SVG placeholders.

* service-hairdressing / service-coloring / service-hair-care / service-manicure-pedicure / service-makeup-facial /
  service-eyebrow-care .jpg and team-hair-stylist / team-esthetician / team-nail-technician / team-makeup-artist /
  team-extension-specialist / team-salon-manager .jpg | Google Gemini image generation API (google-image-gen plugin) |
  2026-10-22 | prompts as in MEDIA-GENERATION-PROMPTS.md sections 6 and 7 (dev doc, not shipped), each with the suffix
  "No text, no logos, no brand names." (3:4 aspect, JPG q82; the 5:7 service and 2:3 team ratios are not offered by the
  tool, crop in the theme's image sizes) | AI-generated synthetic people, stand-ins only; same licence basis as above.
  Not yet attached to demo content (see.

* slider-reception.jpg | slider-hair-styling.jpg | slider-nail-studio.jpg | Google Gemini image generation API (google-image-gen plugin) | 2026-10-22 | prompts as in MEDIA-GENERATION-PROMPTS.md section 9, each with the suffix "No people faces in sharp focus, no text, no logos." (16:9, 1376x768, JPG q78; below the 1920x1080 spec, see | AI-generated; same licence basis as above. Used as featured images of the demo slider slides.

* slider-spa-room.jpg | slider-makeup-studio.jpg | slider-grooming-corner.jpg | Google Gemini image generation API (google-image-gen plugin, free tier, model gemini-3.1-flash-lite-image) | 2026-10-24 | 16:9 prompts: calm massage/spa treatment room; bright makeup studio with lit vanity mirror; barber and grooming corner, each with the suffix "No people faces in sharp focus, no text, no logos."; generated at 1376x768, then upscaled locally with Pillow to 1920x1080 (Lanczos, light unsharp mask, JPG q84) | AI-generated; same licence basis as the other slider images (pending the Gemini/Envato confirmation). Used as featured images of the three added demo slider slides (Relax and Restore, Makeup and Beauty, Grooming Corner).

* portfolio-balayage / portfolio-updo / portfolio-bridal / portfolio-manicure / portfolio-facial / portfolio-barber .jpg | Google Gemini image generation API (google-image-gen plugin, free tier) | 2026-10-23 | prompts as in MEDIA-GENERATION-PROMPTS.md section 8 (one result type each), with the suffix "Editorial 'after' shot style, warm neutral lighting, shallow depth of field, gold and gray color grading consistent with a luxury salon brand. No visible text, no logos, no brand names." (3:4, 896x1200, JPG q82; spec says 5:7, crop in theme) | AI-generated synthetic people, stand-ins only; plan terms still to be confirmed. portfolio-manicure shows a champagne glass; swap if alcohol imagery is unwanted.

Wiring: stylena_ocdi_after_import() step 4b attaches the service, team, portfolio and slider images to the imported posts by title (stylena_demo_attach_image()).

* membership-card.jpg | map-placeholder.jpg | blog-skincare.jpg | blog-massage.jpg | blog-shelf.jpg | Google Gemini image generation API (google-image-gen plugin, free tier) | 2026-10-23 | prompts as in MEDIA-GENERATION-PROMPTS.md sections 10, 11 and 12 (colour hex codes and the 3:2 ratio dropped because the tool only offers 4:3 / 16:9, then centre-cropped; membership-card 1200x800, map-placeholder 1600x900, blog-* 1200x800, JPG q70-78, each under 150KB) | AI-generated; blog-massage shows a person in soft focus. Plan terms still to be confirmed. Wired: membership-card and map-placeholder are inserted into the Membership and Contact pages (content.xml, __THEME_URI__ token replaced by the importer, step 3b); blog-* become featured images of the three demo posts (step 4b). Not shipped: the unused manicure blog image and the OG share image were dropped (SEO plugins own OG tags).

* hero-video.mp4 | derived locally with ffmpeg (imageio-ffmpeg 7.1 build) from hero-poster.jpg (AI-generated, see above) | 2026-10-23 | slow zoom/drift (zoompan 1.00 -> 1.07), played forward then reversed for a seamless 24 s loop, 1920x1080 H.264 CRF 27, no audio, 1.8 MB. No third-party footage or music. Same licence basis and hosting rule as the images (served from the demo server, not in the zip).

* hero-poster.jpg | slider-hair-styling.jpg | slider-nail-studio.jpg | slider-reception.jpg (upscaled) | processed locally with Pillow (Lanczos resize to 1920x1080, centre-crop from 1376x768 sources, light unsharp mask, JPG q84) | 2026-10-23 | no new generation; sources unchanged (AI-generated, see above). hero-video.mp4 was rebuilt from the upscaled hero-poster.jpg. Real extra detail was not added, only resolution; regenerate at native 1920x1080 if the generator allows. The demo-assets folders on the demo server need the new files.

* demo/light-minimal/ and demo/warm-rose/ (customizer.dat, preview.jpg) | derived 2026-10-23 by a one-off local PHP script from demo/default/customizer.dat (palette, bundled fonts exo/aboreto/montserrat, hero text and section toggles changed; scripts/demo-build.php not run) | original work, no third-party material, no image/video/API-key/opt-in mods. preview.jpg (600x450) are original palette cards drawn with Pillow (variant name and colour swatches, plain system font); they are not screenshots. Content, widgets and media are reused from demo/default.

* hero-video.mp4 (replaced 2026-10-25) | 4 stills generated with Google Gemini image generation API (google-image-gen plugin, free tier, model gemini-3.1-flash-lite-image) from the "Prompt A" editorial montage in .wpstate/HERO-VIDEO-PROMPT.md (hair from behind, gloved hands brushing wet hair, jewellery and lipstick on marble, brass mirror; no front-facing faces), animated locally with ffmpeg (imageio-ffmpeg 7.1: zoompan 1.00 -> 1.08, 1 s xfade dissolves, final pull-back so the loop is seamless), 15 s, 1920x1080, 30 fps, H.264 crf 26, no audio, 2.2 MB | licence basis pending (Gemini terms / Envato AI-content) and | Inspired only by the style of third-party sites; no third-party footage used or recreated.
