<?php
/**
 * Travelin' Host Bio Widget (Olivia Owen)
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

class Widget_Travelin_Host_Bio extends Widget_Base
{
    public function get_name(): string
    {
        return 'travelin-host-bio';
    }

    public function get_title(): string
    {
        return esc_html__('Travelin Host Bio (Olivia Owen)', 'agency-companion');
    }

    public function get_icon(): string
    {
        return 'eicon-person';
    }

    public function get_categories(): array
    {
        return ['agency-elements'];
    }

    public function get_keywords(): array
    {
        return ['host', 'bio', 'olivia', 'founder', 'travelin'];
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
                'label' => esc_html__('Host Details', 'agency-companion'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'host_photo',
            [
                'label'   => esc_html__('Host Portrait Image', 'agency-companion'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'photo_caption',
            [
                'label'   => esc_html__('Photo Caption', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('Olivia Owen / Your host, every time.', 'agency-companion'),
            ]
        );

        $this->add_control(
            'eyebrow',
            [
                'label'   => esc_html__('Eyebrow', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('MEET THE PERSON BRINGING YOU TOGETHER', 'agency-companion'),
            ]
        );

        $this->add_control(
            'heading_lines',
            [
                'label'   => esc_html__('Headline', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => 'Olivia is the one<br>getting everyone<br><em>laughing.</em>',
            ]
        );

        $this->add_control(
            'bio_paragraph_1',
            [
                'label'   => esc_html__('Bio Paragraph 1', 'agency-companion'),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => 'Olivia Owen knows what it’s like to want to travel and not have someone to go with. Her own solo travels helped inspire Travel & LIV.',
            ]
        );

        $this->add_control(
            'bio_paragraph_2',
            [
                'label'   => esc_html__('Bio Paragraph 2', 'agency-companion'),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => 'She has a knack for bringing people into the conversation. An introduction here, a joke there, a little help getting past the first hello.',
            ]
        );

        $this->add_control(
            'bio_paragraph_3',
            [
                'label'   => esc_html__('Bio Paragraph 3', 'agency-companion'),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => 'She also plans and personally hosts every trip. So the person who put it together is right there with you.',
            ]
        );

        $this->add_control(
            'cta_link_text',
            [
                'label'   => esc_html__('Contact Link Text', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('Ask Olivia a question', 'agency-companion'),
            ]
        );

        $this->add_control(
            'cta_link_url',
            [
                'label'   => esc_html__('Contact Link URL', 'agency-companion'),
                'type'    => Controls_Manager::URL,
                'default' => [
                    'url'         => 'mailto:Info@travelnliv.com',
                    'is_external' => false,
                ],
            ]
        );

        $this->add_control(
            'signature_text',
            [
                'label'   => esc_html__('Signature Note', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('See you out there,', 'agency-companion'),
            ]
        );

        $this->add_control(
            'signature_name',
            [
                'label'   => esc_html__('Signature Name', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('Olivia', 'agency-companion'),
            ]
        );

        $this->end_controls_section();
    }

    protected function render(): void
    {
        $settings = $this->get_settings_for_display();
        $photo_url = !empty($settings['host_photo']['url']) ? esc_url($settings['host_photo']['url']) : Utils::get_placeholder_image_src();
        $cta_url = !empty($settings['cta_link_url']['url']) ? esc_url($settings['cta_link_url']['url']) : 'mailto:Info@travelnliv.com';
        $target = !empty($settings['cta_link_url']['is_external']) ? ' target="_blank" rel="noopener noreferrer"' : '';
        ?>
        <section id="olivia" class="travelin-host travelin-scope">
            <div class="travelin-host__inner">
                <div class="travelin-host__photo-wrap">
                    <img src="<?php echo $photo_url; ?>" alt="Olivia Owen, founder and host of every Travel & LIV trip" loading="lazy">
                    <?php if (!empty($settings['photo_caption'])) : ?>
                        <span class="travelin-host__caption"><?php echo esc_html($settings['photo_caption']); ?></span>
                    <?php endif; ?>
                </div>

                <div class="travelin-host__content">
                    <?php if (!empty($settings['eyebrow'])) : ?>
                        <span class="travelin-editorial-label"><?php echo esc_html($settings['eyebrow']); ?></span>
                    <?php endif; ?>

                    <h2 class="travelin-host__title">
                        <?php echo wp_kses_post($settings['heading_lines']); ?>
                    </h2>

                    <div class="travelin-host__bio">
                        <?php if (!empty($settings['bio_paragraph_1'])) : ?>
                            <p><?php echo esc_html($settings['bio_paragraph_1']); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($settings['bio_paragraph_2'])) : ?>
                            <p><?php echo esc_html($settings['bio_paragraph_2']); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($settings['bio_paragraph_3'])) : ?>
                            <p><?php echo esc_html($settings['bio_paragraph_3']); ?></p>
                        <?php endif; ?>
                    </div>

                    <div style="margin: 24px 0;">
                        <a href="<?php echo $cta_url; ?>" class="travelin-text-link"<?php echo $target; ?> style="font-size: 14.5px; border-bottom: 1px solid rgba(38, 60, 50, 0.4); padding-bottom: 4px; display: inline-flex; align-items: center; gap: 8px;">
                            <?php echo esc_html($settings['cta_link_text']); ?>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                        </a>
                    </div>

                    <div class="travelin-host__signature" style="margin-top: 32px; font-size: 13px; color: var(--travelin-muted);">
                        <?php echo esc_html($settings['signature_text']); ?><br>
                        <em style="font-family: var(--travelin-serif); font-size: 38px; color: #536342; line-height: 1.1; font-style: italic;"><?php echo esc_html($settings['signature_name']); ?></em>
                    </div>
                </div>
            </div>
        </section>
        <?php
    }
}
