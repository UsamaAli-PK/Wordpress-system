---
topic: Elementor Widget Base and Rendering Lifecycle
source_url: https://developers.elementor.com/docs/widgets/widget-structure
last_synced: 2026-11-20
---

# Elementor Widget Base & Rendering Lifecycle

## 1. Extending `\Elementor\Widget_Base`

Every custom Elementor widget extends `\Elementor\Widget_Base` and defines required metadata methods identifying the widget in the Elementor catalog:

```php
declare(strict_types=1);

namespace Agency\Addons\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

final class InteractiveCardWidget extends Widget_Base {
    public function get_name(): string {
        return 'agency_interactive_card';
    }

    public function get_title(): string {
        return esc_html__( 'Interactive Feature Card', 'agency-addons' );
    }

    public function get_icon(): string {
        return 'eicon-info-box';
    }

    public function get_categories(): array {
        return [ 'agency-category' ];
    }

    public function get_keywords(): array {
        return [ 'card', 'box', 'feature', 'interactive' ];
    }

    protected function register_controls(): void {
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__( 'Card Content', 'agency-addons' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'card_title',
            [
                'label'       => esc_html__( 'Title', 'agency-addons' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Performance First', 'agency-addons' ),
                'placeholder' => esc_html__( 'Enter card title', 'agency-addons' ),
            ]
        );

        $this->add_control(
            'card_description',
            [
                'label'       => esc_html__( 'Description', 'agency-addons' ),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => esc_html__( 'Built with modern PHP 8+ and minimal dependencies.', 'agency-addons' ),
            ]
        );

        $this->add_control(
            'card_link',
            [
                'label'       => esc_html__( 'Link', 'agency-addons' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => 'https://your-link.com',
            ]
        );

        $this->end_controls_section();
    }
```

---

## 2. Server-Side Frontend Rendering (`render()`)

The `render()` method outputs the final HTML markup on the live frontend. Always fetch computed settings via `$this->get_settings_for_display()`, which resolves dynamic tags and responsive controls.

### Using Render Attributes & Strict Escaping

```php
    protected function render(): void {
        $settings = $this->get_settings_for_display();

        if ( empty( $settings['card_title'] ) ) {
            return;
        }

        // 1. Build container attributes
        $this->add_render_attribute( 'wrapper', 'class', 'agency-card-wrapper' );
        $this->add_render_attribute( 'title', 'class', 'agency-card-title' );
        $this->add_render_attribute( 'desc', 'class', 'agency-card-description' );

        // 2. Inline editing support for the Elementor Editor
        $this->add_inline_editing_attributes( 'card_title', 'none' );
        $this->add_inline_editing_attributes( 'card_description', 'basic' );

        // 3. Handle link attributes safely
        if ( ! empty( $settings['card_link']['url'] ) ) {
            $this->add_link_attributes( 'card_link', $settings['card_link'] );
        }
        ?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
            <h3 <?php echo $this->get_render_attribute_string( 'title' ); ?>>
                <?php echo esc_html( (string) $settings['card_title'] ); ?>
            </h3>
            
            <p <?php echo $this->get_render_attribute_string( 'desc' ); ?>>
                <?php echo esc_html( (string) $settings['card_description'] ); ?>
            </p>

            <?php if ( ! empty( $settings['card_link']['url'] ) ) : ?>
                <a <?php echo $this->get_render_attribute_string( 'card_link' ); ?> class="agency-card-cta">
                    <?php esc_html_e( 'Learn More &rarr;', 'agency-addons' ); ?>
                </a>
            <?php endif; ?>
        </div>
        <?php
    }
```

---

## 3. Real-Time Editor Preview (`content_template()`)

The `content_template()` method renders the live preview inside the Elementor editor using client-side JavaScript (Backbone / Underscore templates). It prevents expensive AJAX server trips when a user types in a control:

```php
    protected function content_template(): void {
        ?>
        <#
        if ( ! settings.card_title ) {
            return;
        }

        view.addRenderAttribute( 'wrapper', 'class', 'agency-card-wrapper' );
        view.addRenderAttribute( 'title', 'class', 'agency-card-title' );
        view.addRenderAttribute( 'desc', 'class', 'agency-card-description' );

        view.addInlineEditingAttributes( 'card_title', 'none' );
        view.addInlineEditingAttributes( 'card_description', 'basic' );
        #>
        <div {{{ view.getRenderAttributeString( 'wrapper' ) }}}>
            <h3 {{{ view.getRenderAttributeString( 'title' ) }}}>
                {{ settings.card_title }}
            </h3>

            <p {{{ view.getRenderAttributeString( 'desc' ) }}}>
                {{ settings.card_description }}
            </p>

            <# if ( settings.card_link && settings.card_link.url ) { #>
                <a href="{{ settings.card_link.url }}" class="agency-card-cta">
                    Learn More &rarr;
                </a>
            <# } #>
        </div>
        <?php
    }
}
```

### Template Delimiters Reference:
- `<# ... #>`: Execute JavaScript logic (loops, conditions).
- `{{ ... }}`: Print escaped value.
- `{{{ ... }}}`: Print raw unescaped value (used for HTML attributes and render attribute strings).
