# Customizer Guide

Open Appearance > Customize > **Stylena Theme Options**. Site title and logo are under Site Identity. Sections in this panel:

| Section | What it controls |
| --- | --- |
| Typography | Heading Font and Body Font; opt-in "Load other fonts from Google Fonts" |
| Colors | Primary and background colours, header/footer overrides (defaults keep the original design) |
| Hero Section | Hero text, Hero Video, Hero Poster Image |
| About Section | Homepage About block |
| Services Section | Homepage Services block (content from Services). Also holds **View All button text**, shared by the Services, Team and Portfolio View All buttons (empty = "View All") |
| Team Section | Title, subtitle, auto-scroll, number of members shown |
| Image Slider | Enable, height, autoplay, speed, arrows, dots |
| Advice Section | Homepage advice block |
| Reviews Section | Google Reviews: enable, "Allow contact with Google (Places API)" opt-in (off by default), API key, Place ID, count, title, fallback reviews |
| FAQ Section | Section title and image |
| Homepage Sections | Show or hide the Hero, About and Services sections (all on by default). Team, Reviews, Advice, FAQ and Slider have their own toggles in their sections. With Hero off, the page reserves space for the fixed header automatically. |
| Header | Logo text (the default gold logo text is darkened automatically on a light header; choose any other colour to override this), optional logo images (light, dark, mobile), WhatsApp and telephone links, booking button. The light logo replaces the Site Identity logo in the header and the mobile logo shows under 768px; the dark logo is stored for child themes (`stylena_get_logo_url( 'dark' )`) and is not shown by default. |
| Footer | Footer logo text and colours |
| Portfolio | Home page Portfolio section (the section title uses the Primary colour) (on/off, title, subtitle, item count), items shown first on the Portfolio page (1-50), lightbox on/off, category filters on/off |

Changes preview live. Only colours you change are written as CSS overrides, so resetting a value restores the default.

## Fonts
- Default for headings and body: **Montserrat**.
- **Exo** and **Aboreto** (and Montserrat) are bundled and self-hosted: no request to Google.
- Other choices in the list (for example Roboto, Playfair Display, Lora, Poppins, Cormorant Garamond, Dancing Script) and "System Fonts" are also selectable. Families that are not bundled load from Google Fonts only when you tick "Load other fonts from Google Fonts" (off by default). Enabling it sends visitor IP addresses to Google; check your privacy obligations.

## Google Reviews
Needs a Google Cloud API key with Places API enabled, your Place ID, and the "Allow contact with Google (Places API)" opt-in ticked. The opt-in is off by default: until you tick it, no request is sent to Google and the manual fallback reviews are shown. When on, your server sends its IP address, the Place ID and the API key to Google. If not configured or the call fails, the fallback reviews are shown. Results are cached about 12 hours; administrators can use the "Clear Reviews Cache" button in the Reviews Section. Keep the API key restricted to your domain.

## Images
Recommended sizes: services and portfolio 500x700 (5:7), team 400x600, hero 1920x1080. Run a thumbnail regeneration plugin after changing images.

## Advice section with a contact form
The Advice Section accepts rich text, so you can place a Contact Form 7 shortcode in its content field, for example `[contact-form-7 id="123" title="Booking Form"]` (use the shortcode shown in Contact > Contact Forms). The form must be published and Contact Form 7 active, otherwise the shortcode is shown as plain text. The section also has a title, subtitle, optional background image, text colour and overlay opacity.

## Custom CSS
Do not edit the theme files; updates would overwrite them. Use Appearance > Customize > Additional CSS for small changes, or a child theme for larger ones (see [Hooks](hooks.md) for the files a child theme can override). Colours set in the Colors section are written as overrides, so prefer changing them there. Breakpoints follow Bootstrap 4: 576, 768, 992 and 1200 px.

## Widget areas
The theme registers these widget areas under Appearance > Widgets: Sidebar left, Sidebar right, Header right, Navigation bar right, Footer left, Footer right, Homepage Hero and Homepage Services. Posts, pages and archives use a one, two or three column layout depending on which sidebars have widgets.
