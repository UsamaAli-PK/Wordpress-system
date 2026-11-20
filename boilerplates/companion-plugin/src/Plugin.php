<?php
/**
 * Core Plugin Singleton
 *
 * @package Agency\Companion
 */

declare(strict_types=1);

namespace Agency\Companion;

use Agency\Companion\CPT\PostTypeRegistry;
use Agency\Companion\Elementor\WidgetLoader;
use Agency\Companion\Extracted\Astra\ScrollToTop;

final class Plugin
{
    private static ?self $instance = null;
    private PostTypeRegistry $post_types;
    private WidgetLoader $widget_loader;
    private ScrollToTop $scroll_to_top;

    public static function get_instance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->post_types    = new PostTypeRegistry();
        $this->widget_loader = new WidgetLoader();
        $this->scroll_to_top = new ScrollToTop();
    }

    /**
     * Initialize subsystems on plugins_loaded.
     */
    public function init(): void
    {
        // Load text domain.
        load_plugin_textdomain('agency-companion', false, dirname(plugin_basename(AGENCY_COMPANION_FILE)) . '/languages');

        // Bootstrap modules.
        $this->post_types->init();
        $this->widget_loader->init();
        $this->scroll_to_top->init();

        // Check for deferred rewrite flush after activation.
        if (get_transient('agency_companion_flush_rewrites')) {
            delete_transient('agency_companion_flush_rewrites');
            flush_rewrite_rules(false);
        }
    }

    /**
     * Runs on plugin activation.
     */
    public function activate(): void
    {
        // Register post types and defer flush to next load.
        $this->post_types->register_all();
        set_transient('agency_companion_flush_rewrites', true, 60);
    }

    /**
     * Runs on plugin deactivation.
     */
    public function deactivate(): void
    {
        flush_rewrite_rules(false);
    }
}
