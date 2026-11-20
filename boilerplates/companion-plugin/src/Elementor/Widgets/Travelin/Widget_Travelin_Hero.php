<?php
/**
 * Travelin' Hero & Atmosphere Widget
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

class Widget_Travelin_Hero extends Widget_Base
{
    public function get_name(): string
    {
        return 'travelin-hero';
    }

    public function get_title(): string
    {
        return esc_html__('Travelin Hero Atmosphere', 'agency-companion');
    }

    public function get_icon(): string
    {
        return 'eicon-video-camera';
    }

    public function get_categories(): array
    {
        return ['agency-elements'];
    }

    public function get_keywords(): array
    {
        return ['hero', 'video', 'clouds', 'atmosphere', 'travelin'];
    }

    public function get_script_depends(): array
    {
        return ['travelin-frontend-js'];
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
                'label' => esc_html__('Hero Content', 'agency-companion'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title_line_1',
            [
                'label'       => esc_html__('Headline Line 1', 'agency-companion'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Go somewhere new', 'agency-companion'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'title_line_2',
            [
                'label'       => esc_html__('Headline Line 2', 'agency-companion'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('with people who are', 'agency-companion'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'title_line_3_italic',
            [
                'label'       => esc_html__('Headline Line 3 (Italic)', 'agency-companion'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('up for it.', 'agency-companion'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'intro_lead',
            [
                'label'       => esc_html__('Intro Lead Paragraph', 'agency-companion'),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => "Come on your own.\nWe’ll get the group together,\narrange the stay,\nand plan the days out.",
            ]
        );

        $this->add_control(
            'action_lead',
            [
                'label'       => esc_html__('Action Lead Paragraph', 'agency-companion'),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => "We make the girl trips\nout of the group chat.\nGo somewhere new with\nthe people who are up for it.",
            ]
        );

        $this->add_control(
            'btn_text',
            [
                'label'   => esc_html__('Button Text', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('Find Your Trip', 'agency-companion'),
            ]
        );

        $this->add_control(
            'btn_url',
            [
                'label'       => esc_html__('Button URL', 'agency-companion'),
                'type'        => Controls_Manager::URL,
                'default'     => [
                    'url'         => '/trips',
                    'is_external' => false,
                ],
            ]
        );

        $this->add_control(
            'btn_note',
            [
                'label'   => esc_html__('Button Sub-Note', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('4–5-star stays · Payment plans on most trips', 'agency-companion'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_media',
            [
                'label' => esc_html__('Atmosphere Media', 'agency-companion'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'video_url',
            [
                'label'       => esc_html__('Background Video MP4 URL', 'agency-companion'),
                'type'        => Controls_Manager::TEXT,
                'default'     => '/media/door-journey.mp4',
                'description' => esc_html__('Local or CDN MP4 path', 'agency-companion'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'poster_image',
            [
                'label'   => esc_html__('Video Poster Fallback Image', 'agency-companion'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'enable_clouds',
            [
                'label'        => esc_html__('Enable Cloud Layers', 'agency-companion'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Yes', 'agency-companion'),
                'label_off'    => esc_html__('No', 'agency-companion'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->end_controls_section();
    }

    protected function render(): void
    {
        $settings = $this->get_settings_for_display();
        $video_url = !empty($settings['video_url']) ? esc_url($settings['video_url']) : '';
        $poster_url = !empty($settings['poster_image']['url']) ? esc_url($settings['poster_image']['url']) : '';
        $btn_url = !empty($settings['btn_url']['url']) ? esc_url($settings['btn_url']['url']) : '#trips';
        $target = !empty($settings['btn_url']['is_external']) ? ' target="_blank" rel="noopener noreferrer"' : '';
        ?>
        <section id="home" class="travelin-hero travelin-scope" aria-label="Your journey starts here">
            <div class="travelin-hero__sticky">
                <div class="travelin-hero__scene">
                    <?php if (!empty($video_url)) : ?>
                        <video class="travelin-hero__film" muted playsinline webkit-playsinline="true" preload="auto" poster="<?php echo $poster_url; ?>" aria-hidden="true">
                            <source src="<?php echo $video_url; ?>" type="video/mp4">
                        </video>
                    <?php endif; ?>
                    <div class="travelin-hero__shade"></div>
                </div>

                <div class="travelin-hero__copy travelin-hero__title-box">
                    <h1 class="travelin-hero__title">
                        <?php if (!empty($settings['title_line_1'])) : ?>
                            <span class="travelin-hero__line-mask"><span class="travelin-hero__line"><?php echo esc_html($settings['title_line_1']); ?></span></span>
                        <?php endif; ?>
                        <?php if (!empty($settings['title_line_2'])) : ?>
                            <span class="travelin-hero__line-mask"><span class="travelin-hero__line"><?php echo esc_html($settings['title_line_2']); ?></span></span>
                        <?php endif; ?>
                        <?php if (!empty($settings['title_line_3_italic'])) : ?>
                            <span class="travelin-hero__line-mask"><em class="travelin-hero__line"><?php echo esc_html($settings['title_line_3_italic']); ?></em></span>
                        <?php endif; ?>
                    </h1>
                </div>

                <?php if (!empty($settings['intro_lead'])) : ?>
                    <div class="travelin-hero__copy travelin-hero__intro-box">
                        <p class="travelin-hero__lead">
                            <?php echo nl2br(esc_html($settings['intro_lead'])); ?>
                        </p>
                    </div>
                <?php endif; ?>

                <div class="travelin-hero__copy travelin-hero__action-box">
                    <?php if (!empty($settings['action_lead'])) : ?>
                        <h2 class="travelin-hero__action-lead">
                            <?php echo nl2br(esc_html($settings['action_lead'])); ?>
                        </h2>
                    <?php endif; ?>
                    <div class="travelin-hero__cta-box">
                        <a href="<?php echo $btn_url; ?>" class="travelin-button"<?php echo $target; ?>>
                            <?php echo esc_html($settings['btn_text']); ?>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                        </a>
                        <?php if (!empty($settings['btn_note'])) : ?>
                            <p class="travelin-hero__note"><?php echo esc_html($settings['btn_note']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if ('yes' === $settings['enable_clouds']) : ?>
                    <div class="travelin-hero__clouds" aria-hidden="true">
                        <div class="travelin-cloud-floor"></div>
                    </div>
                <?php endif; ?>
            </div><!-- /.travelin-hero__sticky -->
        </section>
        <?php
    }
}
