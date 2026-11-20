---
topic: Retrofitting an Existing Classic Theme for Elementor
source_url: https://developers.elementor.com/docs/themes/migrating-themes-with-functions
last_synced: 2026-11-20
---

# Migrating an Existing Classic Theme to Elementor Theme Builder

## 1. Migration Strategy & Zero-Regression Goal

When adding Elementor support to an established WordPress theme, the core invariant is:
**The theme must render 100% identically to its original design when Elementor Pro is inactive or when no Theme Builder condition matches.**

Retrofitting requires two steps:
1. Registering supported locations in `functions.php`.
2. Intercepting template outputs either through **Direct Template Wrappers** or **Action Hooks**.

---

## 2. Approach A: Template Wrapping via Functions

This is the standard Elementor approach: modify existing template files to wrap the main output in conditional checks.

### Step 1: Register Core Locations (`functions.php`)

```php
declare(strict_types=1);

namespace LegacyTheme\Integrations;

add_action( 'elementor/theme/register_locations', static function( $elementor_theme_manager ): void {
    $elementor_theme_manager->register_all_core_location();
} );
```

### Step 2: Retrofit `header.php` and `footer.php`

In `header.php`, locate the closing `</head>` and opening `<body>`. Wrap everything inside `<header>`:

```php
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php
if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) :
?>
    <header id="masthead" class="site-header">
        <div class="site-branding">
            <?php the_custom_logo(); ?>
        </div>
        <nav id="site-navigation" class="main-navigation">
            <?php wp_nav_menu( [ 'theme_location' => 'primary' ] ); ?>
        </nav>
    </header>
<?php endif; ?>
```

In `footer.php`, wrap the `<footer>` container:

```php
<?php
if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) :
?>
    <footer id="colophon" class="site-footer">
        <div class="site-info">
            <p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></p>
        </div>
    </footer>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
```

### Step 3: Central Dispatcher (`index.php`)

For classic themes that route multiple views through `index.php`:

```php
<?php
declare(strict_types=1);

get_header();

if ( is_archive() || is_home() || is_search() ) {
    if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'archive' ) ) {
        get_template_part( 'template-parts/archive-loop' );
    }
} elseif ( is_singular() ) {
    if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) {
        get_template_part( 'template-parts/single-content' );
    }
} else {
    // 404 or other fallbacks
    if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) {
        get_template_part( 'template-parts/404-content' );
    }
}

get_footer();
```

---

## 3. Approach B: Hook-Driven Theme Architecture

If your theme uses hook-driven architecture, you can retrofit Elementor without modifying template markup directly:

```php
// In functions.php or an integration module:
namespace LegacyTheme\Integrations;

add_action( 'theme_header', static function(): void {
    if ( function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( 'header' ) ) {
        // Elementor handled the header; stop theme execution
        remove_action( 'theme_header', 'theme_render_default_header', 10 );
    }
}, 5 );

add_action( 'theme_footer', static function(): void {
    if ( function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( 'footer' ) ) {
        // Elementor handled the footer; stop theme execution
        remove_action( 'theme_footer', 'theme_render_default_footer', 10 );
    }
}, 5 );
```

---

## 4. Key Defensive Check Matrix

| Condition | Native Markup Rendered? | Elementor Rendered? |
| :--- | :--- | :--- |
| Elementor inactive | **Yes** (fallback) | No |
| Elementor Free active (no Pro) | **Yes** (fallback) | No (Theme Builder requires Pro) |
| Elementor Pro active, no condition set | **Yes** (fallback) | No |
| Elementor Pro active, condition matched | No (skipped) | **Yes** (Theme Builder template) |
