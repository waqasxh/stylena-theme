# Portfolio

Show your best work on a Portfolio page and in a Portfolio section on the home page. Portfolio items are managed in the WordPress admin; the **Stylena Core** plugin must be active.

## Add portfolio items

1. Go to **Portfolio Items > Add Portfolio Item**.
2. Enter a title. The description (optional) is shown as a short caption.
3. Set the **Featured image** (the picture shown in the grid and the lightbox). Use a landscape image at least 1200 px wide.
4. Optional: choose one or more **Categories** (for example Before and After, Bridal). Categories become filter buttons on the Portfolio page when at least two are in use.
5. Optional: fill the item details box (project link, sort order). Lower sort numbers come first; items without a number follow, newest first.
6. Publish.

## Portfolio page

1. Create a page (for example "Portfolio").
2. In the page settings choose the template **Portfolio (Full - Width)**.
3. Publish. The page lists every published item. Extra items load with **Load More** (items per load: **Customize > Portfolio > Portfolio items shown first on the Portfolio page**).

If the Stylena Core plugin is not active the page still loads and shows a short notice instead of the grid.

## Home page section

The Portfolio section appears on the home page below the image slider (above Reviews and Need Advice) and only when at least one item exists. It is a slider with previous/next buttons: 3 items at a time on desktop, 2 on tablet, 1 on phone. A View All button links to the Portfolio page when it exists.

Open **Customize > Portfolio** to:
- show or hide the section,
- change its title and subtitle,
- set how many items it shows (1 to 12).

The section has the anchor `#portfolio`; the Team section has `#team`. The theme does not add menu links for you. To link to them, add a Custom Link under Appearance > Menus with the URL `/#portfolio` or `/#team` (use your home page address in front if the menu is shown on other pages).

## Lightbox and filters

- Clicking an image opens it large in a lightbox. It works with the keyboard: Esc closes, left and right arrows move between images, Tab stays inside the lightbox, and focus returns to the image you opened. Screen readers announce it as a dialog titled with the item name.
- Animations are turned off for visitors who prefer reduced motion.
- Turn the lightbox or the category filters off in **Customize > Portfolio**.

## Image sizes

The theme creates an 800 x 600 `stylena-portfolio` size for new uploads. Use the Regenerate Thumbnails plugin once if you want it for images uploaded earlier (the grid falls back to the original sizes meanwhile).

## Developers

- `stylena_get_portfolio_items( array( 'limit' => 6, 'category' => 'bridal' ) )` returns normalised cards; filter `stylena_portfolio_items`.
- `stylena_render_portfolio_grid( $items, $args )` prints the grid.
- Taxonomy `portfolio_category` is hierarchical and available in the REST API.
