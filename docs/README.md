# Documentation & Reference

Atomic Markdown reference documentation covering core APIs, hooks, hierarchy, Elementor locations, and plugin integration patterns.

## Modules

### [02. Plugins & Data Architecture](file:///root/Desktop/learn/BAse/docs/02-plugins-and-data/)
- [Companion Plugin Architecture](file:///root/Desktop/learn/BAse/docs/02-plugins-and-data/companion-plugin-architecture.md) — Separation of concerns, plugin headers, activation/deactivation hooks, PSR-4 structure, and namespacing.
- [Hooks: Actions and Filters](file:///root/Desktop/learn/BAse/docs/02-plugins-and-data/hooks-actions-filters.md) — Action/filter execution, priorities, accepted args, and safely removing hooks.
- [CPTs, Taxonomies & Shortcodes](file:///root/Desktop/learn/BAse/docs/02-plugins-and-data/cpts-taxonomies-shortcodes.md) — Clean registration with REST support, activation rewrite flushing, and buffered shortcodes.
- [WooCommerce Theme Support](file:///root/Desktop/learn/BAse/docs/02-plugins-and-data/woocommerce-theme-support.md) — Declaring theme support, gallery flags, template overrides, and hook-based customizations.
- [ACF & Defensive Checks](file:///root/Desktop/learn/BAse/docs/02-plugins-and-data/acf-and-defensive-checks.md) — Safe field retrieval, repeater iteration, defensive plugin guards, and graceful fallbacks.

### [03. Elementor Theme Integration & Data Structure](file:///root/Desktop/learn/BAse/docs/03-elementor-theme/)
- [Theme Locations API](file:///root/Desktop/learn/BAse/docs/03-elementor-theme/theme-locations-api.md) — Core location registration, custom locations, and template wrapping with native fallbacks.
- [Migrating an Existing Theme](file:///root/Desktop/learn/BAse/docs/03-elementor-theme/migrating-existing-theme.md) — Function-based and hook-driven retrofit strategies without regressions.
- [Container JSON Schema](file:///root/Desktop/learn/BAse/docs/03-elementor-theme/container-json-schema.md) — v0.4 JSON schema for modern flexbox/grid containers, widgets, repeaters, and `_elementor_data` storage.
- [Global Styles & CSS Sync](file:///root/Desktop/learn/BAse/docs/03-elementor-theme/global-styles-and-css-sync.md) — Elementor Kit storage, `__globals__` references, and bridging CSS variables without specificity issues.

### [04. Modern Elementor Widgets & Addons](file:///root/Desktop/learn/BAse/docs/04-elementor-widgets/)
- [Addon Plugin & Registration](file:///root/Desktop/learn/BAse/docs/04-elementor-widgets/addon-plugin-and-registration.md) — Companion addon bootstrap, compatibility guards, category registration, and widget manager registration.
- [Widget Base & Rendering Lifecycle](file:///root/Desktop/learn/BAse/docs/04-elementor-widgets/widget-base-and-rendering.md) — Extending `Widget_Base`, metadata methods, escaped PHP `render()`, render attributes, and client-side `content_template()`.
- [Controls, Repeaters & Styling](file:///root/Desktop/learn/BAse/docs/04-elementor-widgets/controls-repeaters-styling.md) — Content vs Style tabs, responsive controls, CSS `{{WRAPPER}}` selectors, `Repeater`, group controls, and conditional display.
- [Widget Assets & JS Handlers](file:///root/Desktop/learn/BAse/docs/04-elementor-widgets/widget-assets-and-js-handlers.md) — Per-widget asset scoping, extending `elementorModules.frontend.handlers.Base`, editor live re-mounting, and `frontend_available`.
- [Dynamic Tags Development](file:///root/Desktop/learn/BAse/docs/04-elementor-widgets/dynamic-tags.md) — Custom dynamic tag groups, categories, and binding widget inputs dynamically to CPT, ACF, or external API endpoints.

### [05. MCP Servers & WP-CLI Operations](file:///root/Desktop/learn/BAse/docs/05-mcp-and-cli/)
- [WP-CLI & MCP Adapter](file:///root/Desktop/learn/BAse/docs/05-mcp-and-cli/wp-cli-and-mcp-adapter.md) — Deterministic WP-CLI commands, core abilities, and local STDIO vs remote HTTP configuration for `WordPress/mcp-adapter`.
- [Elementor MCP Workflow](file:///root/Desktop/learn/BAse/docs/05-mcp-and-cli/elementor-mcp-workflow.md) — Official Elementor MCP & EMCP tools, design token resolution, native container/widget authoring, and read-only-first safety protocol.
