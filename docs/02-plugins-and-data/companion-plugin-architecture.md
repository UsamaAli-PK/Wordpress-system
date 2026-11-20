---
topic: Companion Plugin Architecture
source_url: https://developer.wordpress.org/plugins/intro/
last_synced: 2026-11-20
---

# Companion Plugin Architecture

## 1. Why a Companion Plugin? (Separation of Concerns)

In modern WordPress development, functionality and presentation must remain strictly decoupled:

- **Theme Scope (Presentation):** HTML markup, CSS styling, layout templates, responsive behavior, and UI interactions.
- **Plugin Scope (Functionality & Data):** Custom Post Types (CPTs), custom taxonomies, shortcodes, REST API endpoints, business logic, and custom Elementor widgets.

> **The Golden Rule:** If a user switches the theme, their content and data (portfolio items, case studies, team members, custom taxonomies) must not disappear. Data belongs in the companion plugin.

---

## 2. Standard Plugin Header

The main plugin file (e.g., `my-companion-plugin.php`) requires a standardized header comment declaring compatibility:

```php
<?php
/**
 * Plugin Name:       Agency Companion Plugin
 * Plugin URI:        https://example.com/plugins/companion
 * Description:       Core CPTs, taxonomies, shortcodes, and Elementor extensions for Agency Theme.
 * Version:           1.0.0
 * Requires at least: 6.4
 * Requires PHP:      8.1
 * Author:            Development Team
 * Author URI:        https://example.com
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       agency-companion
 * Domain Path:       /languages
 */

declare(strict_types=1);

namespace Agency\Companion;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
```

---

## 3. Modern Directory Structure & PSR-4

A scalable, maintainable structure organizing code logically:

```text
agency-companion/
├── agency-companion.php        # Entry bootstrap & activation hooks
├── uninstall.php               # Complete data cleanup on deletion
├── composer.json               # PSR-4 autoloading definitions
├── src/
│   ├── Plugin.php              # Main orchestrator (singleton/container)
│   ├── PostTypes/
│   │   ├── PortfolioCPT.php
│   │   └── TeamCPT.php
│   ├── Taxonomies/
│   │   └── ProjectTypeTaxonomy.php
│   ├── Shortcodes/
│   │   └── PricingTableShortcode.php
│   └── Elementor/
│       └── ElementorManager.php
└── assets/
    ├── css/
    └── js/
```

### PSR-4 Autoloading (`composer.json`)

```json
{
  "name": "agency/companion-plugin",
  "description": "Companion functionality plugin",
  "type": "wordpress-plugin",
  "license": "GPL-2.0-or-later",
  "autoload": {
    "psr-4": {
      "Agency\\Companion\\": "src/"
    }
  },
  "config": {
    "optimize-autoloader": true
  }
}
```

---

## 4. Activation, Deactivation & Uninstallation

WordPress provides distinct lifecycle hooks for plugin state changes:

```php
namespace Agency\Companion;

// Bootstrap class autoloader
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
    require_once __DIR__ . '/vendor/autoload.php';
}

/**
 * Plugin Activation Handler
 */
function activate(): void {
    // 1. Register post types and taxonomies first
    PostTypes\PortfolioCPT::register();
    Taxonomies\ProjectTypeTaxonomy::register();

    // 2. Flush rewrite rules (ONLY run inside activation!)
    flush_rewrite_rules();

    // 3. Set default options if needed
    if ( ! get_option( 'agency_companion_version' ) ) {
        add_option( 'agency_companion_version', '1.0.0' );
    }
}
register_activation_hook( __FILE__, __NAMESPACE__ . '\\activate' );

/**
 * Plugin Deactivation Handler
 */
function deactivate(): void {
    // Flush rewrite rules on deactivation to clean up CPT endpoints
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, __NAMESPACE__ . '\\deactivate' );
```

### Clean Uninstallation (`uninstall.php`)

Never delete user content or drop tables in deactivation hooks; use `uninstall.php` only when the user explicitly deletes the plugin:

```php
<?php
/**
 * Uninstallation cleanup script.
 */

declare(strict_types=1);

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

// Remove plugin-specific options
delete_option( 'agency_companion_version' );
delete_option( 'agency_companion_settings' );

// Clean transients
delete_transient( 'agency_portfolio_cache' );
```

---

## 5. Main Bootstrap Class

```php
namespace Agency\Companion;

final class Plugin {
    private static ?self $instance = null;

    public static function instance(): self {
        return self::$instance ??= new self();
    }

    private function __construct() {
        $this->init_hooks();
    }

    private function init_hooks(): void {
        add_action( 'init', [ PostTypes\PortfolioCPT::class, 'register' ] );
        add_action( 'init', [ Taxonomies\ProjectTypeTaxonomy::class, 'register' ] );
        add_action( 'init', [ Shortcodes\PricingTableShortcode::class, 'register' ] );

        // Elementor integration check
        add_action( 'plugins_loaded', [ $this, 'load_integrations' ] );
    }

    public function load_integrations(): void {
        if ( did_action( 'elementor/loaded' ) ) {
            Elementor\ElementorManager::init();
        }
    }
}

// Initialize the plugin
Plugin::instance();
```
