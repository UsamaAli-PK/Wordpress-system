<?php
/**
 * Plugin Name: Agency Companion Plugin
 * Plugin URI: https://github.com/agency/agency-companion
 * Description: Production companion plugin providing custom post types, taxonomies, modern Elementor 3.x widgets, and decoupled extracted theme features.
 * Version: 1.0.0
 * Author: Agency Architecture Team
 * Author URI: https://agency.dev
 * Text Domain: agency-companion
 * Domain Path: /languages
 * Requires at least: 6.2
 * Requires PHP: 8.1
 * License: GPL-2.0-or-later
 *
 * @package Agency\Companion
 */

declare(strict_types=1);

namespace Agency\Companion;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

define('AGENCY_COMPANION_VERSION', '1.0.0');
define('AGENCY_COMPANION_FILE', __FILE__);
define('AGENCY_COMPANION_DIR', plugin_dir_path(__FILE__));
define('AGENCY_COMPANION_URL', plugin_dir_url(__FILE__));

/**
 * Lightweight PSR-4 Autoloader for Agency\Companion\ namespace.
 */
spl_autoload_register(function (string $class): void {
    $prefix = 'Agency\\Companion\\';
    $len    = strlen($prefix);

    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file           = AGENCY_COMPANION_DIR . 'src/' . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

/**
 * Register activation and deactivation lifecycle hooks.
 */
register_activation_hook(__FILE__, function (): void {
    Plugin::get_instance()->activate();
});

register_deactivation_hook(__FILE__, function (): void {
    Plugin::get_instance()->deactivate();
});

/**
 * Bootstrap plugin on plugins_loaded.
 */
add_action('plugins_loaded', function (): void {
    Plugin::get_instance()->init();
});
