---
topic: Premium Addons — Feature Extraction Index
last_synced: 2026-11-20
source_subject: /root/Desktop/learn/BAse/workspace/test-subjects/plugins/premium-addons-for-elementor/premium-addons-for-elementor
---

# Premium Addons — Feature Extraction Index

The **extraction map**. Every shipped feature is findable. Paths relative to the subject's inner directory. For each widget, see `widgets/<slug>.md` for a dedicated page (stub or deep-dive).

## Legend

| Column | Meaning |
| :--- | :--- |
| Feature | Elementor-facing name |
| Entry | Primary PHP file |
| Class | PHP class |
| Libs | Third-party JS the feature pulls in |
| Difficulty | 🟢 easy (single file, generic deps) · 🟡 moderate (vendor libs / API keys) · 🔴 hard (multi-system) |
| Page | Deep-dive doc |

---

## A. Elementor widgets (37, under `widgets/`)

| Slug | Elementor name | Entry | Class | Libs | Difficulty | Page |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| `premium-addon-fancy-text` | Animated Text | `widgets/premium-fancytext.php` | `Premium_Fancytext` | CSS keyframes, small JS | 🟢 | [widgets/premium-fancytext.md](widgets/premium-fancytext.md) |
| `premium-addon-banner` | Banner | `widgets/premium-banner.php` | `Premium_Banner` | CSS-only | 🟢 | widgets/premium-banner.md |
| `premium-addon-blog` | Blog Post Grid | `widgets/premium-blog.php` | `Premium_Blog` | Isotope (filtering) | 🟡 | [widgets/premium-blog.md](widgets/premium-blog.md) |
| `premium-addon-button` | Button | `widgets/premium-button.php` | `Premium_Button` | CSS-only | 🟢 | widgets/premium-button.md |
| `premium-addon-carousel` | Advanced Carousel | `widgets/premium-carousel.php` | `Premium_Carousel` | Swiper | 🟢 | widgets/premium-carousel.md |
| `premium-addon-contact-form` | Contact Form 7 | `widgets/premium-contactform.php` | `Premium_Contactform` | CF7 required | 🟡 | widgets/premium-contactform.md |
| `premium-addon-countdown` | Countdown | `widgets/premium-countdown.php` | `Premium_Countdown` | Luxon | 🟢 | widgets/premium-countdown.md |
| `premium-addon-counter` | Counter | `widgets/premium-counter.php` | `Premium_Counter` | CountUp.js | 🟢 | widgets/premium-counter.md |
| `premium-addon-dual-header` | Dual Header | `widgets/premium-dual-header.php` | `Premium_Dual_Header` | CSS-only | 🟢 | widgets/premium-dual-header.md |
| `premium-addon-grid` | Media Grid | `widgets/premium-grid.php` | `Premium_Grid` | Isotope, LightGallery | 🟡 | widgets/premium-grid.md |
| `premium-addon-icon-list` | Icon List | `widgets/premium-icon-list.php` | `Premium_Icon_List` | CSS-only | 🟢 | widgets/premium-icon-list.md |
| `premium-addon-image-button` | Image Button | `widgets/premium-image-button.php` | `Premium_Image_Button` | CSS-only | 🟢 | widgets/premium-image-button.md |
| `premium-addon-image-scroll` | Image Scroll | `widgets/premium-image-scroll.php` | `Premium_Image_Scroll` | CSS + small JS | 🟢 | widgets/premium-image-scroll.md |
| `premium-addon-image-separator` | Image Separator | `widgets/premium-image-separator.php` | `Premium_Image_Separator` | CSS-only | 🟢 | widgets/premium-image-separator.md |
| `premium-addon-lottie` | Lottie Animations | `widgets/premium-lottie.php` | `Premium_Lottie` | Lottie player | 🟡 | widgets/premium-lottie.md |
| `premium-addon-maps` | Google Maps | `widgets/premium-maps.php` | `Premium_Maps` | Google Maps / Mapbox APIs | 🟡 | widgets/premium-maps.md |
| `premium-addon-media-wheel` | Media Wheel | `widgets/premium-media-wheel.php` | `Premium_Media_Wheel` | Swiper | 🟡 | widgets/premium-media-wheel.md |
| `premium-addon-mobile-menu` | Mobile Menu | `widgets/premium-mobile-menu.php` | `Premium_Mobile_Menu` | Swiper for sub-menus | 🟡 | widgets/premium-mobile-menu.md |
| `premium-addon-modalbox` | Modal Box | `widgets/premium-modalbox.php` | `Premium_Modalbox` | CSS + small JS | 🟢 | widgets/premium-modalbox.md |
| `premium-addon-nav-menu` | Nav Menu | `widgets/premium-nav-menu.php` | `Premium_Nav_Menu` | CSS + small JS | 🟡 | widgets/premium-nav-menu.md |
| `premium-addon-notifications` | Notifications | `widgets/premium-notifications.php` | `Premium_Notifications` | CSS + small JS | 🟢 | widgets/premium-notifications.md |
| `premium-addon-person` | Person Info | `widgets/premium-person.php` | `Premium_Person` | CSS-only | 🟢 | widgets/premium-person.md |
| `premium-addon-pinterest-feed` | Pinterest Feed | `widgets/premium-pinterest-feed.php` | `Premium_Pinterest_Feed` | Pinterest API | 🟡 | widgets/premium-pinterest-feed.md |
| `premium-addon-post-ticker` | Post Ticker | `widgets/premium-post-ticker.php` | `Premium_Post_Ticker` | ticker JS | 🟡 | widgets/premium-post-ticker.md |
| `premium-addon-pricing-table` | Pricing Table | `widgets/premium-pricing-table.php` | `Premium_Pricing_Table` | CSS-only | 🟢 | widgets/premium-pricing-table.md |
| `premium-addon-progressbar` | Progress Bar | `widgets/premium-progressbar.php` | `Premium_Progressbar` | small JS | 🟢 | widgets/premium-progressbar.md |
| `premium-addon-search-form` | Search Form | `widgets/premium-search-form.php` | `Premium_Search_Form` | small JS | 🟢 | widgets/premium-search-form.md |
| `premium-addon-svg-drawer` | SVG Drawer | `widgets/premium-svg-drawer.php` | `Premium_Svg_Drawer` | ScrollMagic? (TBD) | 🟡 | widgets/premium-svg-drawer.md |
| `premium-addon-tcloud` | Tag Cloud (3D) | `widgets/premium-tcloud.php` | `Premium_Tcloud` | TagCanvas | 🟡 | widgets/premium-tcloud.md |
| `premium-addon-testimonials` | Testimonials | `widgets/premium-testimonials.php` | `Premium_Testimonials` | Swiper | 🟢 | widgets/premium-testimonials.md |
| `premium-addon-textual-showcase` | Textual Showcase | `widgets/premium-textual-showcase.php` | `Premium_Textual_Showcase` | CSS + JS | 🟡 | widgets/premium-textual-showcase.md |
| `premium-addon-tiktok-feed` | TikTok Feed | `widgets/premium-tiktok-feed.php` | `Premium_Tiktok_Feed` | TikTok oEmbed | 🟡 | widgets/premium-tiktok-feed.md |
| `premium-addon-title` | Styled Title | `widgets/premium-title.php` | `Premium_Title` | CSS-only | 🟢 | widgets/premium-title.md |
| `premium-addon-videobox` | Video Box | `widgets/premium-videobox.php` | `Premium_Videobox` | HTML5 video | 🟢 | widgets/premium-videobox.md |
| `premium-addon-vscroll` | Vertical Scroll | `widgets/premium-vscroll.php` | `Premium_Vscroll` | small JS | 🟡 | widgets/premium-vscroll.md |
| `premium-addon-weather` | Weather | `widgets/premium-weather.php` | `Premium_Weather` | OpenWeather API | 🟡 | widgets/premium-weather.md |
| `premium-addon-world-clock` | World Clock | `widgets/premium-world-clock.php` | `Premium_World_Clock` | Luxon | 🟢 | widgets/premium-world-clock.md |

