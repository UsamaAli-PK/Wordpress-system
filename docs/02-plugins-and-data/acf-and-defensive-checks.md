---
topic: ACF Integration and Defensive Plugin Checks
source_url: https://www.advancedcustomfields.com/resources/
last_synced: 2026-11-20
---

# ACF Integration and Defensive Plugin Architecture

## 1. Defensive Plugin Checks

Themes and companion modules must never assume third-party plugins are permanently active. Calling plugin functions directly without existence checks will trigger fatal PHP errors (`Call to undefined function`) if the user deactivates the plugin.

### Standard Defensive Check Hierarchy

```php
declare(strict_types=1);

namespace Agency\Core\Integrations;

final class IntegrationGuard {
    /**
     * Check if Advanced Custom Fields (Free or Pro) is loaded.
     */
    public static function has_acf(): bool {
        return class_exists( 'ACF' ) || function_exists( 'get_field' );
    }

    /**
     * Check if WooCommerce is loaded.
     */
    public static function has_woocommerce(): bool {
        return class_exists( 'WooCommerce' );
    }

    /**
     * Check if Elementor has finished loading.
     */
    public static function has_elementor(): bool {
        return did_action( 'elementor/loaded' ) > 0;
    }
}
```

---

## 2. Safe Field Retrieval with Fallbacks

When ACF is deactivated, post meta still resides in the WordPress `wp_postmeta` database table. A defensive helper wrapper retrieves the value via ACF if active, or falls back to native `get_post_meta()`:

```php
namespace Agency\Core\Helpers;

use Agency\Core\Integrations\IntegrationGuard;

/**
 * Safely retrieve a custom field with native meta fallback.
 */
function safe_get_field( string $selector, int|null $post_id = null, mixed $default = null ): mixed {
    $target_id = $post_id ?? get_the_ID();

    if ( ! $target_id ) {
        return $default;
    }

    // 1. If ACF is active, utilize ACF formatted field resolution
    if ( IntegrationGuard::has_acf() ) {
        $value = get_field( $selector, $target_id );
        return ( null !== $value && false !== $value ) ? $value : $default;
    }

    // 2. Graceful fallback: Read raw value from native post meta
    $raw_meta = get_post_meta( $target_id, $selector, true );

    return ( '' !== $raw_meta && false !== $raw_meta ) ? $raw_meta : $default;
}
```

---

## 3. Reading Repeater & Flexible Content Fields

Repeater and flexible content structures require `have_rows()` and `the_row()` to iterate over subfields cleanly.

```php
namespace Agency\Theme\Components;

use Agency\Core\Integrations\IntegrationGuard;

function render_testimonials_slider( int $post_id ): void {
    if ( ! IntegrationGuard::has_acf() ) {
        return;
    }

    if ( ! have_rows( 'client_testimonials', $post_id ) ) {
        return;
    }
    ?>
    <section class="testimonials-slider">
        <?php while ( have_rows( 'client_testimonials', $post_id ) ) : the_row();
            // Fetch subfield values with proper sanitization
            $client_name = (string) get_sub_field( 'client_name' );
            $client_role = (string) get_sub_field( 'client_role' );
            $feedback    = (string) get_sub_field( 'feedback' );
            $avatar      = get_sub_field( 'avatar' ); // Array return format
            ?>
            <article class="testimonial-item">
                <blockquote class="testimonial-text">
                    <?php echo wp_kses_post( $feedback ); ?>
                </blockquote>
                <div class="testimonial-author">
                    <?php if ( is_array( $avatar ) && ! empty( $avatar['url'] ) ) : ?>
                        <img src="<?php echo esc_url( (string) $avatar['url'] ); ?>" 
                             alt="<?php echo esc_attr( (string) ( $avatar['alt'] ?? $client_name ) ); ?>" 
                             width="64" height="64" />
                    <?php endif; ?>
                    <strong><?php echo esc_html( $client_name ); ?></strong>
                    <?php if ( '' !== $client_role ) : ?>
                        <span><?php echo esc_html( $client_role ); ?></span>
                    <?php endif; ?>
                </div>
            </article>
        <?php endwhile; ?>
    </section>
    <?php
}
```

---

## 4. Escaping Outputs & Modern ACF Escaping

Never echo `get_field()` or `the_field()` unescaped. Apply context-aware escaping based on expected data types:

```php
$title = (string) get_field( 'hero_title' );
$link  = (string) get_field( 'cta_link' );
$body  = (string) get_field( 'hero_description' );

// 1. Text strings: esc_html
echo '<h1>' . esc_html( $title ) . '</h1>';

// 2. URLs: esc_url
echo '<a href="' . esc_url( $link ) . '" class="btn btn-primary">';

// 3. Rich text / WYSIWYG: wp_kses_post
echo '<div class="hero-body">' . wp_kses_post( $body ) . '</div>';
```

> **Tip (ACF 6.2.6+):** Use the `$escape_html` argument in `get_field( $selector, $post_id, format_value: true, escape_html: true )` to enforce built-in escaping on scalar outputs.

---

## 5. Bootstrapping Dependent Features via Action Hooks

Instead of executing integration code in file scope, wait for WordPress and dependent plugins to complete their bootstrap phases:

```php
namespace Agency\Theme;

// Wait until all active plugins are loaded before checking dependencies
add_action( 'plugins_loaded', static function(): void {
    if ( class_exists( 'WooCommerce' ) ) {
        // Safe to instantiate WooCommerce custom hooks
        WooCommerce\Customizer::init();
    }

    if ( did_action( 'elementor/loaded' ) ) {
        // Safe to register Elementor custom controls and categories
        Elementor\ExtensionManager::init();
    }
} );
```
