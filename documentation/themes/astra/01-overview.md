---
topic: Astra Theme Overview
last_synced: 2026-11-20
source_subject: /root/Desktop/learn/BAse/workspace/test-subjects/themes/astra/astra
---

# Astra 4.14.3 — Overview

## Identity

| Field | Value |
| :--- | :--- |
| Name | Astra |
| Version | 4.14.3 |
| Vendor | Brainstorm Force (`wpastra.com`) |
| License | GPL-2.0-or-later |
| Text Domain | `astra` |
| Function Prefix | `astra_*`, some shared `bsf_*` |
| Class Prefix | `Astra_*` |
| PHP min | 5.3 (very permissive baseline — modern extraction code should target 8.1+) |
| WordPress min | 5.3 |
| WordPress tested up to | 7.1 |
| WooCommerce tested up to | 11.2.2 |
| Child-theme capable | Yes |
| theme.json | **Yes** (hybrid classic + partial block support) |
| AMP support flag | `AMP: true` in `style.css` |

## Headline stats

| Metric | Count |
| :--- | :--- |
| PHP files | 490 |
| JavaScript files | 79 |
| CSS/SCSS files | 122 |
| `add_action` / `add_filter` call sites | 805 |
| `do_action` call sites | 304 |
| Unique action hook names exported | 246 |
| `apply_filters` call sites | 601 |
| Unique filter names exported | 429 |
| `add_shortcode` call sites | **0** (clean — no shortcode pollution) |

## What Astra actually ships

Astra is a **classic-theme chassis plus visual builders**. Everything is a module behind a hook. Plugin territory in a theme suit:

1. **Classic PHP templates** at the root: `header.php`, `footer.php`, `single.php`, `page.php`, `archive.php`, `search.php`, `404.php`, `index.php`, `comments.php`, `sidebar.php`.
2. **Astra Header/Footer Builder** — a free in-theme layout builder (not Elementor). Lives in `inc/builder/`. See [`02-architecture.md`](02-architecture.md) §3.
3. **Dynamic CSS Generator** — compiles customizer settings into inline `<style>` on each request. `inc/dynamic-css/` + `inc/class-astra-dynamic-css.php`.
4. **Full Customizer panel/section library** — 50+ customizer config files under `inc/customizer/configurations/`.
5. **Compatibility layer** — 20+ plugin bridges (WooCommerce, Elementor, Elementor Pro, Beaver Builder, Divi, Gutenberg, BuddyPress, LearnDash, LifterLMS, SureCart, EDD, CF7, Gravity Forms, Jetpack, Yoast, WPML, AMP, SiteOrigin, Visual Composer, Ubermenu, Web Stories).
6. **Four bundled free "addons"** — `breadcrumbs`, `heading-colors`, `scroll-to-top`, `transparent-header` (feature flags in `inc/addons/`).
7. **Schema.org structured-data library** — 9 schema classes under `inc/schema/`.
8. **Admin dashboard (React)** — `admin/assets/build/` plus `admin/includes/class-astra-menu.php`. Modern WP admin single-page app.
9. **Astra Learn** — in-admin onboarding/tutorial system (`admin/includes/class-astra-learn.php`).
10. **REST API controller** — `admin/includes/class-astra-api-init.php` exposes theme settings and ability bindings.
11. **WordPress Abilities API integration** — `inc/abilities/` (CLI, helper, init, response, abstract base + customizer-tuned abilities).
12. **Command Palette integration** — `inc/core/class-astra-command-palette.php` wires customizer panels into WP 6.3+ Command Palette.
13. **Pro upsell wall** — hooks/UI that prompt upgrade to Astra Pro addon (see §Red flags).
14. **BSF Analytics telemetry** — opt-in usage reporting (`inc/lib/bsf-analytics/`, `admin/class-astra-bsf-analytics.php`).
15. **NPS Survey widget** — satisfaction prompts (`inc/lib/nps-survey/`, `class-astra-nps-notice.php`).
16. **Astra Notices framework** — admin notice manager (`inc/lib/astra-notices/`).
17. **Docs loader** — in-theme contextual help (`inc/lib/docs/class-astra-docs-loader.php`).
18. **Webfont loader** — self-host Google Fonts (`inc/lib/webfont/class-astra-webfont-loader.php`).
19. **Batch processing** — background job runner (`inc/lib/batch-processing/`).
20. **Theme update / background updater** — upgrades DB options between versions (`inc/theme-update/`).
21. **Mobile header** — dedicated standalone class (`inc/class-astra-mobile-header.php`).
22. **Dynamic colour palette** — global colour palette manager (`inc/class-astra-global-palette.php`).
23. **Loop class** — custom WP loop wrapper (`inc/class-astra-loop.php`).
24. **Memory-limit notice** — warns if site is under-provisioned (`inc/class-astra-memory-limit-notice.php`).
25. **Dedicated page walker** — `inc/core/class-astra-walker-page.php`.
26. **Icons library** — SVG icon registry (`inc/core/class-astra-icons.php`).
27. **WP editor / Gutenberg CSS** — editor styles synced with customizer (`class-astra-wp-editor-css.php`, `class-gutenberg-editor-css.php`).
28. **Theme Builder (free scaffold)** — admin shim for optional Theme Builder addon (`admin/includes/class-astra-theme-builder-free.php`).

