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
        add_action('elementor/elements/categories_registered', [$this, 'register_categories']);
        add_action('elementor/widgets/register', [$this, 'register_widgets']);
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
        // Example: $widgets_manager->register(new \Agency\Companion\Elementor\Widgets\SampleWidget());
    }
}
