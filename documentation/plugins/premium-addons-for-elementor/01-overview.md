---
topic: Premium Addons for Elementor — Overview
last_synced: 2026-11-20
source_subject: /root/Desktop/learn/BAse/workspace/test-subjects/plugins/premium-addons-for-elementor/premium-addons-for-elementor
---

# Premium Addons for Elementor 4.11.121 — Overview

## Identity

| Field | Value |
| :--- | :--- |
| Name | Premium Addons for Elementor |
| Version | 4.11.121 |
| Vendor | Leap13 (`premiumaddons.com`) |
| License | GPL-3.0 |
| Text Domain | `premium-addons-for-elementor` |
| Namespace | `PremiumAddons\*` (PSR-ish, custom autoloader) |
| Class / Hook Prefix | `Premium_*`, `pa_*`, `premium-addon-*` (widget slugs) |
| Option Prefix | `pa_*`, `premium_*` |
| PHP min | 7.4 |
| WordPress min | 6.6 |
| Elementor tested up to | 4.4 |
| Elementor Pro tested up to | 4.4 |
| Dependency | Elementor active (plugin bails if missing) |

## Headline stats

| Metric | Count |
| :--- | :--- |
| PHP files | 605 |
| JavaScript files | 166 |
| CSS files | many (compiled per-widget stylesheet set) |
| Elementor widgets shipped | **37** (all under `widgets/`) |
| Elementor "addons" (global features, non-widget) | **8** |
| `do_action` / `apply_filters` call sites | 638 |
| `pa_*` action hooks exported | ~60 |
| `pa_*` filters exported | ~50 |

## What ships (by capability bucket)

### 1. Elementor widgets (37, one file per widget under `widgets/`)

Blog & content: `premium-blog`, `premium-post-ticker`, `premium-nav-menu`, `premium-mobile-menu`, `premium-search-form`.
Visual effects: `premium-fancytext` (Animated Text), `premium-lottie`, `premium-svg-drawer`, `premium-image-scroll`, `premium-image-separator`, `premium-shape-divider`-adjacent.
Layout & navigation: `premium-banner`, `premium-dual-header`, `premium-title`, `premium-grid`, `premium-carousel`, `premium-media-wheel`, `premium-vscroll`, `premium-textual-showcase`.
Interactive: `premium-modalbox`, `premium-notifications`, `premium-contactform`, `premium-countdown`, `premium-counter`, `premium-progressbar`, `premium-pricing-table`.
Media: `premium-videobox`, `premium-image-button`, `premium-tiktok-feed`, `premium-pinterest-feed`.
Icons & buttons: `premium-button`, `premium-icon-list`, `premium-tcloud` (tag cloud).
Info & social: `premium-person`, `premium-testimonials`, `premium-maps`, `premium-weather`, `premium-world-clock`.

Each widget is a single PHP file; CSS/JS live under `assets/`.

### 2. Elementor global "addons" (8) — `addons/`

These are *not* widgets; they extend **all** Elementor elements globally.

- **display-conditions** — show/hide any element based on user, date, device, query, cart state, ACF, cookie, etc. Proprietary condition engine.
- **equal-height** — force sibling columns/containers to equal height.
- **floating-effects** — adds floating/parallax motion controls to any element.
- **liquid-glass** — iOS-style frosted/blurred glass backdrop controls.
- **shape-divider** — custom shape dividers above/below any container.
- **shapes** — background shape layers.
- **tooltips** — tooltip controls on any element.
- **wrapper-link** — make any element a link without nesting `<a>`.

Each is a toggleable module in the plugin's admin UI.

### 3. Bundled MCP server + OAuth (`includes/abilities/`)

This is the **novel concept worth calling out loudest**: PAE ships a fully functional **Elementor MCP server**. It lets an external AI client connect via MCP and drive Elementor pages — create, read, update — using AI tools against Elementor's data structure.

Includes:
- `bootstrap.php` — abilities bootstrap, integrates with WordPress 6.9+ Abilities API
- `elementor-mcp.php` — Elementor-specific MCP tools
- `route-detector.php` — detects which MCP servers are already serving Premium abilities
- `connection-log.php` — connection event log
- `helpers.php`, `input-normalizer.php` — support utilities
- `dashboard/disable-unused-widgets.php`, `dashboard/get-settings.php` — dashboard-exposed tools
- `vendor/` — bundled Composer dependencies for the MCP SDK (authoritative autoload)
- **OAuth** — `Abilities\OAuth\Store` + daily `pa_oauth_gc` cleanup cron

Admin templates for this live under `admin/includes/templates/mcp/*.php` (mcp-config, mcp-oauth-setup, mcp-routes, mcp-connection-check, mcp-setup-steps, mcp-copy-block).

Feature-gated behind a single switcher: `premium-ai-abilities`. Guarded calls ensure OAuth gc cron stays registered even when the gate is off.

### 4. Custom Elementor controls (`includes/controls/`)

Six custom WP controls widgets can use beyond Elementor's built-in catalog:
- `premium-background` — advanced background picker
- `premium-select` — searchable select
- `premium-post-filter` — select posts/CPTs by query
- `premium-tax-filter` — select taxonomy terms
- `premium-acf-selector` — pick an ACF field
- `pa-image-choose` — image chooser

