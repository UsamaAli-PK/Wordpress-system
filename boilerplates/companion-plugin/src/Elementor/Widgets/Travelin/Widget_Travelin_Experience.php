<?php
/**
 * Travelin' Interactive Experience Film Widget
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

class Widget_Travelin_Experience extends Widget_Base
{
    public function get_name(): string
    {
        return 'travelin-experience';
    }

    public function get_title(): string
    {
        return esc_html__('Travelin Interactive Film', 'agency-companion');
    }

    public function get_icon(): string
    {
        return 'eicon-play-o';
    }

    public function get_categories(): array
    {
        return ['agency-elements'];
    }

    public function get_keywords(): array
    {
        return ['experience', 'film', 'video', 'interactive', 'travelin'];
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
                'label' => esc_html__('Film Content', 'agency-companion'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'eyebrow',
            [
                'label'   => esc_html__('Eyebrow', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('A MOMENT WITH THE GROUP', 'agency-companion'),
            ]
        );

        $this->add_control(
            'title_prefix',
            [
                'label'   => esc_html__('Title Regular', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('You had to', 'agency-companion'),
            ]
        );

        $this->add_control(
            'title_italic',
            [
                'label'   => esc_html__('Title Italic', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('be there.', 'agency-companion'),
            ]
        );

        $this->add_control(
            'video_url',
            [
                'label'       => esc_html__('Video MP4 URL', 'agency-companion'),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'https://www.travelnliv.com/travelnliv_who.mp4',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'poster_image',
            [
                'label'   => esc_html__('Video Poster', 'agency-companion'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'play_label',
            [
                'label'   => esc_html__('Play Button Label', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('Watch the experience', 'agency-companion'),
            ]
        );

        $this->add_control(
            'enable_ambient_btn',
            [
                'label'        => esc_html__('Show Ambient Mute Control', 'agency-companion'),
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
        ?>
        <section class="travelin-experience-film travelin-scope" aria-labelledby="experience-film-title">
            <div class="film-frame">
                <?php if (!empty($video_url)) : ?>
                    <video class="film-poster" muted loop playsinline preload="none" poster="<?php echo $poster_url; ?>" aria-label="<?php echo esc_attr($settings['title_prefix'] . ' ' . $settings['title_italic']); ?>">
                        <source src="<?php echo $video_url; ?>" type="video/mp4">
                    </video>
                <?php endif; ?>
                <div class="film-shade"></div>

                <?php if ('yes' === $settings['enable_ambient_btn']) : ?>
                    <button type="button" class="ambient-control" aria-label="Toggle ambient video sound">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon><line x1="23" y1="9" x2="17" y2="15"></line><line x1="17" y1="9" x2="23" y2="15"></line></svg>
                    </button>
                <?php endif; ?>

                <div class="film-copy">
                    <div class="film-headings">
                        <?php if (!empty($settings['eyebrow'])) : ?>
                            <span class="eyebrow"><?php echo esc_html($settings['eyebrow']); ?></span>
                        <?php endif; ?>
                        <h2 id="experience-film-title">
                            <?php echo esc_html($settings['title_prefix']); ?> <em><?php echo esc_html($settings['title_italic']); ?></em>
                        </h2>
                    </div>

                    <button type="button" class="film-play" aria-label="Play video">
                        <span>
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                        </span>
                        <?php if (!empty($settings['play_label'])) : ?>
                            <?php echo esc_html($settings['play_label']); ?>
                        <?php endif; ?>
                    </button>
                </div>

                <div class="film-clouds" aria-hidden="true">
                    <div class="film-cloud-floor"></div>
                </div>
            </div>
        </section>
        <?php
    }
}
