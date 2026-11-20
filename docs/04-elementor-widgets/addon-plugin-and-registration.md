---
topic: Elementor Addon Plugin Bootstrap & Registration
source_url: https://developers.elementor.com/docs/getting-started/first-addon
last_synced: 2026-11-20
---

# Elementor Addon Plugin Bootstrap & Registration

## 1. Modern Architecture Invariants (Elementor 3.x+)

When developing addons and custom widgets for Elementor 3.x+, strictly observe the following API deprecations:

| Banned / Deprecated API | Modern Elementor 3.x Replacement |
| :--- | :--- |
| `elementor/widgets/widgets_registered` hook | `elementor/widgets/register` |
| `\Elementor\Plugin::instance()->widgets_manager->register_widget_type()` | `$widgets_manager->register( new Widget_Class() )` |
| `\Elementor\Scheme_Color` | Elementor Global Kit Colors |
| `\Elementor\Scheme_Typography` | Elementor Global Kit Typography / Group Control |

---

## 2. Companion Addon Plugin Bootstrap

A robust addon checks for Elementor's presence and minimum version requirements before registering components:

```php
<?php
/**
 * Plugin Name:       Agency Elementor Addons
 * Plugin URI:        https://example.com/plugins/agency-addons
 * Description:       Custom widgets and dynamic tags for Agency Theme.
 * Version:           1.0.0
 * Requires at least: 6.4
 * Requires PHP:      8.1
 * Author:            Development Team
 * Text Domain:       agency-addons
 */

declare(strict_types=1);

namespace Agency\Addons;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

final class Plugin {
    public const VERSION = '1.0.0';
    public const MINIMUM_ELEMENTOR_VERSION = '3.20.0';
    public const MINIMUM_PHP_VERSION = '8.1';

    private static ?self $instance = null;

    public static function instance(): self {
        return self::$instance ??= new self();
    }

    private function __construct() {
        add_action( 'plugins_loaded', [ $this, 'on_plugins_loaded' ] );
    }

    public function on_plugins_loaded(): void {
        if ( ! $this->is_compatible() ) {
            return;
        }

        // 1. Register custom categories
        add_action( 'elementor/elements/categories_registered', [ $this, 'register_categories' ] );

        // 2. Register custom widgets
        add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
    }

    public function is_compatible(): bool {
        // Verify Elementor is installed and loaded
        if ( ! did_action( 'elementor/loaded' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_missing_elementor' ] );
            return false;
        }

        // Verify Elementor version
        if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_version_mismatch' ] );
            return false;
        }

        return true;
    }

    public function register_categories( \Elementor\Elements_Manager $elements_manager ): void {
        $elements_manager->add_category(
            'agency-category',
            [
                'title' => esc_html__( 'Agency Studio Widgets', 'agency-addons' ),
                'icon'  => 'eicon-apps',
            ]
        );
    }

    public function register_widgets( \Elementor\Widgets_Manager $widgets_manager ): void {
        require_once __DIR__ . '/widgets/PortfolioGridWidget.php';
        require_once __DIR__ . '/widgets/InteractiveCardWidget.php';

        $widgets_manager->register( new Widgets\PortfolioGridWidget() );
        $widgets_manager->register( new Widgets\InteractiveCardWidget() );
    }

    public function admin_notice_missing_elementor(): void {
        ?>
        <div class="notice notice-warning is-dismissible">
            <p><?php esc_html_e( 'Agency Elementor Addons requires Elementor to be active.', 'agency-addons' ); ?></p>
        </div>
        <?php
    }

    public function admin_notice_version_mismatch(): void {
        ?>
        <div class="notice notice-error is-dismissible">
            <p><?php esc_html_e( 'Agency Elementor Addons requires Elementor version 3.20.0 or higher.', 'agency-addons' ); ?></p>
        </div>
        <?php
    }
}

Plugin::instance();
```

---

## 3. Directory Layout

```text
agency-elementor-addons/
├── agency-elementor-addons.php    # Plugin entry file & bootstrap
├── widgets/
│   ├── PortfolioGridWidget.php    # Custom widget class extending Widget_Base
│   └── InteractiveCardWidget.php
├── assets/
│   ├── css/
│   │   └── interactive-card.css
│   └── js/
│       └── interactive-card-handler.js
└── dynamic-tags/
    └── ProjectBudgetTag.php       # Dynamic tag class extending Tag
```