### 5. Admin surface (`admin/includes/`)

- `admin-helper.php` — top-level admin orchestrator (`Admin_Helper::get_instance()`)
- `admin-bar.php` — admin bar integration
- `admin-notices.php` — notice framework (review prompts, snooze logic)
- `elements.php` — module/widget toggler
- `feedback.php` — rate/review prompt
- `beta-testers.php` — beta program enrollment UI
- `duplicator.php` — Elementor template duplicator utility
- `keys.php` — API key manager (Mapbox, OpenWeather, Lottie, Pinterest, TikTok, etc.)
- `pa-rollback.php` — plugin version rollback
- `promotion-pointer.php` — in-admin promo pointers
- `mcp-client-logos.php`, `mcp-news.php`, `mcp-settings.php` — MCP-specific admin screens
- `templates/mcp/*.php` — 11 MCP UI partials
- `setup-wizard/` — first-install setup flow (main-view, elements, content-views)

### 6. Helpers & extensions (`includes/helpers/`, `includes/extras/`)

**Helpers:**
- `acf-helper.php` — ACF field resolution
- `ajax-helper.php` — AJAX trait (used by widgets for live data)
- `device-detector.php` — UA detection
- `document-helper.php` — Elementor document utils
- `element-transfer.php` — cross-site template export/import payload builder
- `nav-menu-walker.php` — custom nav walker (`pa-nav-menu-walker.php`)
- `papro-promotion.php` — Pro-upsell slot renderer
- `query-helper.php` — common `WP_Query` builders
- `template-bundle.php` — template bundling for exports
- `widget-class-map.php` — slug-to-class registry

**Extras:**
- `angie-integration.php` — Angie AI integration
- `cf7-inserter.php` — Contact Form 7 insertion helper
- `cross-copy-paste.php` — cross-site element paste
- `live-editor.php` + `live-editor-modal.php` — live editor modal

### 7. Display Conditions engine (`includes/pa-display-conditions/`)

A proprietary condition system (separate from, and more powerful than, Elementor's native conditions):

- `pa-controls-handler.php` — wires conditions into any element's controls
- `lang-locale.php` — language/locale conditions
- `conditions/` — individual condition classes (user role, date, device, URL, cart, query, ACF, cookie, referrer, geolocation, etc.)

### 8. Modules (`modules/`)

- `woocommerce/` — dedicated module for Woo-specific widgets and integrations.

### 9. Templates library (`includes/templates/`)

Bundled Elementor section/page templates ready to insert.

### 10. Extensibility

All widgets, addons, custom controls, and admin pages hook into a documented `pa_*` filter/action API (see `03-hooks-filters.md`). The Admin_Helper exposes `get_enabled_elements()` so other code can gate behaviour on specific toggles.

## Lifecycle

```
premium-addons-for-elementor.php
  ├── defines constants (PREMIUM_ADDONS_VERSION, PATHS)
  ├── requires autoload.php          # custom spl_autoload_register for PremiumAddons\*
  └── requires includes/class-pa-core.php
       └── new PA_Core()
            ├── on 'init' priority 10:    i18n
            ├── on 'init' priority -999:  (not shown above, early init)
            ├── on 'plugins_loaded':      pa_init()
            │     ├── Admin_Helper::get_instance()
            │     ├── Addons_Integration::get_instance()   # registers all widgets / addons
            │     └── load_abilities()                     # MCP server if enabled
            ├── on activation:            handle_activation (seeds install state, sets options)
            ├── on deactivation:          handle_deactivation
            └── on uninstall:             static uninstall()
```

## Red flags (per `APPROACH.md` §6)

| Flag | Where | Action |
| :--- | :--- | :--- |
| Hard dep on Elementor | `pa_init()` bails if Elementor missing | Keep the check pattern; strip the admin notice when extracting a single widget |
| Pro upsell overlays | `papro-promotion.php`, `pa_pro_label` filter, "Pro" label on disabled controls | Strip on extraction |
| Snooze/review-prompt UX | `admin-notices.php` | Do not ship on extracted components |
| Setup wizard | `admin/includes/setup-wizard/` | Nice reference pattern but strip when extracting individual widgets |
| MCP OAuth cleanup cron | `pa_oauth_gc` | If extracting MCP code, keep the cleanup cron; if not, no concern |
| Analytics / telemetry | Grep `promotion-pointer`, `beta-testers`, `feedback` | Strip |
| PSR-ish autoloader (not real PSR-4) | `autoload.php` | Replace with Composer PSR-4 when extracting |
| `premium-` and `pa-` double prefix | Mixed throughout | Normalize to one prefix during extraction |
| Vendor Composer libraries bundled | `includes/abilities/vendor/` | Keep intact — these are the MCP SDK |

## Scope

PAE is three products in one zip:
1. A library of 37 Elementor widgets.
2. A library of 8 global behavior addons.
3. An MCP server.

Treat each as a separate extraction candidate. Full catalog in [`04-feature-index.md`](04-feature-index.md). Per-widget deep-dives in [`widgets/`](widgets/).
