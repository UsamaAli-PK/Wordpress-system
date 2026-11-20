---
topic: Unified Theme and Companion Plugin Assembly Workflow
last_synced: 2026-11-20
---

# Unified Theme and Companion Plugin Assembly Workflow

## 1. Architectural Blueprint

A production-grade WordPress + Elementor deployment cleanly isolates presentation from business data:

```
[WordPress Core]
  ├── Active Theme: "agency-theme"
  │     ├── Theme Locations (header, footer, single, archive)
  │     ├── Global CSS Variables (:root tokens)
  │     ├── Navigation Menus & Sidebars
  │     └── Native PHP Fallbacks (zero-degradation if Elementor disabled)
  │
  ├── Companion Plugin: "agency-companion"
  │     ├── CPTs & Taxonomies (show_in_rest = true)
  │     ├── Custom Elementor Widgets (\Elementor\Widget_Base)
  │     ├── Scoped Widget CSS/JS Handlers
  │     └── Dynamic Tags
  │
  └── Elementor Kit & JSON Templates
        ├── Global Colors & Typography Kit
        └── Pre-built Container Page/Header/Footer Templates
```

---

## 2. Step-by-Step Assembly Procedure

### Step 1: Scaffold the Lightweight Base Theme

1. **`style.css`**: Minimal theme header declaration (Requires PHP 8.1+, Requires at least WP 6.4).
2. **`functions.php`**:
   - Declare `add_theme_support('title-tag')`, `add_theme_support('post-thumbnails')`, `add_theme_support('woocommerce')`.
   - Register navigation menus (`register_nav_menus`).
   - Register widget sidebars (`register_sidebar`).
   - Hook `elementor/theme/register_locations` calling `$manager->register_all_core_location()`.
   - Enqueue `theme-variables.css` containing the CSS custom properties bridge.
3. **Template Wrappers**:
   - `header.php`: Wrapped with `elementor_theme_do_location('header')`.
   - `footer.php`: Wrapped with `elementor_theme_do_location('footer')`.
   - `index.php` / `singular.php`: Wrapped with `elementor_theme_do_location('single')`.
   - `archive.php`: Wrapped with `elementor_theme_do_location('archive')`.

### Step 2: Assemble the Companion Plugin

1. Initialize `composer.json` with PSR-4 autoloading (`Agency\\Companion\\: "src/"`).
2. Main bootstrap file (`agency-companion.php`):
   - Hook `register_activation_hook` (call CPT init, then `flush_rewrite_rules()`).
   - Hook `register_deactivation_hook` (`flush_rewrite_rules()`).
   - Verify `did_action('elementor/loaded')` on `plugins_loaded`.
   - Register custom widget categories on `elementor/elements/categories_registered`.
   - Register all custom & extracted widgets on `elementor/widgets/register`.
3. Package all extracted widgets under `src/Widgets/` with scoped asset dependencies.

### Step 3: Establish the Design Token CSS Bridge

In `assets/css/theme-variables.css`, map all native theme styling directly to Elementor Kit variables with strict native fallbacks:

```css
:root {
  /* Colors */
  --theme-color-primary: var(--e-global-color-primary, #0f172a);
  --theme-color-secondary: var(--e-global-color-secondary, #334155);
  --theme-color-accent: var(--e-global-color-accent, #2563eb);
  --theme-color-background: var(--e-global-color-text, #ffffff);

  /* Typography */
  --theme-font-headings: var(--e-global-typography-primary-font-family, system-ui, sans-serif);
  --theme-font-body: var(--e-global-typography-text-font-family, system-ui, sans-serif);

  /* Container Boundaries */
  --theme-container-width: var(--e-container-default-width, 1200px);
}
```

### Step 4: Bundle Elementor Container JSON Templates

Save exportable Container templates (`v0.4` JSON schema) inside `templates/` for automated import:
- `templates/header-modern.json`: Container header with logo, nav-menu, and CTA button.
- `templates/footer-clean.json`: 4-column container footer with copyright and social icons.
- `templates/landing-hero.json`: Flexbox hero container with heading, description, and dual CTA buttons.

---

## 3. Preflight Quality & Verification Gate

Before committing or releasing the unified theme + companion build, verify:

| Gate Check | Verification Procedure | Expected Outcome |
| :--- | :--- | :--- |
| **PHP Syntax / Types** | Run `php -l` on all PHP files | No parse errors or deprecation warnings |
| **Elementor Inactive Test** | Deactivate Elementor plugin in WP admin | Theme renders native fallback header, footer, loop without fatal errors |
| **Theme Builder Condition Test** | Create Header in Elementor Pro (`Entire Site`) | Elementor header replaces native header smoothly |
| **Widget Preview Test** | Edit a page in Elementor Editor and drag custom widget | Widget renders live via `content_template()`, updates on control change |
| **JS Memory Leak Test** | Drag/reorder interactive widget (carousel/tabs) | JS handler tears down old instance and mounts cleanly without error |
| **Postmeta Integrity** | Check `wp_postmeta` for `_elementor_data` | Valid JSON array containing `elType: "container"` elements |
