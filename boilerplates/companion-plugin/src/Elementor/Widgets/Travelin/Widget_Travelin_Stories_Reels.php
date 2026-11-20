<?php
/**
 * Travelin' Story Reels Widget ("Who's coming with you?")
 *
 * @package Agency\Companion\Elementor\Widgets\Travelin
 */

declare(strict_types=1);

namespace Agency\Companion\Elementor\Widgets\Travelin;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;

if (!defined('ABSPATH')) {
    exit;
}

class Widget_Travelin_Stories_Reels extends Widget_Base
{
    public function get_name(): string
    {
        return 'travelin-stories';
    }

    public function get_title(): string
    {
        return esc_html__('Travelin Story Reels', 'agency-companion');
    }

    public function get_icon(): string
    {
        return 'eicon-video-playlist';
    }

    public function get_categories(): array
    {
        return ['agency-elements'];
    }

    public function get_keywords(): array
    {
        return ['stories', 'reels', 'testimonials', 'travelin'];
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
                'label' => esc_html__('Section Heading', 'agency-companion'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'heading_line_1',
            [
                'label'   => esc_html__('Heading Line 1', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('Who’s coming', 'agency-companion'),
            ]
        );

        $this->add_control(
            'heading_line_2_italic',
            [
                'label'   => esc_html__('Heading Line 2 (Italic)', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('with you?', 'agency-companion'),
            ]
        );

        $this->add_control(
            'paragraph_1',
            [
                'label'   => esc_html__('Paragraph 1', 'agency-companion'),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => 'People who enjoy a day out, a good meal, and getting to know someone new. Most book solo; friends and couples join too.',
            ]
        );

        $this->add_control(
            'paragraph_2',
            [
                'label'   => esc_html__('Paragraph 2', 'agency-companion'),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => 'You can start getting to know each other in the trip chat before departure. And Olivia will be there for the first hello.',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_reels',
            [
                'label' => esc_html__('Story Video Reels', 'agency-companion'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'video_url',
            [
                'label'       => esc_html__('Reel MP4 Video URL', 'agency-companion'),
                'type'        => Controls_Manager::TEXT,
                'default'     => '/media/Testimonuals/reel_1.mp4',
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'poster_image',
            [
                'label'   => esc_html__('Poster Image (Optional)', 'agency-companion'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'aria_label',
            [
                'label'   => esc_html__('Accessibility Label', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('Travel & LIV collective moment', 'agency-companion'),
            ]
        );

        $this->add_control(
            'reels_list',
            [
                'label'       => esc_html__('Reels', 'agency-companion'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'video_url'  => '/media/Testimonuals/reel_1.mp4',
                        'aria_label' => 'Travel & LIV collective moment 1',
                    ],
                    [
                        'video_url'  => '/media/Testimonuals/reel_2.mp4',
                        'aria_label' => 'Travel & LIV collective moment 2',
                    ],
                    [
                        'video_url'  => '/media/Testimonuals/reel_3.mp4',
                        'aria_label' => 'Travel & LIV collective moment 3',
                    ],
                ],
                'title_field' => '{{{ aria_label }}}',
            ]
        );

        $this->end_controls_section();
    }

    protected function render(): void
    {
        $settings = $this->get_settings_for_display();
        $reels = !empty($settings['reels_list']) ? $settings['reels_list'] : [];
        ?>
        <section id="people" class="travelin-stories travelin-scope">
            <div class="travelin-stories__header">
                <h2 class="travelin-stories__title">
                    <?php echo esc_html($settings['heading_line_1']); ?><br>
                    <em><?php echo esc_html($settings['heading_line_2_italic']); ?></em>
                </h2>

                <div class="travelin-stories__lead">
                    <?php if (!empty($settings['paragraph_1'])) : ?>
                        <p><?php echo esc_html($settings['paragraph_1']); ?></p>
                    <?php endif; ?>
                    <?php if (!empty($settings['paragraph_2'])) : ?>
                        <p><?php echo esc_html($settings['paragraph_2']); ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!empty($reels)) : ?>
                <div class="travelin-stories__cards">
                    <?php foreach ($reels as $i => $reel) :
                        $video_url = !empty($reel['video_url']) ? esc_url($reel['video_url']) : '';
                        $poster = !empty($reel['poster_image']['url']) ? esc_url($reel['poster_image']['url']) : '';
                    ?>
                        <div class="travelin-story-card" role="button" tabindex="0" aria-label="<?php echo esc_attr($reel['aria_label']); ?>">
                            <div class="travelin-story-card__video-wrap">
                                <?php if (!empty($video_url)) : ?>
                                    <video src="<?php echo $video_url; ?>" muted loop playsinline preload="none" poster="<?php echo $poster; ?>" aria-label="<?php echo esc_attr($reel['aria_label']); ?>"></video>
                                <?php endif; ?>
                            </div>

                            <button type="button" class="travelin-story-sound-toggle" aria-label="Toggle sound">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon><line x1="23" y1="9" x2="17" y2="15"></line><line x1="17" y1="9" x2="23" y2="15"></line></svg>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
        <?php
    }
}