## Dependencies & third-party libraries

- **Bundled JS** (`assets/js/`): Flexibility polyfill (`flexibility.js`), command palette wrapper, customizer live-preview, misc fast loaders. No Swiper/Slick/GSAP bundled by the free theme.
- **BSF framework libs** in `inc/lib/`: `astra-notices`, `bsf-analytics`, `nps-survey`, `batch-processing`, `docs`, `webfont` — all shared Brainstorm Force shared packages reused across other BSF plugins (Spectra, SureForms, etc.).
- **theme.json** present for block-editor colour palette interop.

## Add-on surface (paid-tier gating)

Many customizer panels contain **upgrade hooks** calling `astra_pro_upgrade_link` or rendering tempting disabled fields that require the paid "Astra Pro" plugin (`astra-addon`). Grep `class-astra-pro-upgrade-link-configs.php` and `apply_filters( 'astra_addon_*' )`. Astra Pro itself isn't in this workspace.

## Red flags (per [`APPROACH.md`](../../APPROACH.md) §6)

| Flag | Where | Action on extract |
| :--- | :--- | :--- |
| `get_theme_mod` everywhere | `inc/customizer/`, `inc/markup-extras.php`, dynamic-css modules | On extraction, swap for widget controls or scoped options. 1000+ call sites — grep per feature, not globally. |
| PHP 5.3 baseline | Entire codebase | Modernize to PHP 8.1+ (typed properties, union types, null-safe) during extraction. |
| No namespaces | All classes are `Astra_*` global | Wrap in `Agency\Companion\Extracted\Astra\<feature>` namespace on extract. |
| Pro upsell UI | Customizer controls, admin pages | Strip Pro upsell markup when extracting individual features. |
| BSF telemetry | `admin/class-astra-bsf-analytics.php` + `inc/lib/bsf-analytics/` | Do not ship BSF analytics with extracted code. |
| Freemius-free but has upgrade link injector | `inc/customizer/astra-pro/` | Delete any `astra-pro` links on extract. |
| Tested only to WP 7.1 | `style.css` | Re-verify features against your target WP version before extracting. |
| Astra Pro addon version check | `ASTRA_EXT_MIN_VER` constant in `functions.php` | Irrelevant once decoupled — remove. |

## Scope summary

Astra is realistically **a mini-platform**, not a theme. If you extract from it, treat each system (Builder / Dynamic CSS / Compatibility bridge / Schema / Admin) as a potential standalone companion plugin. Full feature index is in [`04-feature-index.md`](04-feature-index.md).
