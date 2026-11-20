---
topic: Decoupling, Isolation, and Migration Protocol
last_synced: 2026-11-20
---

# Decoupling, Isolation, and Codebase Migration Protocol

## 1. Decoupling Proprietary Theme Frameworks

Legacy commercial themes frequently couple layout components to proprietary frameworks (Redux, Kirki, Unyson, Codestar). During extraction, replace framework calls with native parameters:

### Migration Mapping Table

| Proprietary Framework Call | Clean Replacement Pattern |
| :--- | :--- |
| `Redux::getOption( 'opt_id', 'key' )` | `$settings['control_key']` in widget, or `get_option()` |
| `get_theme_mod( 'setting_id', 'default' )` | `$this->get_settings_for_display()['setting_id']` |
| `kirki_get_option( 'theme_config', 'key' )` | Elementor Control Setting or CSS variable |
| `fw_get_db_post_option( $post_id, 'key' )` | `get_post_meta( $post_id, 'key', true )` or ACF `get_field()` |

### Code Refactoring Example

```php
// ❌ DIRTY EXTRACTED CODE (Tied to Redux & hardcoded globals)
$accent = Redux::getOption( 'agency_opts', 'primary_color' );
$title  = get_theme_mod( 'hero_title', 'Default Title' );

// ✅ CLEAN DECOUPLED CODE (Widget-scoped or native fallback)
$settings = $this->get_settings_for_display();
$title    = ! empty( $settings['hero_title'] ) ? (string) $settings['hero_title'] : 'Default Title';
// Accent color handled via CSS custom property: var(--theme-color-primary)
```

---

## 2. Strict Namespacing, Prefixing & Text Domains

Every extracted PHP class, function, database option, and CSS class must be re-prefixed to prevent fatal name collisions:

1. **PHP Namespacing:** Use modern PSR-4 namespace:
   ```php
   namespace Agency\Companion\Widgets;
   ```
2. **Procedural Functions & Hooks:** Prefix with vendor slug:
   ```php
   agency_companion_register_cpt();
   do_action( 'agency_companion_after_render' );
   ```
3. **CSS Class Scoping:** Prefix component wrappers to avoid collisions with other plugins:
   ```css
   /* ❌ Unscoped class collision risk */
   .card { padding: 20px; }

   /* ✅ Scoped BEM format */
   .agency-card-item { padding: 20px; }
   ```
4. **Text Domain Replacement:** Search-and-replace all localization functions:
   ```bash
   # Find old text domains
   grep -rnE "__\(\s*['\"][^'\"]+['\"]\s*,\s*['\"]([^'\"]+)['\"]\s*\)" .
   ```
   Replace `__('Text', 'old-theme-slug')` with `__('Text', 'agency-companion')`.

---

## 3. Asset Isolation & Deduplication

Never import an existing theme’s monolithic 15,000-line `style.css` file into your custom build.

### Surgical CSS Extraction Rules
1. Copy only CSS rules matching the component's root wrapper and child selectors.
2. Convert hardcoded brand colors to semantic variables:
   ```css
   /* Before */
   .old-banner { background: #ff5722; font-family: 'Poppins', sans-serif; }

   /* After: Scoped and CSS Variable Synced */
   .agency-banner {
     background-color: var(--theme-color-accent, #ff5722);
     font-family: var(--theme-font-primary, sans-serif);
   }
   ```
3. Save the isolated stylesheet under `assets/css/[widget-name].css` for scoped loading.

### Dequeuing Redundant Assets
If a parent theme or existing plugin loads duplicate libraries (e.g., an outdated jQuery Slick carousel), dequeue it:

```php
add_action( 'wp_enqueue_scripts', static function(): void {
    // Prevent old duplicate carousel library from loading
    wp_dequeue_script( 'slick-carousel' );
    wp_deregister_script( 'slick-carousel' );
}, 99 );
```

---

## 4. Modernizing Legacy Code (PHP 8+ & Elementor 3.x)

Update legacy code patterns immediately during migration:

| Legacy Pattern | Modern PHP 8+ / WordPress Replacement |
| :--- | :--- |
| `add_action('elementor/widgets/widgets_registered')` | `add_action('elementor/widgets/register')` |
| `$this->register_widget_type( new Widget() )` | `$widgets_manager->register( new Widget() )` |
| `\Elementor\Scheme_Color::COLOR_1` | Kit Global Colors or Group Controls |
| Dynamic properties without declaration | Declare explicit typed class properties |
| PHP 8.2 null passing to string functions | Null-safe string casting: `(string) ( $val ?? '' )` |
| `create_function(...)` | Anonymous closure: `fn() => ...` |
