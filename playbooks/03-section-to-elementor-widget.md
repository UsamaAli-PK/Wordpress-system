---
topic: Converting Extracted Sections to Elementor Widgets
last_synced: 2026-11-20
---

# Converting Extracted HTML/PHP Sections into Modern Elementor Widgets

## 1. The Layout & DOM Parity Rule

> **Invariant:** Never redesign or refactor the HTML DOM structure during the initial extraction. 
> Preserve the exact tag hierarchy, wrapper classes, flex/grid container properties, and `data-*` attributes. 
> Layout drift occurs when developers attempt to "clean up" HTML nesting before the widget functions correctly.

---

## 2. 5-Step Conversion Workflow

```
[Extracted PHP/HTML] 
  → 1. Scaffold Widget Class
  → 2. Map Static Text & Media to Controls
  → 3. Map Repeaters & Dynamic Loops
  → 4. Map Style & Responsive Controls
  → 5. Re-wire JS to Frontend Handler
```

### Step 1: Scaffold the Widget Skeleton

Create `src/Widgets/[WidgetName]Widget.php` inside the companion plugin:

```php
declare(strict_types=1);

namespace Agency\Companion\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

final class TestimonialGridWidget extends Widget_Base {
    public function get_name(): string { return 'agency_testimonial_grid'; }
    public function get_title(): string { return esc_html__( 'Testimonial Grid', 'agency-companion' ); }
    public function get_icon(): string { return 'eicon-testimonial-carousel'; }
    public function get_categories(): array { return [ 'agency-category' ]; }
    public function get_style_depends(): array { return [ 'agency-testimonials-css' ]; }
    public function get_script_depends(): array { return [ 'agency-testimonials-js' ]; }
```

### Step 2 & 3: Map Static Content & Repeaters to Controls

Replace hardcoded items with Elementor controls in `register_controls()`:

```php
    protected function register_controls(): void {
        // CONTENT TAB: Section Settings
        $this->start_controls_section( 'content_section', [
            'label' => esc_html__( 'Content', 'agency-companion' ),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ] );

        $this->add_control( 'section_title', [
            'label'   => esc_html__( 'Heading', 'agency-companion' ),
            'type'    => Controls_Manager::TEXT,
            'default' => esc_html__( 'Trusted by Industry Leaders', 'agency-companion' ),
        ] );

        // REPEATER: Testimonial Cards
        $repeater = new Repeater();
        $repeater->add_control( 'client_quote', [
            'label' => esc_html__( 'Quote', 'agency-companion' ),
            'type'  => Controls_Manager::TEXTAREA,
        ] );
        $repeater->add_control( 'client_name', [
            'label' => esc_html__( 'Name', 'agency-companion' ),
            'type'  => Controls_Manager::TEXT,
        ] );
        $repeater->add_control( 'client_avatar', [
            'label'   => esc_html__( 'Avatar', 'agency-companion' ),
            'type'    => Controls_Manager::MEDIA,
            'default' => [ 'url' => \Elementor\Utils::get_placeholder_image_src() ],
        ] );

        $this->add_control( 'testimonials', [
            'label'       => esc_html__( 'Testimonials', 'agency-companion' ),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'title_field' => '{{{ client_name }}}',
        ] );

        $this->end_controls_section();
```

### Step 4: Map Visual Styling to Style Tab Controls

```php
        // STYLE TAB: Item Styling
        $this->start_controls_section( 'style_section', [
            'label' => esc_html__( 'Typography & Colors', 'agency-companion' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'quote_typography',
            'selector' => '{{WRAPPER}} .agency-testimonial-quote',
        ] );

        $this->add_control( 'quote_color', [
            'label'     => esc_html__( 'Quote Color', 'agency-companion' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .agency-testimonial-quote' => 'color: {{VALUE}};' ],
        ] );

        $this->add_responsive_control( 'card_padding', [
            'label'      => esc_html__( 'Card Padding', 'agency-companion' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em', 'rem' ],
            'selectors'  => [
                '{{WRAPPER}} .agency-testimonial-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ] );

        $this->end_controls_section();
    }
```

### Step 5: Render with DOM Parity & Escaping

```php
    protected function render(): void {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="agency-testimonials-wrapper">
            <?php if ( ! empty( $settings['section_title'] ) ) : ?>
                <h2 class="agency-section-heading"><?php echo esc_html( (string) $settings['section_title'] ); ?></h2>
            <?php endif; ?>

            <div class="agency-testimonials-grid">
                <?php foreach ( $settings['testimonials'] as $item ) : ?>
                    <article class="agency-testimonial-card">
                        <blockquote class="agency-testimonial-quote">
                            <?php echo wp_kses_post( (string) ( $item['client_quote'] ?? '' ) ); ?>
                        </blockquote>
                        <div class="agency-client-meta">
                            <?php if ( ! empty( $item['client_avatar']['url'] ) ) : ?>
                                <img src="<?php echo esc_url( (string) $item['client_avatar']['url'] ); ?>" 
                                     alt="<?php echo esc_attr( (string) ( $item['client_name'] ?? '' ) ); ?>" 
                                     class="agency-client-avatar" />
                            <?php endif; ?>
                            <span class="agency-client-name"><?php echo esc_html( (string) ( $item['client_name'] ?? '' ) ); ?></span>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
```

---

## 3. Re-Wiring JS from `document.ready` to Elementor Handlers

Legacy themes initialize scripts via `$(document).ready()`. In Elementor, this fails inside the editor preview when widgets are dragged or edited.

### The Conversion:
```javascript
// ❌ OLD THEME CODE (Breaks in Elementor Editor preview)
jQuery(document).ready(function($) {
    $('.agency-testimonials-grid').slick();
});

// ✅ MODERN ELEMENTOR HANDLER (Re-mounts seamlessly on edit)
class TestimonialsHandler extends elementorModules.frontend.handlers.Base {
    onInit() {
        super.onInit();
        this.initSlider();
    }

    initSlider() {
        const $grid = this.$element.find('.agency-testimonials-grid');
        if (!$grid.length) return;
        // Initialize carousel / layout engine...
    }

    onElementChange(propertyName) {
        this.initSlider(); // Live update when controls change
    }
}

jQuery(window).on('elementor/frontend/init', () => {
    elementorFrontend.elementsHandler.addHandler(TestimonialsHandler, {
        $element: jQuery('.agency-testimonials-wrapper')
    });
});
```
