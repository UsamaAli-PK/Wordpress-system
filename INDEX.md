# Knowledge Base & Workspace Index

Master routing table for documentation, standards, playbooks, and templates.

| Category | Path | Description | Status |
| :--- | :--- | :--- | :--- |
| **Rules** | [rules/](file:///root/Desktop/learn/BAse/rules/README.md) | Coding standards, architecture & security guardrails | Active |
| **Docs** | [docs/](file:///root/Desktop/learn/BAse/docs/README.md) | Atomic reference docs by topic | Active (02, 03, 04, 05) |
| **Playbooks** | [playbooks/](file:///root/Desktop/learn/BAse/playbooks/README.md) | Step-by-step development & extraction workflows | Complete (01-04) |
| **Boilerplates** | [boilerplates/](file:///root/Desktop/learn/BAse/boilerplates/README.md) | Verified starter themes, companion plugins, & widgets | Skeleton |
| **Workspace** | [workspace/](file:///root/Desktop/learn/BAse/workspace/README.md) | Active builds, tests, and component inspection | Ready |
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
