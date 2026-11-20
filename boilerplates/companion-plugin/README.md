# Agency Companion Plugin Boilerplate

**Location:** `boilerplates/companion-plugin/`  
**License:** GPL-2.0+  
**Stack:** PSR-4 Namespaced PHP 8.1+ Plugin with Elementor 3.5+ Widget Loader & Decoupled Feature Modules

---

## 1. Architectural Overview

- **Namespace:** `Agency\Companion\`
- **Autoloading:** Native lightweight PSR-4 autoloader registered in `agency-companion.php`.
- **Safe Activation / Deactivation**: Deferring rewrite flushes using transient flags on activation, preventing expensive runtime cache flushes on standard loads.
- **CPT / Taxonomy Registry**: `src/CPT/PostTypeRegistry.php` registers custom post types with `show_in_rest => true` for compatibility with Block Editor, REST, and AI MCP adapters.
- **Modern Elementor 3.x Widgets**: `src/Elementor/WidgetLoader.php` uses `elementor/widgets/register` and registers custom sidebar categories via `elementor/elements/categories_registered`.
- **First Extracted Feature**: `src/Extracted/Astra/ScrollToTop.php` — A fully working, production-grade module extracted from Astra theme, completely decoupled from Astra's theme options and jQuery, rewritten to pure native vanilla ES6.
