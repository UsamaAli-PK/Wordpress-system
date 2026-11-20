---
topic: Controls, Repeaters, and Styling Engine
source_url: https://developers.elementor.com/docs/editor-controls/
last_synced: 2026-11-20
---

# Controls, Repeaters, and Styling Engine

## 1. Organizing Tabs & Sections

Elementor categorizes controls into three core tabs:
- `Controls_Manager::TAB_CONTENT`: Data inputs, texts, links, images, repeaters.
- `Controls_Manager::TAB_STYLE`: Visual presentation, colors, typography, paddings.
- `Controls_Manager::TAB_ADVANCED`: Layout dimensions, positioning, motion effects (injected automatically).

```php
declare(strict_types=1);

namespace Agency\Addons\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Repeater;

final class FeatureListWidget extends Widget_Base {
    // ... metadata methods ...

    protected function register_controls(): void {
        $this->register_content_tab();
        $this->register_style_tab();
    }
```

---

## 2. Repeater Controls (`\Elementor\Repeater`)

Repeaters allow authors to add an arbitrary number of sub-items (e.g., pricing features, accordion tabs, team members):

```php
    private function register_content_tab(): void {
        $this->start_controls_section(
            'section_features',
            [
                'label' => esc_html__( 'Features List', 'agency-addons' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'feature_text',
            [
                'label'       => esc_html__( 'Feature Title', 'agency-addons' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Premium Cloud Hosting', 'agency-addons' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'feature_highlight',
            [
                'label'        => esc_html__( 'Highlight Feature?', 'agency-addons' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'agency-addons' ),
                'label_off'    => esc_html__( 'No', 'agency-addons' ),
                'return_value' => 'yes',
                'default'      => 'no',
            ]
        );

        $this->add_control(
            'features',
            [
                'label'       => esc_html__( 'Items', 'agency-addons' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [ 'feature_text' => 'Unlimited Bandwidth' ],
                    [ 'feature_text' => 'Automated Daily Backups' ],
                ],
                'title_field' => '{{{ feature_text }}}',
            ]
        );

        $this->end_controls_section();
    }
```

---

## 3. Responsive Controls & CSS `selectors` Engine

Elementor injects dynamic CSS rules compiled directly from control values. Use `{{WRAPPER}}` to target the widget root wrapper without hardcoding classes.

### Responsive Alignment & Spacing

```php
    private function register_style_tab(): void {
        $this->start_controls_section(
            'section_style_items',
            [
                'label' => esc_html__( 'Items Styling', 'agency-addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        // Responsive control generates desktop, tablet, and mobile CSS rules
        $this->add_responsive_control(
            'item_alignment',
            [
                'label'     => esc_html__( 'Alignment', 'agency-addons' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [ 'title' => esc_html__( 'Left', 'agency-addons' ), 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => esc_html__( 'Center', 'agency-addons' ), 'icon' => 'eicon-text-align-center' ],
                    'right'  => [ 'title' => esc_html__( 'Right', 'agency-addons' ), 'icon' => 'eicon-text-align-right' ],
                ],
                'default'   => 'left',
                'selectors' => [
                    '{{WRAPPER}} .agency-feature-item' => 'text-align: {{VALUE}};',
                ],
            ]
        );
```

---

## 4. Modern Group Controls (Deprecation Replacements)

> **Forbidden APIs:** Never use `\Elementor\Scheme_Color` or `\Elementor\Scheme_Typography`. In Elementor 3.x+, use **Group Controls** or standard color controls, which automatically integrate with Elementor Kit Global Styles.

```php
        // Group Control: Typography (Font Family, Size, Weight, Transform, Line Height)
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'item_typography',
                'selector' => '{{WRAPPER}} .agency-feature-text',
            ]
        );

        // Standard Color Control (Automatically supports Elementor Global Colors)
        $this->add_control(
            'item_color',
            [
                'label'     => esc_html__( 'Text Color', 'agency-addons' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .agency-feature-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Group Control: Box Shadow
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'item_box_shadow',
                'selector' => '{{WRAPPER}} .agency-feature-item',
            ]
        );

        // Group Control: Border
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'item_border',
                'selector' => '{{WRAPPER}} .agency-feature-item',
            ]
        );

        $this->end_controls_section();
    }
```

---

## 5. Conditional Display Rules (`condition`)

Conditionally show or hide controls in the editor panel based on the values of other controls:

```php
$this->add_control(
    'badge_text',
    [
        'label'     => esc_html__( 'Badge Text', 'agency-addons' ),
        'type'      => Controls_Manager::TEXT,
        'default'   => 'Popular',
        'condition' => [
            'show_badge' => 'yes', // Only displays if 'show_badge' switcher is toggled 'yes'
        ],
    ]
);

// Multiple condition requirements
$this->add_control(
    'custom_height',
    [
        'label'     => esc_html__( 'Custom Height', 'agency-addons' ),
        'type'      => Controls_Manager::NUMBER,
        'condition' => [
            'layout_style' => 'custom',
            'show_badge!'  => '', // layout_style is 'custom' AND show_badge is not empty
        ],
    ]
);
```
