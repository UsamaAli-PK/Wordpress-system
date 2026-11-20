---
topic: Elementor Dynamic Tags Development
source_url: https://developers.elementor.com/docs/dynamic-tags/
last_synced: 2026-11-20
---

# Elementor Dynamic Tags Development

## 1. What Are Dynamic Tags?

Dynamic Tags enable content creators to bind native Elementor widget controls (headings, button links, images, numbers) to dynamic backend data sources instead of static text.

Common use cases include:
- Displaying Custom Post Type (CPT) custom field metadata.
- Querying Advanced Custom Fields (ACF) data dynamically.
- Generating runtime calculated numbers (e.g. cart totals, discounts, live exchange rates).
- Querying external REST APIs or CRM endpoints.

---

## 2. Registering Dynamic Tag Groups & Tags

Hook into `elementor/dynamic_tags/register` to register both custom tag groupings and tag instances.

```php
declare(strict_types=1);

namespace Agency\Addons\DynamicTags;

use Elementor\Core\DynamicTags\Manager;

add_action( 'elementor/dynamic_tags/register', static function( Manager $dynamic_tags_manager ): void {
    // 1. Register custom group
    $dynamic_tags_manager->register_group(
        'agency-tags',
        [
            'title' => esc_html__( 'Agency Studio Data', 'agency-addons' ),
        ]
    );

    // 2. Register individual dynamic tags
    require_once __DIR__ . '/tags/ProjectBudgetTag.php';
    require_once __DIR__ . '/tags/ClientCompanyTag.php';

    $dynamic_tags_manager->register( new Tags\ProjectBudgetTag() );
    $dynamic_tags_manager->register( new Tags\ClientCompanyTag() );
} );
```

---

## 3. Creating a Custom Tag Class

A Dynamic Tag class extends `\Elementor\Core\DynamicTags\Tag`:

```php
declare(strict_types=1);

namespace Agency\Addons\DynamicTags\Tags;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Modules\DynamicTags\Module;
use Elementor\Controls_Manager;

final class ProjectBudgetTag extends Tag {
    public function get_name(): string {
        return 'agency_project_budget';
    }

    public function get_title(): string {
        return esc_html__( 'Portfolio Project Budget', 'agency-addons' );
    }

    public function get_group(): array {
        return [ 'agency-tags' ];
    }

    /**
     * Define which Elementor controls can use this dynamic tag.
     * Categories: TEXT_CATEGORY, NUMBER_CATEGORY, URL_CATEGORY, IMAGE_CATEGORY, COLOR_CATEGORY.
     */
    public function get_categories(): array {
        return [
            Module::TEXT_CATEGORY,
            Module::NUMBER_CATEGORY,
        ];
    }

    /**
     * Optional settings panel for the tag itself (e.g., currency format, fallback).
     */
    protected function register_controls(): void {
        $this->add_control(
            'currency_symbol',
            [
                'label'   => esc_html__( 'Currency Symbol', 'agency-addons' ),
                'type'    => Controls_Manager::TEXT,
                'default' => '$',
            ]
        );

        $this->add_control(
            'fallback',
            [
                'label'   => esc_html__( 'Fallback Value', 'agency-addons' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Custom Quote', 'agency-addons' ),
            ]
        );
    }

    /**
     * Render dynamic output on the frontend.
     */
    public function render(): void {
        $post_id = get_the_ID();

        if ( ! $post_id ) {
            echo esc_html( (string) $this->get_settings( 'fallback' ) );
            return;
        }

        // 1. Fetch custom post meta (or via safe ACF wrapper)
        $raw_budget = get_post_meta( $post_id, 'agency_project_budget', true );

        if ( empty( $raw_budget ) || ! is_numeric( $raw_budget ) ) {
            echo esc_html( (string) $this->get_settings( 'fallback' ) );
            return;
        }

        $currency = (string) $this->get_settings( 'currency_symbol' );
        $formatted = sprintf( '%s%s', $currency, number_format( (float) $raw_budget, 0 ) );

        // Strict escaping of final output
        echo esc_html( $formatted );
    }
}
```

---

## 4. Advanced: Dynamic URL Tags

For dynamic buttons and hyperlinks, bind your tag to `Module::URL_CATEGORY` by extending `\Elementor\Core\DynamicTags\Data_Tag` and implementing `get_value()`:

```php
namespace Agency\Addons\DynamicTags\Tags;

use Elementor\Core\DynamicTags\Data_Tag;
use Elementor\Modules\DynamicTags\Module;

final class ClientPortalUrlTag extends Data_Tag {
    public function get_name(): string {
        return 'agency_client_portal_url';
    }

    public function get_title(): string {
        return esc_html__( 'Client Portal Link', 'agency-addons' );
    }

    public function get_group(): array {
        return [ 'agency-tags' ];
    }

    public function get_categories(): array {
        return [ Module::URL_CATEGORY ];
    }

    public function get_value( array $options = [] ): string {
        $client_id = get_post_meta( get_the_ID(), 'assigned_client_id', true );

        if ( ! empty( $client_id ) ) {
            return esc_url( home_url( '/portal/' . rawurlencode( (string) $client_id ) ) );
        }

        return esc_url( home_url( '/contact/' ) );
    }
}
```
