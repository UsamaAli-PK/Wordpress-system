<?php
/**
 * Travelin' Experience Pillars Widget (The Stay, The Days Out, The People)
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

class Widget_Travelin_Pillars extends Widget_Base
{
    public function get_name(): string
    {
        return 'travelin-pillars';
    }

    public function get_title(): string
    {
        return esc_html__('Travelin Experience Pillars', 'agency-companion');
    }

    public function get_icon(): string
    {
        return 'eicon-columns';
    }

    public function get_categories(): array
    {
        return ['agency-elements'];
    }

    public function get_keywords(): array
    {
        return ['pillars', 'experience', 'chapters', 'stay', 'travelin'];
    }

    public function get_style_depends(): array
    {
        return ['travelin-journey-css'];
    }

    protected function register_controls(): void
    {
        $this->start_controls_section(
            'section_intro',
            [
                'label' => esc_html__('Section Intro', 'agency-companion'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'intro_eyebrow',
            [
                'label'   => esc_html__('Eyebrow', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('THE TRAVEL & LIV WAY', 'agency-companion'),
            ]
        );

        $this->add_control(
            'intro_title',
            [
                'label'   => esc_html__('Heading Line 1', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('There’s a whole trip behind', 'agency-companion'),
            ]
        );

        $this->add_control(
            'intro_title_italic',
            [
                'label'   => esc_html__('Heading Line 2 (Italic)', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('those laughing videos.', 'agency-companion'),
            ]
        );

        $this->add_control(
            'intro_description',
            [
                'label'   => esc_html__('Intro Description', 'agency-companion'),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => 'The days out. The conversations over dinner. Someone getting everyone up to dance. Those are the moments we make room for, along with time to head off on your own or do nothing for a while.',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_chapters',
            [
                'label' => esc_html__('Chapter Pillars', 'agency-companion'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'chapter_label',
            [
                'label'   => esc_html__('Chapter Label', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('The stay', 'agency-companion'),
            ]
        );

        $repeater->add_control(
            'chapter_title',
            [
                'label'   => esc_html__('Chapter Title', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('Somewhere you’ll enjoy coming back to.', 'agency-companion'),
            ]
        );

        $repeater->add_control(
            'chapter_text',
            [
                'label'   => esc_html__('Description Text', 'agency-companion'),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => 'We choose 4–5-star stays because the room, the setting, and time to relax matter too. Each trip page shows where you’ll stay and the rooms you can book.',
            ]
        );

        $repeater->add_control(
            'image_1',
            [
                'label'   => esc_html__('Photo 1', 'agency-companion'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'image_2',
            [
                'label'   => esc_html__('Photo 2', 'agency-companion'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'image_3',
            [
                'label'   => esc_html__('Photo 3', 'agency-companion'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'chapters_list',
            [
                'label'       => esc_html__('Chapters List', 'agency-companion'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'chapter_label' => 'The stay',
                        'chapter_title' => 'Somewhere you’ll enjoy coming back to.',
                        'chapter_text'  => 'We choose 4–5-star stays because the room, the setting, and time to relax matter too. Each trip page shows where you’ll stay and the rooms you can book.',
                    ],
                    [
                        'chapter_label' => 'The days out',
                        'chapter_title' => 'Plans you don’t have to pull together.',
                        'chapter_text'  => 'We arrange the stay and scheduled group activities. You can see the plans before you book, including what’s covered and where there’s time for yourself.',
                    ],
                    [
                        'chapter_label' => 'The people',
                        'chapter_title' => 'An introduction when you need one.',
                        'chapter_text'  => 'You don’t have to walk in and work the room. Olivia gets people talking and laughing, and helps you join in at your own pace.',
                    ],
                ],
                'title_field' => '{{{ chapter_label }}}',
            ]
        );

        $this->end_controls_section();
    }

    protected function render(): void
    {
        $settings = $this->get_settings_for_display();
        ?>
        <section id="experience" class="travelin-pillars travelin-scope">
            <div class="travelin-pillars__intro">
                <?php if (!empty($settings['intro_eyebrow'])) : ?>
                    <span class="travelin-editorial-label"><?php echo esc_html($settings['intro_eyebrow']); ?></span>
                <?php endif; ?>
                <h2 class="travelin-serif-heading" style="font-size: clamp(38px, 4vw, 56px);">
                    <?php echo esc_html($settings['intro_title']); ?><br>
                    <em><?php echo esc_html($settings['intro_title_italic']); ?></em>
                </h2>
                <?php if (!empty($settings['intro_description'])) : ?>
                    <p style="font-size: 15.5px; line-height: 1.85; color: var(--travelin-muted); max-width: 580px; margin-top: 24px;">
                        <?php echo nl2br(esc_html($settings['intro_description'])); ?>
                    </p>
                <?php endif; ?>
            </div>

            <?php if (!empty($settings['chapters_list'])) : ?>
                <div class="travelin-pillars__list">
                    <?php foreach ($settings['chapters_list'] as $index => $chapter) : ?>
                        <article class="travelin-pillar-card">
                            <div class="travelin-pillar-card__content">
                                <span class="travelin-editorial-label"><?php echo esc_html($chapter['chapter_label']); ?></span>
                                <h3 class="travelin-serif-heading" style="font-size: clamp(28px, 2.8vw, 42px); margin: 12px 0 20px;">
                                    <?php echo esc_html($chapter['chapter_title']); ?>
                                </h3>
                                <p style="font-size: 14.5px; line-height: 1.8; color: var(--travelin-muted);">
                                    <?php echo nl2br(esc_html($chapter['chapter_text'])); ?>
                                </p>
                            </div>

                            <div class="travelin-pillar-card__visuals">
                                <?php if (!empty($chapter['image_1']['url'])) : ?>
                                    <div class="travelin-pillar-card__photo photo-1">
                                        <img src="<?php echo esc_url($chapter['image_1']['url']); ?>" alt="<?php echo esc_attr($chapter['chapter_title']); ?>" loading="lazy">
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($chapter['image_2']['url'])) : ?>
                                    <div class="travelin-pillar-card__photo photo-2">
                                        <img src="<?php echo esc_url($chapter['image_2']['url']); ?>" alt="<?php echo esc_attr($chapter['chapter_title']); ?>" loading="lazy">
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($chapter['image_3']['url'])) : ?>
                                    <div class="travelin-pillar-card__photo photo-3">
                                        <img src="<?php echo esc_url($chapter['image_3']['url']); ?>" alt="<?php echo esc_attr($chapter['chapter_title']); ?>" loading="lazy">
                                    </div>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
        <?php
    }
}