Shared widget assets live under `assets/frontend/` (CSS) and `assets/frontend/js/` (JS). Common vendor libs are in `assets/frontend/min-js/lib/`.

Deep-dive pages exist for `premium-fancytext` and `premium-blog` as templates. Fill others on demand using the same template structure.

---

## B. Global addons (`addons/`)

These apply to **all** Elementor elements, not individual widgets. Each is a single file that hooks `elementor/element/after_section_end` (or similar) to inject controls.

| Feature | Entry | Class | Deps | Difficulty | Notes |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **Display Conditions** | `addons/display-conditions.php` | `Display_Conditions` | engine at `includes/pa-display-conditions/` | 🔴 | Separate proprietary conditions engine |
| **Equal Height** | `addons/equal-height.php` | `Equal_Height` | small JS | 🟢 | Flex/Grid-based equal height sibling columns |
| **Floating Effects** | `addons/floating-effects.php` | `Floating_Effects` | CSS animations + JS | 🟢 | Blur, brightness, contrast, grayscale, hue, saturation overlay effects on hover/scroll |
| **Liquid Glass** | `addons/liquid-glass.php` | `Liquid_Glass` | CSS `backdrop-filter` | 🟢 | iOS-style frosted backdrop; modern browsers only |
| **Shape Divider** | `addons/shape-divider.php` | `Shape_Divider` | SVG + CSS | 🟢 | Native-style shape dividers above/below any container; extensible via `pa_divider_custom_svg` |
| **Shapes** | `addons/shapes.php` | `Shapes` | SVG | 🟢 | Background shape layer |
| **Tooltips** | `addons/tooltips.php` | `Tooltips` | small JS | 🟢 | Add tooltip controls to any element |
| **Wrapper Link** | `addons/wrapper-link.php` | `Wrapper_Link` | small JS | 🟢 | Make any element a link without nested `<a>` |

