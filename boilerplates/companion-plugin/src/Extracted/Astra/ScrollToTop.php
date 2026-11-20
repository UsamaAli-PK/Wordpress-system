<?php
/**
 * Extracted Feature: Scroll To Top Button
 *
 * Lifted and decoupled from Astra Theme (inc/addons/scroll-to-top).
 * Converted to standalone module with strict PHP 8.1+, zero theme options dependency,
 * and pure native vanilla ES6 JavaScript (zero jQuery).
 *
 * @package Agency\Companion\Extracted\Astra
 */

declare(strict_types=1);

namespace Agency\Companion\Extracted\Astra;

final class ScrollToTop
{
    private string $version = '1.0.0';

    public function init(): void
    {
        add_action('wp_footer', [$this, 'render_markup']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    /**
     * Determines whether the button is active.
     */
    public function is_enabled(): bool
    {
        return (bool) apply_filters('agency_scroll_to_top_enabled', (bool) get_option('agency_scroll_to_top_enabled', true));
    }

    /**
     * Enqueue scoped CSS and vanilla JS handler.
     */
    public function enqueue_assets(): void
    {
        if (!$this->is_enabled()) {
            return;
        }

        wp_enqueue_style(
            'agency-scroll-to-top',
            AGENCY_COMPANION_URL . 'assets/css/scroll-to-top.css',
            [],
            $this->version
        );

        wp_enqueue_script(
            'agency-scroll-to-top',
            AGENCY_COMPANION_URL . 'assets/js/scroll-to-top.js',
            [],
            $this->version,
            true
        );
    }

    /**
     * Render the accessible scroll-to-top button markup.
     */
    public function render_markup(): void
    {
        if (!$this->is_enabled()) {
            return;
        }

        $position = (string) apply_filters('agency_scroll_to_top_position', 'right'); // 'right' or 'left'
        ?>
        <button id="agency-scroll-top"
                class="agency-scroll-top agency-scroll-top--<?php echo esc_attr($position); ?>"
                type="button"
                aria-label="<?php esc_attr_e('Scroll to top of page', 'agency-companion'); ?>"
                hidden>
            <svg class="agency-scroll-top__icon" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <polyline points="18 15 12 9 6 15"></polyline>
            </svg>
        </button>
        <?php
    }
}
