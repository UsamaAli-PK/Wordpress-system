<?php
/**
 * Travelin' Final CTA Banner Widget ("You’ll either be on the next trip… or watching it on Instagram.")
 *
 * @package Agency\Companion\Elementor\Widgets\Travelin
 */

declare(strict_types=1);

namespace Agency\Companion\Elementor\Widgets\Travelin;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;

if (!defined('ABSPATH')) {
    exit;
}

class Widget_Travelin_Final_CTA extends Widget_Base
{
    public function get_name(): string
    {
        return 'travelin-final-cta';
    }

    public function get_title(): string
    {
        return esc_html__('Travelin Final CTA Banner', 'agency-companion');
    }

    public function get_icon(): string
    {
        return 'eicon-call-to-action';
    }

    public function get_categories(): array
    {
        return ['agency-elements'];
    }

    public function get_keywords(): array
    {
        return ['cta', 'banner', 'final', 'action', 'travelin'];
    }

    public function get_style_depends(): array
    {
        return ['travelin-journey-css'];
    }

    protected function register_controls(): void
    {
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Banner Content', 'agency-companion'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'bg_image',
            [
                'label'   => esc_html__('Background Image', 'agency-companion'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'eyebrow',
            [
                'label'   => esc_html__('Eyebrow', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('MAKE YOUR MOVE', 'agency-companion'),
            ]
        );

        $this->add_control(
            'heading_line_1',
            [
                'label'   => esc_html__('Heading Line 1', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('You’ll either be on the next trip…', 'agency-companion'),
            ]
        );

        $this->add_control(
            'heading_line_2_italic',
            [
                'label'   => esc_html__('Heading Line 2 (Italic)', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('or watching it on Instagram.', 'agency-companion'),
            ]
        );

        $this->add_control(
            'subtitle',
            [
                'label'   => esc_html__('Subtitle', 'agency-companion'),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => "Find the dates that work for you,\nand come join Olivia and the group.",
            ]
        );

        $this->add_control(
            'btn_text',
            [
                'label'   => esc_html__('Button Text', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('Choose Your Trip', 'agency-companion'),
            ]
        );

        $this->add_control(
            'btn_url',
            [
                'label'   => esc_html__('Button URL', 'agency-companion'),
                'type'    => Controls_Manager::URL,
                'default' => [
                    'url'         => '/trips',
                    'is_external' => false,
                ],
            ]
        );

        $this->add_control(
            'secondary_link_text',
            [
                'label'   => esc_html__('Secondary Link Text', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('Have a question? Ask us', 'agency-companion'),
            ]
        );

        $this->add_control(
            'secondary_link_url',
            [
                'label'   => esc_html__('Secondary Link URL', 'agency-companion'),
                'type'    => Controls_Manager::URL,
                'default' => [
                    'url'         => 'mailto:Info@travelnliv.com',
                    'is_external' => false,
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render(): void
    {
        $settings = $this->get_settings_for_display();
        $bg_url = !empty($settings['bg_image']['url']) ? esc_url($settings['bg_image']['url']) : '';
        $btn_url = !empty($settings['btn_url']['url']) ? esc_url($settings['btn_url']['url']) : '/trips';
        $btn_target = !empty($settings['btn_url']['is_external']) ? ' target="_blank" rel="noopener noreferrer"' : '';
        $secondary_url = !empty($settings['secondary_link_url']['url']) ? esc_url($settings['secondary_link_url']['url']) : 'mailto:Info@travelnliv.com';
        $secondary_target = !empty($settings['secondary_link_url']['is_external']) ? ' target="_blank" rel="noopener noreferrer"' : '';
        ?>
        <section class="travelin-final-cta travelin-scope">
            <?php if (!empty($bg_url)) : ?>
                <div style="position: absolute; inset: 0; z-index: 1;">
                    <img src="<?php echo $bg_url; ?>" alt="Atmospheric background" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.25;">
                </div>
            <?php endif; ?>

            <div style="position: relative; z-index: 2; max-width: 900px; margin: 0 auto;">
                <?php if (!empty($settings['eyebrow'])) : ?>
                    <span class="travelin-editorial-label" style="color: #cbdcbb; margin-bottom: 24px; display: block;">
                        <?php echo esc_html($settings['eyebrow']); ?>
                    </span>
                <?php endif; ?>

                <h2>
                    <?php echo esc_html($settings['heading_line_1']); ?><br>
                    <em><?php echo esc_html($settings['heading_line_2_italic']); ?></em>
                </h2>

                <?php if (!empty($settings['subtitle'])) : ?>
                    <p><?php echo nl2br(esc_html($settings['subtitle'])); ?></p>
                <?php endif; ?>

                <div>
                    <a href="<?php echo $btn_url; ?>" class="travelin-button"<?php echo $btn_target; ?> style="display: inline-flex; align-items: center; gap: 14px; padding: 16px 36px; border-radius: 4px; font-weight: 500;">
                        <?php echo esc_html($settings['btn_text']); ?>
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                    </a>
                </div>

                <?php if (!empty($settings['secondary_link_text'])) : ?>
                    <div style="margin-top: 28px;">
                        <a href="<?php echo $secondary_url; ?>" class="travelin-text-link"<?php echo $secondary_target; ?> style="color: rgba(255, 255, 255, 0.7); font-size: 13px; border-bottom: 1px solid rgba(255, 255, 255, 0.3); padding-bottom: 3px;">
                            <?php echo esc_html($settings['secondary_link_text']); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        <?php
    }
}
