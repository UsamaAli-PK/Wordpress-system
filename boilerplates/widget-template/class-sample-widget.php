<?php
/**
 * Canonical Sample Elementor Widget Boilerplate
 *
 * @package Agency\Companion\Elementor\Widgets
 */

declare(strict_types=1);

namespace Agency\Companion\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if (!defined('ABSPATH')) {
    exit;
}

class SampleWidget extends Widget_Base
{
    public function get_name(): string
    {
        return 'agency_feature_card';
    }

    public function get_title(): string
    {
        return esc_html__('Agency Feature Card', 'agency-companion');
    }

    public function get_icon(): string
    {
        return 'eicon-info-box';
    }

    public function get_categories(): array
    {
        return ['agency-elements'];
    }

    public function get_keywords(): array
    {
        return ['feature', 'card', 'box', 'agency', 'service'];
    }

    public function get_script_depends(): array
    {
        return ['agency-sample-widget-handler'];
    }

    public function get_style_depends(): array
    {
        return ['agency-sample-widget-style'];
    }

    protected function register_controls(): void
    {
        // -------------------------------------------------------------
        // SECTION: Content
        // -------------------------------------------------------------
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Card Content', 'agency-companion'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'card_title',
            [
                'label'       => esc_html__('Title', 'agency-companion'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('High-Performance Engineering', 'agency-companion'),
                'placeholder' => esc_html__('Enter title...', 'agency-companion'),
                'label_block' => true,
                'dynamic'     => ['active' => true],
            ]
        );

        $this->add_control(
            'card_description',
            [
                'label'       => esc_html__('Description', 'agency-companion'),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => esc_html__('Tailored digital solutions built on modern standards, robust performance, and pixel precision.', 'agency-companion'),
                'placeholder' => esc_html__('Enter description...', 'agency-companion'),
                'rows'        => 4,
                'dynamic'     => ['active' => true],
            ]
        );

        $this->add_control(
            'card_link',
            [
                'label'       => esc_html__('Card Link', 'agency-companion'),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-link.com', 'agency-companion'),
                'default'     => ['url' => ''],
                'dynamic'     => ['active' => true],
            ]
        );

        // Repeater for Checklist Items
        $repeater = new Repeater();

        $repeater->add_control(
            'item_text',
            [
                'label'       => esc_html__('Item Text', 'agency-companion'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Verified Architecture', 'agency-companion'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'features_list',
            [
                'label'       => esc_html__('Feature Highlights', 'agency-companion'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    ['item_text' => esc_html__('Zero Bloat Classic Theme', 'agency-companion')],
                    ['item_text' => esc_html__('Decoupled Companion Plugin', 'agency-companion')],
                    ['item_text' => esc_html__('Native Elementor Container JSON', 'agency-companion')],
                ],
                'title_field' => '{{{ item_text }}}',
            ]
        );

        $this->end_controls_section();

        // -------------------------------------------------------------
        // SECTION: Styling
        // -------------------------------------------------------------
        $this->start_controls_section(
            'section_style_card',
            [
                'label' => esc_html__('Card Container', 'agency-companion'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'card_bg_color',
            [
                'label'     => esc_html__('Background Color', 'agency-companion'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .agency-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_padding',
            [
                'label'      => esc_html__('Padding', 'agency-companion'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'rem', '%'],
                'default'    => [
                    'top'      => '2',
                    'bottom'   => '2',
                    'left'     => '2',
                    'right'    => '2',
                    'unit'     => 'rem',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .agency-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'card_border',
                'selector' => '{{WRAPPER}} .agency-card',
            ]
        );

        $this->add_responsive_control(
            'card_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'agency-companion'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'rem', '%'],
                'default'    => [
                    'top'      => '12',
                    'bottom'   => '12',
                    'left'     => '12',
                    'right'    => '12',
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .agency-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'card_shadow',
                'selector' => '{{WRAPPER}} .agency-card',
            ]
        );

        $this->end_controls_section();

        // -------------------------------------------------------------
        // SECTION: Typography Style
        // -------------------------------------------------------------
        $this->start_controls_section(
            'section_style_typography',
            [
                'label' => esc_html__('Typography', 'agency-companion'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__('Title Color', 'agency-companion'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .agency-card__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .agency-card__title',
            ]
        );

        $this->end_controls_section();
    }

    protected function render(): void
    {
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute('wrapper', 'class', 'agency-card');

        if (!empty($settings['card_link']['url'])) {
            $this->add_link_attributes('link', $settings['card_link']);
        }
        ?>
        <div <?php $this->print_render_attribute_string('wrapper'); ?>>
            <?php if (!empty($settings['card_title'])) : ?>
                <h3 class="agency-card__title">
                    <?php if (!empty($settings['card_link']['url'])) : ?>
                        <a <?php $this->print_render_attribute_string('link'); ?>>
                            <?php echo esc_html($settings['card_title']); ?>
                        </a>
                    <?php else : ?>
                        <?php echo esc_html($settings['card_title']); ?>
                    <?php endif; ?>
                </h3>
            <?php endif; ?>

            <?php if (!empty($settings['card_description'])) : ?>
                <div class="agency-card__desc">
                    <?php echo wp_kses_post(wpautop($settings['card_description'])); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($settings['features_list'])) : ?>
                <ul class="agency-card__list">
                    <?php foreach ($settings['features_list'] as $item) : ?>
                        <?php if (!empty($item['item_text'])) : ?>
                            <li class="agency-card__list-item">
                                <span class="agency-card__list-bullet" aria-hidden="true">&check;</span>
                                <span class="agency-card__list-text"><?php echo esc_html($item['item_text']); ?></span>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        <?php
    }

    protected function content_template(): void
    {
        ?>
        <#
        view.addRenderAttribute('wrapper', 'class', 'agency-card');
        #>
        <div {{{ view.getRenderAttributeString('wrapper') }}}>
            <# if (settings.card_title) { #>
                <h3 class="agency-card__title">
                    <# if (settings.card_link && settings.card_link.url) { #>
                        <a href="{{{ settings.card_link.url }}}">{{{ settings.card_title }}}</a>
                    <# } else { #>
                        {{{ settings.card_title }}}
                    <# } #>
                </h3>
            <# } #>

            <# if (settings.card_description) { #>
                <div class="agency-card__desc">
                    {{{ settings.card_description }}}
                </div>
            <# } #>

            <# if (settings.features_list && settings.features_list.length) { #>
                <ul class="agency-card__list">
                    <# _.each(settings.features_list, function(item) { #>
                        <# if (item.item_text) { #>
                            <li class="agency-card__list-item">
                                <span class="agency-card__list-bullet">&check;</span>
                                <span class="agency-card__list-text">{{{ item.item_text }}}</span>
                            </li>
                        <# } #>
                    <# }); #>
                </ul>
            <# } #>
        </div>
        <?php
    }
}
