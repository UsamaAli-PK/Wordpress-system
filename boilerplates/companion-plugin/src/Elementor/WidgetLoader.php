<?php
/**
 * Elementor Widget Loader & Category Registrar
 *
 * @package Agency\Companion\Elementor
 */

declare(strict_types=1);

namespace Agency\Companion\Elementor;

final class WidgetLoader
{
    public function init(): void
    {
        add_action('wp_enqueue_scripts', [$this, 'register_frontend_assets']);
        add_action('elementor/frontend/after_register_styles', [$this, 'register_frontend_assets']);
        add_action('elementor/frontend/after_register_scripts', [$this, 'register_frontend_assets']);
        add_action('elementor/elements/categories_registered', [$this, 'register_categories']);
        add_action('elementor/widgets/register', [$this, 'register_widgets']);
    }

    /**
     * Register scripts and styles for on-demand enqueuing by Elementor.
     */
    public function register_frontend_assets(): void
    {
        $version = defined('AGENCY_COMPANION_VERSION') ? AGENCY_COMPANION_VERSION : '1.0.0';
        $base_url = defined('AGENCY_COMPANION_URL') ? AGENCY_COMPANION_URL : plugin_dir_url(dirname(__DIR__, 1));

        wp_register_style(
            'travelin-journey-css',
            $base_url . 'assets/css/travelin/travelin-journey.css',
            [],
            $version
        );

        wp_register_style(
            'travelin-reviews-css',
            $base_url . 'assets/css/travelin/travelin-reviews.css',
            [],
            $version
        );

        wp_register_script(
            'travelin-frontend-js',
            $base_url . 'assets/js/travelin/travelin-frontend.js',
            ['jquery'],
            $version,
            true
        );
    }

    /**
     * Register custom widget category in the Elementor sidebar.
     *
     * @param \Elementor\Elements_Manager $elements_manager
     */
    public function register_categories($elements_manager): void
    {
        $elements_manager->add_category(
            'agency-elements',
            [
                'title' => esc_html__('Agency Elements', 'agency-companion'),
                'icon'  => 'fa fa-plug',
            ]
        );
    }

    /**
     * Register modern Elementor 3.5+ widgets.
     *
     * @param \Elementor\Widgets_Manager $widgets_manager
     */
    public function register_widgets($widgets_manager): void
    {
        $widgets = [
            Widgets\Travelin\Widget_Travelin_Hero::class,
            Widgets\Travelin\Widget_Travelin_Pillars::class,
            Widgets\Travelin\Widget_Travelin_Experience::class,
            Widgets\Travelin\Widget_Travelin_Trips_Carousel::class,
            Widgets\Travelin\Widget_Travelin_Stories_Reels::class,
            Widgets\Travelin\Widget_Travelin_Google_Reviews::class,
            Widgets\Travelin\Widget_Travelin_Host_Bio::class,
            Widgets\Travelin\Widget_Travelin_Moments_Strip::class,
            Widgets\Travelin\Widget_Travelin_FAQ::class,
            Widgets\Travelin\Widget_Travelin_Booking_Steps::class,
            Widgets\Travelin\Widget_Travelin_Final_CTA::class,
        ];

        foreach ($widgets as $widget_class) {
            if (class_exists($widget_class)) {
                $widgets_manager->register(new $widget_class());
            }
        }
    }
}
