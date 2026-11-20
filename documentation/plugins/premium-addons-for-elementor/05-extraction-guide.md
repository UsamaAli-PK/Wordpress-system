---
topic: Premium Addons — Feature Extraction Guide (Recipes)
last_synced: 2026-11-20
source_subject: /root/Desktop/learn/BAse/workspace/test-subjects/plugins/premium-addons-for-elementor/premium-addons-for-elementor
---

# Premium Addons — Feature Extraction Guide

Per-feature recipes. Applies [`playbooks/02-extract-isolate-merge.md`](file:///root/Desktop/learn/BAse/playbooks/02-extract-isolate-merge.md), [`03-section-to-elementor-widget.md`](file:///root/Desktop/learn/BAse/playbooks/03-section-to-elementor-widget.md), [`04-build-unified-theme.md`](file:///root/Desktop/learn/BAse/playbooks/04-build-unified-theme.md).

## Shared moves (apply to every PAE extraction)

1. **Namespace.** `PremiumAddons\…` → `Agency\Companion\Extracted\PAE\…`.
2. **Widget class slug.** Rename `Premium_Fancytext` → `Agency_Fancytext` (or keep inside your plugin's naming). Change the public widget slug returned by `get_name()` to a prefixed slug (`agency-animated-text`), otherwise it collides with PAE if both plugins ever run together.
3. **Style/script handles.** All PAE handles are `pa-*` or `premium-addons`. Rename to `agency-*`.
4. **Text domain.** `premium-addons-for-elementor` → your plugin's domain.
5. **Pro upsell.** Strip `papro-promotion.php` includes, `pa_pro_label` filter outputs, and any `<span class="papro">` markup.
6. **Admin shell.** Standalone widgets don't need `Admin_Helper::get_enabled_elements()` gating — collapse to always-on or add your own on/off option.
7. **Assets_Manager dependency.** PAE widgets depend on `Assets_Manager` for enqueue. When extracting a single widget, replace with a straightforward `wp_register_*` + `get_style_depends()`/`get_script_depends()` per [`docs/04-elementor-widgets/widget-assets-and-js-handlers.md`](file:///root/Desktop/learn/BAse/docs/04-elementor-widgets/widget-assets-and-js-handlers.md).
8. **Custom controls.** If the widget uses a PAE custom control (`Premium_Background`, `Premium_Select`, etc.), either lift the control too or swap for a standard Elementor equivalent.
9. **Modernize PHP.** Add `declare(strict_types=1)` + type hints. PAE's PHP 7.4 baseline is acceptable; bump to 8.1+ for new code.
10. **Verify.** Drag widget into Elementor editor, confirm live preview works, confirm front-end renders, confirm nothing in the console complains.

---

## §Any single widget (template — 🟢 for CSS-only widgets)

For a widget like Animated Text / Button / Dual Header / Icon List / Image Separator / Pricing Table / Title etc. (no vendor libs):

1. Copy `widgets/<slug>.php` into your plugin's `src/Widgets/` and apply the shared moves.
2. Grep the widget file for `wp_enqueue_style` / `wp_enqueue_script` references. Each handle has a `assets/frontend/css/<slug>.css` and sometimes `assets/frontend/js/<slug>.js`. Copy them.
3. Register your assets with `wp_register_*` in your addon bootstrap:
   ```php
   wp_register_style('agency-fancy-text', plugins_url('assets/css/fancy-text.css', __FILE__), [], '1.0.0');
   wp_register_script('agency-fancy-text', plugins_url('assets/js/fancy-text.js', __FILE__), ['elementor-frontend'], '1.0.0', true);
   ```
4. Update `get_style_depends()` / `get_script_depends()` in the widget class to your new handles.
5. If the widget uses `Premium_Background` or another PAE custom control, swap to Elementor's `Group_Control_Background` or lift the custom control (see §Custom controls).
6. If the widget uses Elementor Kit globals (`Global_Colors`, `Global_Typography`) — keep them unchanged, they work in any Elementor install.
7. Test: `php -l` the widget file, drag into a page, check rendering + live preview.

Deep-dive examples in [`widgets/premium-fancytext.md`](widgets/premium-fancytext.md) and [`widgets/premium-blog.md`](widgets/premium-blog.md).

---

## §Widget with vendor JS library (template — 🟡)

For widgets using Swiper / Isotope / Lottie / Luxon / CountUp / LightGallery:

Extra steps on top of §Any single widget:

1. **Do not** copy PAE's bundled minified vendor library. Either:
   - Register vendor via CDN or your plugin's own `node_modules`/`vendor-assets/`.
   - Use WordPress-registered scripts if already present (`wp-jquery`, `imagesloaded`, etc.).
2. Declare vendor as a script dep in your registration:
   ```php
   wp_register_script('agency-grid-handler', ..., ['elementor-frontend', 'swiper'], '1.0.0', true);
   ```
3. Rewire the JS handler to extend `elementorModules.frontend.handlers.Base` per [`docs/04-elementor-widgets/widget-assets-and-js-handlers.md`](file:///root/Desktop/learn/BAse/docs/04-elementor-widgets/widget-assets-and-js-handlers.md). PAE's existing handlers mostly do this — grep `elementor/frontend/init` in the JS files to confirm.
4. For API-backed widgets (Weather, Pinterest, TikTok, Maps), see §API widget below.

---

## §API-backed widget (API-key dependent — 🟡)

For Weather, Pinterest Feed, TikTok Feed, Google Maps, Mapbox Maps, Post Ticker (stocks), Pricing Currency:

Extra steps:

1. **Lift the API endpoint abstraction**, not PAE's `Keys` admin screen (unless you want to copy the whole central key manager — see §API Keys manager).
2. Expose the API key as a plugin option or widget-level control. Example:
   ```php
   $this->add_control('weather_api_key', [
       'label' => 'OpenWeather API Key',
       'type'  => Controls_Manager::TEXT,
       'description' => '<a href="https://openweathermap.org/api" target="_blank">Get a key</a>',
   ]);
   ```
3. Keep the API endpoint call inside a short method and route it through the filter PAE already provides (e.g. `pa_weather_api`, `pa_stock_api`) — rename to `agency_weather_api` etc.
4. Cache responses via `set_transient` so you don't hammer the API on every page load.
5. Comply with each API's ToS when redistributing (TikTok/Pinterest tokens must be user-scoped; Google Maps requires billing enabled).

---

## §Custom controls (🟢)

For any of the six custom controls in `includes/controls/`:

1. Copy the control PHP file into your plugin's `src/Elementor/Controls/`.
2. Remove the `PremiumAddons\Includes\Controls` namespace; add your own.
3. Rename the control type string returned by `get_type()` to a non-colliding name (e.g. `agency_background`).
4. Register the control in `elementor/controls/register`:
   ```php
   add_action('elementor/controls/register', function($controls_manager) {
       $controls_manager->register(new Agency_Background());
   });
   ```
5. If the control has editor JS (grep `assets/editor/js/` for the control slug), copy that too and enqueue on `elementor/editor/before_enqueue_scripts`.
6. Test: use the control in a dummy widget, confirm the UI appears and values round-trip.

---

## §Shape Divider addon (🟢)

**Source:** `addons/shape-divider.php` + SVG shapes in `assets/`.

1. Copy the file, rename class/namespace.
2. The addon hooks `elementor/element/after_section_end` to inject a "Shape Divider" control section into every container/column.
3. Copy the SVG asset library it ships (grep `pa_divider_custom_svg` to find where shapes are loaded).
4. The shape dividers are rendered via a template partial that outputs inline SVG on top of the section. Lift the partial.
5. Keep the `pa_divider_custom_svg` extension filter — rename to `agency_divider_custom_svg` — so your own future shapes stay pluggable.

---

## §Floating Effects addon (🟢)

**Source:** `addons/floating-effects.php` + `assets/frontend/css/floating-effects.css` + `assets/frontend/js/floating-effects.js`.

1. The addon adds six CSS filter controls (blur/brightness/contrast/grayscale/hue/saturation) + optional scroll-tied animation.
2. Copy class, port the six hook triggers (`pa_floating_blur_controls` etc.) to your namespace.
3. Lift the CSS + JS. JS is lightweight — uses IntersectionObserver.
4. Test on a sibling element: enable "Floating effects" in the Advanced tab, set a blur delta on hover, watch it apply.

---

## §Liquid Glass addon (🟢)

**Source:** `addons/liquid-glass.php` + CSS.

1. Pure CSS `backdrop-filter: blur()` with transparency layering.
2. Browser support: recent Safari/Chrome/Firefox. Add a `@supports` fallback.
3. Perfect as a reusable "background effect" control for your own widget library.

---

## §Equal Height addon (🟢)

**Source:** `addons/equal-height.php`.

1. Flex/Grid-based. Appends a "Make equal height" switcher to column/container controls.
2. Pure CSS once toggled. No JS needed if you use `align-items: stretch`.
3. Easiest addon to lift.

---

## §Tooltips addon (🟢)

**Source:** `addons/tooltips.php` + `tippy.js` or similar.

1. Copy addon file + referenced vendor JS.
2. Expose via "Enable Tooltip" switcher + Content and Style controls.
3. Consider replacing the bundled vendor with the native `[popover]` API where supported.

---

## §Wrapper Link addon (🟢)

**Source:** `addons/wrapper-link.php`.

1. Tiny JS handler that turns an element into a clickable area without nesting `<a>` (which breaks HTML validity).
2. Keeps keyboard focus with `role="link"` + `tabindex="0"`.
3. 50 lines. Lift verbatim, re-prefix.

---

## §Display Conditions engine (🔴)

Big system. Only worth lifting if you specifically need Elementor-Pro-free display conditions.

1. Lift the controls handler (`includes/pa-display-conditions/pa-controls-handler.php`) first.
2. Keep each condition class in `conditions/` as-is (each is already self-contained and tests `is_*()` or `has_*()`).
3. Decide which conditions you actually need. User role + device + URL + date are usually enough; skip ACF/WPML/geolocation unless the destination site uses those.
4. Rename `pa_display_conditions` filter → `agency_display_conditions`.
5. Rendering strategy: either `display: none` in CSS (SEO/a11y footgun — content still rendered) OR server-side skip (better — wrap the element's `before_render`/`after_render`).

---

## §Bundled MCP server (🔴 to lift whole, 🟡 to use as reference)

**Source:** `includes/abilities/` + `admin/includes/templates/mcp/`.

Two scenarios:

**Scenario A — You want your OWN MCP server for Elementor on your sites:**
1. Follow [`docs/05-mcp-and-cli/elementor-mcp-workflow.md`](file:///root/Desktop/learn/BAse/docs/05-mcp-and-cli/elementor-mcp-workflow.md) to use the official Elementor MCP or `WordPress/mcp-adapter` instead. PAE's bootstrap is bespoke; the official adapter is simpler.

**Scenario B — You want PAE-specific MCP tools exposed on a client's site:**
1. Keep PAE installed and enable `premium-ai-abilities` in the admin. Don't extract.

**Scenario C — You want to study PAE's bootstrap pattern:**
1. Read `abilities/bootstrap.php` + `abilities/elementor-mcp.php` + `abilities/OAuth/Store.php`.
2. Notable patterns:
   - The MCP route is `premium-addons/mcp` under `rest_namespace`.
   - OAuth state is daily-GC-cleaned via `pa_oauth_gc` scheduled action — even when the feature is off (defensive).
   - Category prefix `PA` prevents collision with other MCP servers.
   - `Connection_Log::init()` flushes logs when WP Application Passwords are revoked.
3. Those four patterns are worth copying wholesale into any custom MCP server you build.

---

## §Setup Wizard (🟡)

**Source:** `admin/includes/setup-wizard/main-view.php`, `elements.php`, `content-views/`.

A guided first-install experience with content views + element picker. Clean UX pattern for free plugins.

1. Lift the main view class + partial includes.
2. Replace the PA_Core activation hook that triggers it with your own.
3. The wizard uses vanilla JS (no React). Easy to adapt.

Reusable across any free plugin with a lot of toggles — just replace the content of the wizard steps.

---

## §Centralized API Keys manager (🟢)

**Source:** `admin/includes/keys.php`.

A single admin page that stores Mapbox, Google Maps, OpenWeather, Lottie, Pinterest, TikTok keys. Each key is exposed via `Admin_Helper::get_integrations_settings()`.

1. Lift the class as `src/Admin/ApiKeys.php`.
2. Simplify to just the services you need.
3. Expose settings via your own getter: `Agency_Api_Keys::get('openweather')`.
4. Encrypt at rest if you want (optional; WordPress options table is already DB-scoped).

Pattern worth copying into any plugin that consumes 2+ external APIs.

---

## §Assets Manager (per-post asset scanning) (🟡)

**Source:** `includes/assets-manager.php`.

Scans Elementor post content on save, records which PA widgets/addons are present, and on frontend load only enqueues matching assets.

Steps:
1. Lift the class as a utility — it's generic enough to handle any Elementor-widget library.
2. Replace the PA-specific element slug detection with a filter so other developers can register their slugs.
3. Cache storage: PAE uses post meta key `_pa_widget_elements` — change to `_<yourprefix>_widget_elements`.

This pattern is an answer to Elementor's classic "every widget's CSS loads on every page" problem. Widely reusable.

---

## §Cross-site element transfer (🟡)

**Source:** `includes/helpers/element-transfer.php`, `template-bundle.php`, `includes/extras/cross-copy-paste.php`.

Serialise an Elementor element (with nested elements, settings, image references) into a portable payload that can be pasted into another site.

Steps:
1. Lift `element-transfer.php` and `template-bundle.php`.
2. Media references: original code fetches images server-side and includes URLs in the payload. Decide: strip media (recipient fetches from source URLs) or embed base64 (self-contained but heavy).
3. Add a REST endpoint `POST /wp-json/agency/v1/transfer/import` that accepts the payload and creates the Elementor element.
4. Expose a simple admin UI with copy/paste buttons.

Rare feature in community plugins — worth isolating as its own small plugin.

---

## §Elements toggler (🟢)

**Source:** `admin/includes/elements.php`.

Bulk-enable/disable widgets and addons. Writes to an options row consumed by `Admin_Helper::get_enabled_elements()`.

Reusable pattern for any plugin with 20+ toggleable features.

---

## §Rollback button (🟢)

**Source:** `admin/includes/pa-rollback.php`.

Downgrade to a previous stable version via WordPress updater API.

1. Uses `PREMIUM_ADDONS_STABLE_VERSION` constant + WP plugin updater.
2. Lift directly; change the constant and update server URL to your own.
3. Pair with semver releases so rollback actually works.

Useful reference pattern for any shipped plugin.

---

## §Widget Class Map (🟢)

**Source:** `includes/helpers/widget-class-map.php`.

Simple slug → class registry. Let other code resolve a widget's class without hardcoding.

Great foundational pattern if you build a widget library of your own.

---

## Component Manifest template (fill one per actual extraction)

Copied from `playbooks/01-reverse-engineer-audit.md` §3:

```markdown
# Component Manifest: <Feature Name>

### Source Provenance
- Source: Premium Addons for Elementor 4.11.121
- Files:
  - PHP: widgets/<slug>.php
  - CSS: assets/frontend/css/<slug>.css
  - JS:  assets/frontend/js/<slug>.js
  - Vendor: assets/frontend/min-js/lib/<lib>.min.js (if any)

### Data & Model Dependencies
- Custom controls used: Premium_Background, …
- Elementor Kit globals: Global_Colors, Global_Typography
- API keys: OpenWeather, Mapbox, …
- Post meta: none / …

### Styling & CSS Footprint
- Root wrapper selector: .premium-<slug>
- CSS variables: --premium-<slug>-*
- Breakpoints: 768, 1024 (Elementor defaults)

### JavaScript & Interactivity
- Library deps: Swiper@11 / Isotope / Lottie / Luxon / CountUp.js
- Handler: extends elementorModules.frontend.handlers.Base
- Attach: elementor/frontend/init → frontend/element_ready/<slug>.default

### Licensing & Asset Review
- License: GPL-3.0 (Premium Addons parent)
- Bundled media: strip demo images in assets/images
- Proprietary framework calls: Admin_Helper::get_enabled_elements, pa_pro_label (strip)
```