---

## C. Custom Elementor controls (`includes/controls/`)

Reusable Elementor controls other widgets can use.

| Control | Entry | Class | Use case |
| :--- | :--- | :--- | :--- |
| Background picker | `controls/premium-background.php` | `Premium_Background` | Richer background picker than Group_Control_Background |
| Searchable Select | `controls/premium-select.php` | `Premium_Select` | Native WP select2-style control |
| Post Filter | `controls/premium-post-filter.php` | `Premium_Post_Filter` | Pick posts/CPTs via live search |
| Taxonomy Filter | `controls/premium-tax-filter.php` | `Premium_Tax_Filter` | Pick taxonomy terms |
| ACF Selector | `controls/premium-acf-selector.php` | `Premium_Acf_Selector` | Pick an ACF field |
| Image Chooser | `controls/pa-image-choose.php` | `PA_Image_Choose` | Image-first chooser |

All six are useful standalone — see [`05-extraction-guide.md`](05-extraction-guide.md) §Custom controls.

---

## D. Admin surface (`admin/includes/`)

| Feature | File | Class | Notes |
| :--- | :--- | :--- | :--- |
| Admin bar integration | `admin-bar.php` | `Admin_Bar` | Top-bar menu for PAE |
| Admin orchestrator | `admin-helper.php` | `Admin_Helper` | Exposes `get_enabled_elements()` + `get_integrations_settings()` |
| Notice framework | `admin-notices.php` | `Admin_Notices` | Review prompts + snooze logic |
| Beta tester enrollment | `beta-testers.php` | `Beta_Testers` | Opt-in to beta channel |
| Template duplicator | `duplicator.php` | `Duplicator` | Clone Elementor templates |
| Elements toggler | `elements.php` | `Elements` | Enable/disable individual widgets+addons in bulk |
| Feedback / review | `feedback.php` | `Feedback` | Review prompt |
| **API Keys manager** | `keys.php` | `Keys` | Stores Mapbox, Google Maps, OpenWeather, Lottie, Pinterest, TikTok keys centrally |
| Rollback | `pa-rollback.php` | `Pa_Rollback` | Downgrade to previous version |
| Promo pointer | `promotion-pointer.php` | `Promotion_Pointer` | In-admin promo hints — strip |
| MCP client logos | `mcp-client-logos.php` | — | Visual logo library for MCP admin UI |
| MCP news | `mcp-news.php` | — | News feed for MCP tab |
| MCP settings | `mcp-settings.php` | `MCP_Settings` | MCP config page |
| **Setup wizard** | `setup-wizard/` (main-view.php, elements.php, content-views/) | — | First-install guided wizard |
| **MCP UI partials** | `templates/mcp/*.php` × 11 files | — | Modular MCP admin screens |

Difficulty: 🟡 per module, mostly self-contained. Strip Pro upsell UI on extraction.

---

## E. MCP server (`includes/abilities/`)

The novel capability. See [`02-architecture.md`](02-architecture.md) §7 and `05-extraction-guide.md` §MCP server.

| Feature | File |
| :--- | :--- |
| Bootstrap | `abilities/bootstrap.php` (`Bootstrap` class) |
| Route detector | `abilities/route-detector.php` |
| Elementor MCP tools | `abilities/elementor-mcp.php` |
| Connection log | `abilities/connection-log.php` |
| Helpers | `abilities/helpers.php`, `input-normalizer.php` |
| Dashboard tools | `abilities/dashboard/disable-unused-widgets.php`, `get-settings.php` |
| Ability registry | `abilities/Registry/Ability_Registry.php` |
| OAuth store | `abilities/OAuth/Store.php` (static `gc()` method) |
| Bundled MCP SDK | `abilities/vendor/` (autoload_packages.php + autoload.php) |

Difficulty: 🔴 whole system — but if the destination is a site that **already** has WordPress 6.9+ Abilities API, the bundled MCP SDK and bootstrap pattern are directly reusable.

