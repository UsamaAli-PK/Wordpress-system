# Knowledge Base & Workspace Index

Master routing table for documentation, standards, playbooks, and templates.

| Category | Path | Description | Status |
| :--- | :--- | :--- | :--- |
| **Rules** | [rules/](file:///root/Desktop/learn/BAse/rules/README.md) | Coding standards, architecture & security guardrails | Active |
| **Docs** | [docs/](file:///root/Desktop/learn/BAse/docs/README.md) | Atomic reference docs by topic | Active (02, 03, 04, 05) |
| **Playbooks** | [playbooks/](file:///root/Desktop/learn/BAse/playbooks/README.md) | Step-by-step development & extraction workflows | Complete (01-04) |
| **Boilerplates** | [boilerplates/](file:///root/Desktop/learn/BAse/boilerplates/README.md) | Verified starter theme, companion plugin, widget template, and extracted modules | Complete |
| **Documentation** | [documentation/](file:///root/Desktop/learn/BAse/documentation/README.md) | Reverse-engineering audit maps, architecture graphs, and feature extraction recipes | Active (Astra, PAE) |
| **Workspace** | [workspace/](file:///root/Desktop/learn/BAse/workspace/README.md) | Active builds, test subjects (11 themes, 14 plugins), and extraction staging | Active |
| **Agent Guide** | [CLAUDE.md](file:///root/Desktop/learn/BAse/CLAUDE.md) | Claude Code entry point: imports this index and summarizes rules and conventions | Active |

---

## 01. Rules & Boundaries (`rules/`)

- [architecture-and-boundaries.md](file:///root/Desktop/learn/BAse/rules/architecture-and-boundaries.md) — Precedence rule (Classic PHP + Elementor override upstream Block/FSE recommendations) and Git/credential safety boundaries.

---

## 02. Plugins & Custom Data Modeling (`docs/02-plugins-and-data/`)

1. [companion-plugin-architecture.md](file:///root/Desktop/learn/BAse/docs/02-plugins-and-data/companion-plugin-architecture.md) — Separation of presentation vs functionality, header standards, activation/deactivation hooks, PSR-4 autoloading, and singleton orchestration.
2. [hooks-actions-filters.md](file:///root/Desktop/learn/BAse/docs/02-plugins-and-data/hooks-actions-filters.md) — Action/filter mechanics, priorities, accepted args, unhooking procedures, and closure removal pitfalls.
3. [cpts-taxonomies-shortcodes.md](file:///root/Desktop/learn/BAse/docs/02-plugins-and-data/cpts-taxonomies-shortcodes.md) — CPT and taxonomy registration with `show_in_rest`, activation-only rewrite flushing, and buffered output shortcodes.
4. [woocommerce-theme-support.md](file:///root/Desktop/learn/BAse/docs/02-plugins-and-data/woocommerce-theme-support.md) — Declaring `add_theme_support('woocommerce')`, gallery flags, `/woocommerce/` overrides vs `woocommerce.php`, and hook-based modification.
5. [acf-and-defensive-checks.md](file:///root/Desktop/learn/BAse/docs/02-plugins-and-data/acf-and-defensive-checks.md) — Safe ACF field resolution, repeater loops, defensive guard checks (`function_exists`, `class_exists`, `did_action`), and native meta fallbacks.

---

## 03. Elementor Theme Integration & Data Structure (`docs/03-elementor-theme/`)

1. [theme-locations-api.md](file:///root/Desktop/learn/BAse/docs/03-elementor-theme/theme-locations-api.md) — Registering core locations (`register_all_core_location()`), custom locations, wrapping `header.php`, `footer.php`, `single.php`, `archive.php`, and clean native PHP fallbacks.
2. [migrating-existing-theme.md](file:///root/Desktop/learn/BAse/docs/03-elementor-theme/migrating-existing-theme.md) — Non-destructive retrofitting of classic themes via functions and action hooks, with zero degradation when Elementor is inactive.
3. [container-json-schema.md](file:///root/Desktop/learn/BAse/docs/03-elementor-theme/container-json-schema.md) — Exact Elementor v0.4 JSON schema for modern Flexbox/Grid containers, widget elements, repeaters, responsive settings, and `_elementor_data` postmeta storage.
4. [global-styles-and-css-sync.md](file:///root/Desktop/learn/BAse/docs/03-elementor-theme/global-styles-and-css-sync.md) — Elementor Kit post structure, `__globals__` references, CSS custom properties (`--e-global-*`), and theme CSS variable bridging without specificity conflicts.

---

## 04. Modern Elementor Widgets & Addons (`docs/04-elementor-widgets/`)

1. [addon-plugin-and-registration.md](file:///root/Desktop/learn/BAse/docs/04-elementor-widgets/addon-plugin-and-registration.md) — Addon plugin bootstrap, version compatibility guards, custom widget categories (`elementor/elements/categories_registered`), and modern widget registration (`elementor/widgets/register`).
2. [widget-base-and-rendering.md](file:///root/Desktop/learn/BAse/docs/04-elementor-widgets/widget-base-and-rendering.md) — Extending `\Elementor\Widget_Base`, identification methods, strict escaping in PHP `render()`, render attributes, and client-side Backbone `content_template()`.
3. [controls-repeaters-styling.md](file:///root/Desktop/learn/BAse/docs/04-elementor-widgets/controls-repeaters-styling.md) — Content vs Style tabs, responsive controls, CSS `{{WRAPPER}}` selectors, `\Elementor\Repeater`, Group Controls (Typography, Border, Box Shadow), and conditional visibility rules.
4. [widget-assets-and-js-handlers.md](file:///root/Desktop/learn/BAse/docs/04-elementor-widgets/widget-assets-and-js-handlers.md) — Scoped per-widget dependency enqueueing (`get_script_depends`), modern ES6 `elementorModules.frontend.handlers.Base`, editor live re-rendering, and `frontend_available`.
5. [dynamic-tags.md](file:///root/Desktop/learn/BAse/docs/04-elementor-widgets/dynamic-tags.md) — Registering custom dynamic tag groups and tags via `elementor/dynamic_tags/register`, and dynamically piping CPT, ACF, and external API values into native controls.

---

## 05. MCP & WP-CLI Tooling (`docs/05-mcp-and-cli/`)

1. [wp-cli-and-mcp-adapter.md](file:///root/Desktop/learn/BAse/docs/05-mcp-and-cli/wp-cli-and-mcp-adapter.md) — Core WP-CLI operational commands (themes, plugins, Elementor CSS flush/replace-urls), local STDIO (`wp mcp-adapter serve`), and HTTP remote proxy configurations.
2. [elementor-mcp-workflow.md](file:///root/Desktop/learn/BAse/docs/05-mcp-and-cli/elementor-mcp-workflow.md) — Official Elementor MCP & EMCP tools, global Kit design token resolution, native container/widget authoring (anti-HTML-widget mandate), and 3-phase safety protocol.

---

## Playbooks & Engineering Protocols (`playbooks/`)

1. [01-reverse-engineer-audit.md](file:///root/Desktop/learn/BAse/playbooks/01-reverse-engineer-audit.md) — Read-only static inspection protocol, exact shell search patterns, asset/dependency mapping, and the Component Manifest template.
2. [02-extract-isolate-merge.md](file:///root/Desktop/learn/BAse/playbooks/02-extract-isolate-merge.md) — Decoupling proprietary frameworks (Redux/Kirki), strict namespacing/prefixing, asset isolation/deduplication, and PHP 8.x/API upgrades.
3. [03-section-to-elementor-widget.md](file:///root/Desktop/learn/BAse/playbooks/03-section-to-elementor-widget.md) — The DOM Parity Rule, mapping static markup to Elementor Content/Style/Repeater controls, and re-wiring JS to `elementorModules.frontend.handlers.Base`.
4. [04-build-unified-theme.md](file:///root/Desktop/learn/BAse/playbooks/04-build-unified-theme.md) — Complete end-to-end assembly workflow for lightweight base themes, companion addon plugins, CSS variable bridges, and preflight verification gates.

---

## 06. Test Subjects & Staging (`workspace/`)

- [workspace/README.md](file:///root/Desktop/learn/BAse/workspace/README.md) — Workspace overview and staging guidelines.
- [OWNER_AUTHORIZATION_AND_LICENSING.md](file:///root/Desktop/learn/BAse/workspace/test-subjects/OWNER_AUTHORIZATION_AND_LICENSING.md) — Formal grant of rights and special license from owner to use, inspect, reverse-engineer, and extract code for all purposes.
- [COMMERCIAL_TARGETS_CATALOG.md](file:///root/Desktop/learn/BAse/workspace/test-subjects/COMMERCIAL_TARGETS_CATALOG.md) — Architecture reference and live demo inspection strategies for commercial targets (JupiterX, The7, Phlox Pro, Bridge, Brisk, Avas, Asli, Aurem, Willows, Brandberry, Slider Revolution).
- [test-subjects/README.md](file:///root/Desktop/learn/BAse/workspace/test-subjects/README.md) — Master catalog of all 25 downloaded & extracted themes and plugins with individual README guides.
- [downloads/](file:///root/Desktop/learn/BAse/workspace/downloads/) — Raw staging repository containing all verified `.zip` archives (ignored in `.gitignore`).

### Stored Themes (`workspace/test-subjects/themes/`)
1. [astra/](file:///root/Desktop/learn/BAse/workspace/test-subjects/themes/astra/README.md) — Astra 4.14.3 (Theme Locations & classic hook benchmark).
2. [hello-elementor/](file:///root/Desktop/learn/BAse/workspace/test-subjects/themes/hello-elementor/README.md) — Hello Elementor 3.5.2 (Canonical minimal Elementor base).
3. [phlox/](file:///root/Desktop/learn/BAse/workspace/test-subjects/themes/phlox/README.md) — Phlox 2.17.22 (Auxin framework core / Phlox Pro engine).
4. [oceanwp/](file:///root/Desktop/learn/BAse/workspace/test-subjects/themes/oceanwp/README.md) — OceanWP (Multipurpose architecture & WooCommerce hooks).
5. [kadence/](file:///root/Desktop/learn/BAse/workspace/test-subjects/themes/kadence/README.md) — Kadence 1.6.0 (Modular CSS loading & header builder).
6. [blocksy/](file:///root/Desktop/learn/BAse/workspace/test-subjects/themes/blocksy/README.md) — Blocksy 2.1.62 (Modern CSS variable system & container support).
7. [inspiro/](file:///root/Desktop/learn/BAse/workspace/test-subjects/themes/inspiro/README.md) — Inspiro 2.3.3 (Video hero headers & portfolio media).
8. [spexo/](file:///root/Desktop/learn/BAse/workspace/test-subjects/themes/spexo/README.md) — Spexo 2.0.13 (Agency layouts & modern grid systems).
9. [royal-elementor-kit/](file:///root/Desktop/learn/BAse/workspace/test-subjects/themes/royal-elementor-kit/README.md) — Royal Elementor Kit 1.0.151 (Zero-bloat companion base theme).
10. [hello-animation/](file:///root/Desktop/learn/BAse/workspace/test-subjects/themes/hello-animation/README.md) — Hello Animation 1.1.10 (Animation utility presets).
11. [creative-portfolio-lite/](file:///root/Desktop/learn/BAse/workspace/test-subjects/themes/creative-portfolio-lite/README.md) — Creative Portfolio Lite 6.4.4 (Minimalist portfolio showcase).

### Stored Plugins (`workspace/test-subjects/plugins/`)
1. [animation-addons-for-elementor/](file:///root/Desktop/learn/BAse/workspace/test-subjects/plugins/animation-addons-for-elementor/README.md) — Animation Addons 4.2.13 (163 widgets & motion controls).
2. [responsive-addons-for-elementor/](file:///root/Desktop/learn/BAse/workspace/test-subjects/plugins/responsive-addons-for-elementor/README.md) — Responsive Addons 2.2.3 (95 mobile-optimized widgets).
3. [royal-elementor-addons/](file:///root/Desktop/learn/BAse/workspace/test-subjects/plugins/royal-elementor-addons/README.md) — Royal Elementor Addons 1.7.1072 (85 widgets & grid builders).
4. [essential-addons-for-elementor-lite/](file:///root/Desktop/learn/BAse/workspace/test-subjects/plugins/essential-addons-for-elementor-lite/README.md) — Essential Addons 6.8.9 (69 widgets & dynamic asset loader).
5. [the-plus-addons-for-elementor-page-builder/](file:///root/Desktop/learn/BAse/workspace/test-subjects/plugins/the-plus-addons-for-elementor-page-builder/README.md) — The Plus Addons 6.6.1 (68 dynamic widgets & styling tools).
6. [qi-addons-for-elementor/](file:///root/Desktop/learn/BAse/workspace/test-subjects/plugins/qi-addons-for-elementor/README.md) — Qi Addons 1.11.2 by Qode Interactive (64 luxury widgets).
7. [premium-addons-for-elementor/](file:///root/Desktop/learn/BAse/workspace/test-subjects/plugins/premium-addons-for-elementor/README.md) — Premium Addons 4.11.121 (53 interactive widgets).
8. [elementskit-lite/](file:///root/Desktop/learn/BAse/workspace/test-subjects/plugins/elementskit-lite/README.md) — ElementsKit Lite 4.2.0 (43 widgets & header/footer module).
9. [bdthemes-prime-slider-lite/](file:///root/Desktop/learn/BAse/workspace/test-subjects/plugins/bdthemes-prime-slider-lite/README.md) — Prime Slider 5.1.4 (22 hero slider & carousel widgets).
10. [bdthemes-element-pack-lite/](file:///root/Desktop/learn/BAse/workspace/test-subjects/plugins/bdthemes-element-pack-lite/README.md) — Element Pack Lite 10.0.12 (Core extension suite & query tools).
11. [happy-elementor-addons/](file:///root/Desktop/learn/BAse/workspace/test-subjects/plugins/happy-elementor-addons/README.md) — Happy Addons 3.52.0 (CSS transforms & tactile UI).
12. [header-footer-elementor/](file:///root/Desktop/learn/BAse/workspace/test-subjects/plugins/header-footer-elementor/README.md) — Header Footer Elementor (Locations API for classic themes).
13. [motionkit/](file:///root/Desktop/learn/BAse/workspace/test-subjects/plugins/motionkit/README.md) — MotionKit 2.1.1 (WAAPI / GSAP keyframe engine).
14. [smooth-scrolling-for-elementor/](file:///root/Desktop/learn/BAse/workspace/test-subjects/plugins/smooth-scrolling-for-elementor/README.md) — Arts Smooth Scrolling 1.0.7 (Lenis smooth scroll engine).


---

## 07. Production Boilerplates (`boilerplates/`)

- [boilerplates/README.md](file:///root/Desktop/learn/BAse/boilerplates/README.md) — Master guide for starter templates and extracted features.
- [theme/](file:///root/Desktop/learn/BAse/boilerplates/theme/README.md) — Base Classic Theme (PHP 8.1+, Elementor Theme Locations API, native PHP semantic fallbacks, CSS token bridge).
- [companion-plugin/](file:///root/Desktop/learn/BAse/boilerplates/companion-plugin/README.md) — Agency Companion Plugin (PSR-4 autoloader, lifecycle hooks, REST CPT registry, modern widget loader).
- [widget-template/](file:///root/Desktop/learn/BAse/boilerplates/widget-template/README.md) — Elementor Widget Boilerplate (extending `\Elementor\Widget_Base`, repeaters, group controls, ES6 frontend handler, Backbone live template).
- [First Extracted Feature: ScrollToTop](file:///root/Desktop/learn/BAse/boilerplates/companion-plugin/src/Extracted/Astra/ScrollToTop.php) — Extracted from Astra theme, decoupled from theme options, rewritten with pure native vanilla ES6 (zero jQuery).

---

## 08. Reverse-Engineering Audits & Maps (`documentation/`)

- [documentation/README.md](file:///root/Desktop/learn/BAse/documentation/README.md) — Extraction-ready developer documentation for test subjects.
- [documentation/APPROACH.md](file:///root/Desktop/learn/BAse/documentation/APPROACH.md) — Reproducible 9-step audit and reconnaissance strategy with exact grep commands.
- [Astra Theme Audit](file:///root/Desktop/learn/BAse/documentation/themes/astra/01-overview.md) — Complete 5-document mapping covering architecture, dynamic CSS compositor, 650 hooks, and extraction recipes.
- [Premium Addons Audit](file:///root/Desktop/learn/BAse/documentation/plugins/premium-addons-for-elementor/01-overview.md) — Complete 5-document mapping covering MCP REST API, OAuth, assets scanner, and widget deep dives.

---

## Recommended Upstream Skills Checklist (`WordPress/agent-skills`)

Use this checklist when installing skills to this workspace via `npx skills add WordPress/agent-skills --skill <name>`:

| Upstream Skill | Status | Rationale |
| :--- | :--- | :--- |
| `wp-plugin-development` | **Recommended** | High value for companion plugins, options, capabilities, and nonces. |
| `wp-wpcli-and-ops` | **Recommended** | Critical for automated site management, database migrations, and flushing. |
| `wp-performance` | **Recommended** | Core caching, database profiling, and query reduction standards. |
| `wp-rest-api` | **Recommended** | Essential for custom REST endpoints and dynamic data. |
| `wp-abilities-api` | **Recommended** | Foundation for exposing custom plugin functions to MCP clients. |
| `wp-ability-development` | **Recommended** | Designing typed, schema-validated abilities for agents. |
| `wp-abilities-audit` | **Recommended** | Audit REST surface to expose abilities. |
| `wp-abilities-verify` | **Recommended** | Lint and verify registered abilities. |
| `wp-phpstan` | **Recommended** | High-rigor static type analysis for PHP 8+. |
| `wp-env` | **Recommended** | Docker-based development setup with `@wordpress/env`. |
| `blueprint` | **Recommended** | Reproducible WordPress Playground setups for testing. |
| `wp-playground` | **Recommended** | In-browser testing and instant repros. |
| `wp-plugin-directory-guidelines` | **Recommended** | Clean licensing, sanitization, and prefixing compliance. |
| `wordpress-router` | **Recommended** | Automated repository classification. |
| `wp-project-triage` | **Recommended** | Automatic version and stack discovery. |
| `wp-block-development` | **Skip** | Strictly Block Editor / Gutenberg blocks (excluded from our Classic PHP + Elementor stack). |
| `wp-block-themes` | **Skip** | Strictly FSE / `theme.json` block themes (excluded in favor of Classic Themes + Locations). |
| `wp-interactivity-api` | **Skip** | Gutenberg frontend runtime (`data-wp-*`), irrelevant for Elementor JS handlers. |
| `wpds` | **Skip** | WordPress React design system for Gutenberg admin UI. |
