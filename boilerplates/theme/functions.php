<?php
/**
 * Base Classic Theme Functions & Definitions
 *
 * @package BaseClassic
 * @since 1.0.0
 */

declare(strict_types=1);

namespace BaseClassic;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

define('BASE_CLASSIC_VERSION', '1.0.0');
define('BASE_CLASSIC_DIR', get_template_directory());
define('BASE_CLASSIC_URI', get_template_directory_uri());

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function setup_theme(): void
{
    // Let WordPress manage the document title.
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails on posts and pages.
    add_theme_support('post-thumbnails');

    // Switch default core markup to output valid HTML5.
    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ]);

    // Declare explicit Elementor support.
    add_theme_support('elementor');
    add_theme_support('align-wide');

    // Register navigation menus.
    register_nav_menus([
        'primary' => esc_html__('Primary Navigation', 'base-classic'),
        'footer'  => esc_html__('Footer Navigation', 'base-classic'),
    ]);
}
add_action('after_setup_theme', __NAMESPACE__ . '\\setup_theme');

/**
 * Register Elementor Pro & Core Theme Locations.
 *
 * Allows Elementor Theme Builder templates to override header, footer,
 * single, and archive templates while providing seamless PHP fallbacks.
 *
 * @param \ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $elementor_theme_manager
 */
function register_elementor_locations($elementor_theme_manager): void
{
    $elementor_theme_manager->register_all_core_location();
}
add_action('elementor/theme/register_locations', __NAMESPACE__ . '\\register_elementor_locations');

/**
 * Enqueue stylesheets and theme assets.
 */
function enqueue_scripts(): void
{
    // Design tokens & CSS custom property bridge.
    wp_enqueue_style(
        'base-classic-variables',
        BASE_CLASSIC_URI . '/assets/css/theme-variables.css',
        [],
        BASE_CLASSIC_VERSION
    );

    // Main stylesheet.
    wp_enqueue_style(
        'base-classic-style',
        get_stylesheet_uri(),
        ['base-classic-variables'],
        BASE_CLASSIC_VERSION
    );
}
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_scripts');