---

## F. Display Conditions engine (`includes/pa-display-conditions/`)

| Feature | File |
| :--- | :--- |
| Controls handler | `pa-controls-handler.php` — appends Display Conditions tab to every element |
| Language/locale conditions | `lang-locale.php` |
| Condition classes | `conditions/*.php` (one file per condition type — user role, device, URL, date, ACF, cookie, query var, cart contents, logged-in state, referrer, geolocation) |

Difficulty: 🔴 whole engine / 🟡 single condition. Reusable as a standalone Elementor extension plugin.

---

## G. Helpers (`includes/helpers/`)

| Helper | File | Purpose |
| :--- | :--- | :--- |
| ACF | `acf-helper.php` | Resolve ACF fields safely |
| AJAX | `ajax-helper.php` | Trait used by widgets for live AJAX |
| Device detector | `device-detector.php` | Server-side UA detection |
| Document helper | `document-helper.php` | Elementor document utilities |
| Element transfer | `element-transfer.php` | Export elements to transferable payload |
| Nav menu walker | `pa-nav-menu-walker.php` | Custom walker for mobile menu widget |
| Papro promotion | `papro-promotion.php` | Pro-upsell slot renderer — strip |
| Query helper | `query-helper.php` | Common WP_Query builders |
| Template bundle | `template-bundle.php` | Bundle templates for cross-site transfer |
| Widget class map | `widget-class-map.php` | Slug → widget class registry |
| Helper_Functions | `helper-functions.php` | Grab-bag of common helpers (version checks, URLs, campaign links) |

---

## H. Extras (`includes/extras/`)

| Feature | File |
| :--- | :--- |
| Angie AI integration | `angie-integration.php` |
| Contact Form 7 inserter | `cf7-inserter.php` |
| Cross-site copy-paste | `cross-copy-paste.php` |
| Live editor | `live-editor.php` |
| Live editor modal | `live-editor-modal.php` |

---

## I. Module: WooCommerce (`modules/woocommerce/`)

Dedicated subsystem for Woo-specific widgets and integrations. Hook catalog is in [`03-hooks-filters.md`](03-hooks-filters.md) §2 → "WooCommerce widgets".

---

## J. Core services (`includes/`)

| Service | File | Class |
| :--- | :--- | :--- |
| Plugin core | `class-pa-core.php` | `PA_Core` |
| Addons integration | `addons-integration.php` | `Addons_Integration` |
| Assets manager | `assets-manager.php` | `Assets_Manager` |
| Module base class | `module-base.php` | `Module_Base` |
| Template tags | `premium-template-tags.php` | Procedural `premium_*` tags |
| Template loader | `templates/` | Bundled Elementor templates |

---

## K. Templates library (`includes/templates/`)

Pre-built Elementor sections and pages shipped with PAE. Inserted via the admin UI or cross-site paste.

---

## L. Novel concepts worth calling out

| Concept | Where | Why interesting |
| :--- | :--- | :--- |
| **Bundled MCP server** | §E | Full MCP implementation for Elementor control via AI — reusable reference implementation of `docs/05-mcp-and-cli/elementor-mcp-workflow.md` |
| **Per-post asset scanning** | `assets-manager.php` | Scans post content, caches which widgets appear, only enqueues their assets. Clean way to avoid asset bloat. |
| **On-demand editor libs** | `addons-integration.php` constants | Isotope/Lottie/Luxon loaded in Elementor editor preview only when needed |
| **Setup wizard** | `admin/includes/setup-wizard/` | First-install guided experience — modern UX pattern for free plugins |
| **Centralized API keys** | `admin/includes/keys.php` | Single admin screen for Mapbox/Weather/Google Maps/Pinterest/TikTok/Lottie keys with global filters per service |
| **Proprietary display conditions** | §F | More powerful than Elementor Pro's native conditions, decoupled from Pro |
| **Rollback button** | `admin/includes/pa-rollback.php` | In-admin downgrade to previous version |
| **Cross-site element transfer** | `includes/helpers/element-transfer.php` + `cross-copy-paste.php` | Serialise an Elementor element with its dependencies for paste into another site |
| **OAuth-backed MCP** | `abilities/OAuth/` | OAuth flow dedicated to MCP connections, daily cron cleanup |
| **Review prompt snooze system** | `admin/includes/admin-notices.php` + `feedback.php` | Non-aggressive review prompting — 7-day initial quiet period, snooze option |
| **Pro label filter** | `pa_pro_label` filter | Pro upsell badges rendered through a filter, so they can be stripped cleanly when extracting |

See [`05-extraction-guide.md`](05-extraction-guide.md) for recipes.
