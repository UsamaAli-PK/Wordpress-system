---
topic: Premium Addons Widget — Blog Post Grid
last_synced: 2026-11-20
widget_slug: premium-addon-blog
source_file: /root/Desktop/learn/BAse/workspace/test-subjects/plugins/premium-addons-for-elementor/premium-addons-for-elementor/widgets/premium-blog.php
---

# Widget: Blog Post Grid (`premium-addon-blog`)

Multi-layout, filterable blog loop with category tabs, pagination, infinite scroll, and marquee variants.

## Identity

| Field | Value |
| :--- | :--- |
| Elementor slug | `premium-addon-blog` |
| Elementor label | Blog |
| Class | `PremiumAddons\Widgets\Premium_Blog` |
| Source file | `widgets/premium-blog.php` |

## Asset dependencies

**CSS:** `premium-addons` aggregate + `premium-blog.css`.

**JS:** On frontend extends `elementorModules.frontend.handlers.Base` (confirmed via `assets/frontend/js/premium-blog.js`). Handler name `PremiumBlogHandler` managed through `paElementsHandler.isElementAlreadyExists('paBlog')` guard to prevent re-mount.

Vendor libs loaded conditionally:
- **Isotope** — when category filter is active
- No external library when using simple layout or marquee

Files:
- `assets/frontend/css/premium-blog.css` / `.min.css`
- `assets/frontend/js/premium-blog.js` / `.min.js`
- `assets/frontend/min-js/lib/isotope.pkgd.min.js` (conditionally enqueued)

## DOM structure (from JS selectors)

The handler expects this DOM shape:

```
.premium-blog-wrap
├── .premium-loading-feed            (loading state)
├── .premium-blog-filter             (filter container)
│   └── .premium-blog-filters-container
│       └── li a                     (filter links)
├── .premium-blog-content-wrapper
│   └── .premium-blog-post-outer-container  (one per post)
│       ├── .premium-blog-thumbnail
│       ├── .premium-blog-meta
│       │   └── .premium-blog-meta-separator
│       └── .premium-blog-content
└── .premium-blog-pagination-container
    └── .page-numbers.current
```

Preserve this DOM on extraction — the JS handler selectors are hardcoded.

## Control sections (18)

`start_controls_section` at lines 184, 364, 675, 851, 1037, 1204, 1351, 1640, 1747, 1819, 1854, 2032, 2220, 2282, 2343, 2477, 2532, 2644. Typical breakdown:

1. **Query** — post type, posts per page, offset, orderby, sticky handling
2. **Filters** — taxonomy filter tabs (category / tag / custom taxonomy)
3. **Layout** — grid / masonry / list / marquee / even columns
4. **Featured Image** — size, aspect ratio, hover effect
5. **Meta** — author, date, categories, comment count, reading time
6. **Title** — length, HTML tag
7. **Excerpt** — length, read more
8. **Pagination / Load More / Infinite scroll**
9. **Marquee options** — speed, direction, pause on hover
10. **Category tabs style**
11. **Post card style** — background, border, shadow
12. **Thumbnail style**
13. **Meta style**
14. **Title style**
15. **Excerpt style**
16. **Pagination style**
17. **Marquee style**
18. **Advanced** (standard Elementor tab)

Complex widget — ~2700 lines of control definitions.

## Custom controls used

- `Premium_Post_Filter` (PAE custom control) — post picker in Query section
- `Premium_Tax_Filter` — taxonomy picker
- `Premium_Background` — card background picker
- Elementor Group_Control_Typography, Group_Control_Box_Shadow, Group_Control_Border, Group_Control_Image_Size
- Kit globals (Global_Colors, Global_Typography)

## Rendering

- `render()` — builds `WP_Query` from settings, iterates posts, outputs DOM per structure above.
- Pagination / load-more routes through admin-ajax (handler: `wp_ajax_premium_blog_load_more` or similar — grep for exact action name).
- Filter clicks hit admin-ajax to replace content without reload.

## Public hooks fired

- `pa_blog_after_thumbnail` — inject custom content after each post thumbnail
- `pa_before_post_content` / `pa_after_post_content` — wrap card body
- Filters: `pa_post_excerpt`, `pa_post_tags` — override excerpt and tag list per card

## Extraction recipe

Classification: **🟡 moderate** — many control sections, Isotope dependency, admin-ajax handlers.

1. Copy `widgets/premium-blog.php` → `src/Widgets/BlogGrid.php` and rename class/slug.
2. Isotope: register your own `isotope-js` handle (CDN or bundled).
3. Copy the full `premium-blog.css` and its matching JS. Rename selectors with your prefix (massive find/replace — the DOM structure is ~30 classes).
4. **Admin-AJAX handlers**: grep the source for `wp_ajax_premium_blog_*` — copy those handler methods into your plugin core and rename action names.
5. **AJAX_Helper trait**: the handler relies on `PremiumAddons\Includes\Helpers\AJAX_Helper`. Lift this trait (trivial — it's a few AJAX wrapper methods).
6. **Custom controls** (Premium_Post_Filter, Premium_Tax_Filter, Premium_Background): either lift them or swap for Elementor's native `POST` and `SELECT2` controls (less rich but works).
7. **Fetch handler slug** in JS: change `frontend/element_ready/premium-addon-blog.default` to your slug.
8. **Isotope init**: handler calls Isotope on category click. Keep the init pattern.
9. Preflight: drag onto a page with posts → all filter clicks swap content, pagination changes page, marquee variant scrolls.

## Risks

- **Scale.** 2700-line widget. Per [`APPROACH.md`](../../APPROACH.md), do a shortest-path extraction: only lift the layout variants you need (e.g. drop marquee if not used, drop infinite scroll if load-more is enough).
- **Admin-AJAX vs REST.** Original uses `admin-ajax.php`. On extraction prefer REST endpoint `/wp-json/agency/v1/blog/load-more` — more modern, cacheable. See [`docs/02-plugins-and-data/hooks-actions-filters.md`](file:///root/Desktop/learn/BAse/docs/02-plugins-and-data/hooks-actions-filters.md).
- **DOM fragility.** The widget's JS handler uses hardcoded class selectors. Any DOM change must be matched in JS — follow the DOM Parity Rule from [`playbooks/03-section-to-elementor-widget.md`](file:///root/Desktop/learn/BAse/playbooks/03-section-to-elementor-widget.md).
- **Image sizes** — `Group_Control_Image_Size` generates `thumbnail`, `medium`, `large` + custom crops. Ensure `add_image_size()` in your theme or plugin matches.

## Interesting patterns to copy

- **Guard against double-mount** via `paElementsHandler.isElementAlreadyExists('paBlog')` — the pattern is worth keeping for any widget that has expensive init.
- **Loading state** via `.premium-loading-feed` overlay — good UX pattern.
- **Marquee variant** of a classic grid widget — unusual, interesting for scrollable showcases.
