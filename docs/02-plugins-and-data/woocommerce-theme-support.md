---
topic: WooCommerce Theme Support & Overrides
source_url: https://developer.woocommerce.com/docs/theming/theme-development/classic-theme-developer-handbook/
last_synced: 2026-11-20
---

# WooCommerce Theme Support & Customization

## 1. Declaring WooCommerce Support

Classic themes must explicitly declare support for WooCommerce using `add_theme_support()`. Without this declaration, WooCommerce treats the theme as unsupported and falls back to generic shortcode rendering.

```php
declare(strict_types=1);

namespace Agency\Theme;

function setup_woocommerce_support(): void {
    // 1. Declare core WooCommerce compatibility
    add_theme_support( 'woocommerce', [
        'thumbnail_image_width'         => 300,
        'single_image_width'            => 600,
        'gallery_thumbnail_image_width' => 150,
        'product_grid'                  => [
            'default_columns' => 4,
            'min_columns'     => 2,
            'max_columns'     => 6,
            'default_rows'    => 3,
            'min_rows'        => 1,
            'max_rows'        => 8,
        ],
    ] );

    // 2. Enable modern product gallery features
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\\setup_woocommerce_support' );
```

---

## 2. Integration Strategies: `woocommerce.php` vs Template Overrides

WooCommerce provides two methods to adapt layouts to a custom theme:

### Method A: The Catch-All `woocommerce.php`

Create `woocommerce.php` in the theme root (duplicated from `page.php`), replacing the standard loop with `woocommerce_content()`:

```php
<?php
get_header();
?>
<main id="primary" class="site-main container">
    <?php woocommerce_content(); ?>
</main>
<?php
get_sidebar( 'shop' );
get_footer();
```

> **Trade-off:** `woocommerce.php` is fast and prevents display layout glitches, but it takes precedence over **all** WooCommerce taxonomy and post type displays. You **cannot** use `woocommerce/archive-product.php` overrides when `woocommerce.php` exists.

### Method B: Template Overrides (`/woocommerce/` folder)

For granular control, do not use `woocommerce.php`. Instead, copy specific templates from `plugins/woocommerce/templates/` into your theme's `/woocommerce/` directory (omitting the `templates/` folder level):

- Plugin source: `wp-content/plugins/woocommerce/templates/single-product/price.php`
- Theme override: `wp-content/themes/agency-theme/woocommerce/single-product/price.php`

#### Critical Rule: The `@version` Header

Always maintain the `@version` comment at the top of overridden files:

```php
/**
 * Single Product Price
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/price.php.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.0.0
 */
```

WooCommerce inspects this version in `WooCommerce > Status > System Status`. If WooCommerce updates a template and your version header lags, an "Outdated Templates" alert will prompt maintenance.

---

## 3. The Hook-First Customization Principle

> **Best Practice:** Avoid template overrides whenever possible. Template overrides introduce technical debt across WooCommerce major updates. Customize layouts and markup using action hooks.

### Customizing Single Product Summary

WooCommerce single product summary elements are hooked into `woocommerce_single_product_summary`:

| Element | Default Hook Callback | Priority |
| :--- | :--- | :--- |
| Title | `woocommerce_template_single_title` | 5 |
| Rating | `woocommerce_template_single_rating` | 10 |
| Price | `woocommerce_template_single_price` | 10 |
| Excerpt | `woocommerce_template_single_excerpt` | 20 |
| Add to Cart | `woocommerce_template_single_add_to_cart` | 30 |
| Meta | `woocommerce_template_single_meta` | 40 |
| Sharing | `woocommerce_template_single_sharing` | 50 |

### Reordering Elements Safely Without Modifying Files

```php
namespace Agency\Theme\WooCommerce;

add_action( 'wp', static function(): void {
    if ( ! is_product() ) {
        return;
    }

    // Move price below the excerpt
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
    add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 25 );

    // Inject custom badge before product title
    add_action( 'woocommerce_single_product_summary', static function(): void {
        echo '<span class="agency-verified-badge">' . esc_html__( 'Authentic Product', 'agency-theme' ) . '</span>';
    }, 4 );
} );
```

### Wrapping Shop Container Markup Cleanly

```php
// Remove default WooCommerce container wrappers
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

// Inject theme-specific container markup
add_action( 'woocommerce_before_main_content', static function(): void {
    echo '<div class="agency-container site-shop-layout"><main id="shop-primary" class="content-area">';
}, 10 );

add_action( 'woocommerce_after_main_content', static function(): void {
    echo '</main></div>';
}, 10 );
```
