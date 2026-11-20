---
topic: Hooks: Actions and Filters
source_url: https://developer.wordpress.org/plugins/hooks/
last_synced: 2026-11-20
---

# WordPress Hooks: Actions and Filters

## 1. Actions vs. Filters

WordPress operates on an event-driven architecture using two types of hooks:

| Hook Type | Purpose | Return Value | Core Functions |
| :--- | :--- | :--- | :--- |
| **Action** | Trigger side-effects (logging, emails, outputting markup, saving meta) | None (ignored) | `add_action()`, `do_action()` |
| **Filter** | Intercept and transform data before usage | Must return modified variable | `add_filter()`, `apply_filters()` |

---

## 2. Actions: Execution & Triggering

### Firing an Action (`do_action`)

```php
declare(strict_types=1);

namespace Agency\Core;

function process_order( int $order_id, float $total ): void {
    // Business logic...

    // Allow third parties to hook into the event
    do_action( 'agency_after_order_processed', $order_id, $total );
}
```

### Attaching to an Action (`add_action`)

```php
add_action(
    hook_name: 'agency_after_order_processed',
    callback: function( int $order_id, float $total ): void {
        error_log( "Order {$order_id} completed with total: \${$total}" );
    },
    priority: 10,
    accepted_args: 2
);
```

> **Critical:** Always specify `$accepted_args` if your callback expects more than 1 argument (default is `1`).

---

## 3. Filters: Data Transformation

### Applying a Filter (`apply_filters`)

Always pass the value to modify as the first parameter; subsequent parameters act as read-only context:

```php
function render_price( float $amount, string $currency = 'USD' ): string {
    $formatted = sprintf( '%s %.2f', $currency, $amount );

    // Pass $formatted as subject, $amount and $currency as context
    return apply_filters( 'agency_formatted_price', $formatted, $amount, $currency );
}
```

### Hooking into a Filter (`add_filter`)

```php
add_filter(
    'agency_formatted_price',
    static function( string $formatted, float $amount, string $currency ): string {
        if ( 'EUR' === $currency ) {
            return sprintf( '€%.2f', $amount );
        }
        return $formatted;
    },
    priority: 10,
    accepted_args: 3
);
```

> **Filter Invariant:** A filter callback **MUST** always return the expected type (or unmodified value), otherwise data downstream breaks.

---

## 4. Priority and Execution Order

Hooks run in ascending numeric order:

```php
// Runs first
add_action( 'init', 'setup_early_dependencies', 5 );

// Default priority (10)
add_action( 'init', 'register_custom_post_types', 10 );

// Runs late
add_action( 'init', 'register_overrides', 99 );
```

---

## 5. Removing Third-Party Hooks

To unhook an action or filter, your `remove_*` call must match the exact **hook name**, **callback**, and **priority** used during registration.

### Removing Procedural Functions

```php
// Remove core or third-party procedural function
remove_action( 'wp_head', 'wp_generator', 10 );
```

### Removing Class Methods (Static vs. Instance)

**Static Method:**
```php
// Registration: add_action( 'init', [ ThirdPartyClass::class, 'setup' ], 10 );
remove_action( 'init', [ ThirdPartyClass::class, 'setup' ], 10 );
```

**Instance Method (Singleton or Global Object):**
```php
// When the instance is stored in a global or static accessor:
if ( class_exists( 'ThirdParty_Plugin' ) ) {
    $instance = \ThirdParty_Plugin::instance();
    remove_action( 'wp_footer', [ $instance, 'inject_tracking' ], 15 );
}
```

### The Anonymous Function / Closure Trap

Never register hooks with anonymous functions if child themes or third parties might need to unhook them:

```php
// ❌ BAD: Impossible for other developers to remove without complex reflection hacks
add_action( 'wp_head', function() {
    echo '<meta name="custom" content="value" />';
} );

// ✅ GOOD: Accessible, testable, and removable
class SEOHead {
    public static function inject_meta(): void {
        echo '<meta name="custom" content="value" />';
    }
}
add_action( 'wp_head', [ SEOHead::class, 'inject_meta' ], 10 );
// Removable by: remove_action( 'wp_head', [ SEOHead::class, 'inject_meta' ], 10 );
```

---

## 6. Timing Removal with `init` / `wp_loaded`

You cannot remove a hook before it has been added. Wrap removal calls inside a hook that fires after registration:

```php
add_action( 'init', static function(): void {
    // Unhook WooCommerce default breadcrumbs on single product
    remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
}, 15 );
```
