# AI-Generated Content Disclosure

Product: Stylena theme, vendor Zylonex (https://zylonex.com). Applies to Envato AI-content policy review.

## What is AI-generated

- All demo photographs: hero poster, founder and team portraits, service, slider, portfolio, blog, membership card, map placeholder and FAQ/advice images (about 30 images).
- The hero background video (hero-video.mp4): four AI-generated stills animated locally with ffmpeg (zoom, dissolves). No third-party footage or music.
- The theme screenshot (screenshot.png), a capture of the demo that uses the AI images.

The people shown are synthetic and do not depict real individuals.

## Tool and dates

- Tool: Google Gemini image generation API (via the google-image-gen plugin; free tier reported by the tool; models named in demo/README.txt, for example gemini-3.1-flash-lite-image).
- Generated between 2026-10-22 and 2026-10-25 (per-file dates and prompts: stylena/demo/README.txt in the repository).
- Post-processing: local resizing, cropping and sharpening with Pillow; video assembly with ffmpeg.

## Distribution

- The images and the video are demo content hosted on the demo server (stylena.zylonex.com). They are excluded from the theme zip; the release build fails if img/demo is present.
- The only AI-generated file in the download is screenshot.png (marketplace preview only).
- The demo importer downloads the media only when an administrator runs it. Without it, the theme uses original SVG placeholders.
- Theme code, text and placeholder graphics are original work under GPL-2.0-or-later. The demo AI media is separate and not covered by that grant.

## Owner attestation (TO BE COMPLETED BY THE OWNER)

Nothing below has been confirmed. Do not submit until every blank is filled.

- Google Gemini terms URL reviewed: ____________________________
- Date accessed: ____________________
- Plan/tier used for generation: ____________________
- I confirm that commercial redistribution of the generated outputs (including use in a marketplace preview image) is permitted under those terms: [ ] yes  [ ] no
- Name: ____________________  Signature: ____________________  Date: ____________________
