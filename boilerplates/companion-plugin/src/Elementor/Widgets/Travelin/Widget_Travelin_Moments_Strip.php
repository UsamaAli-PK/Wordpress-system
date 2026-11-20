<?php
/**
 * Travelin' Moments Collage Grid Widget ("A few days away. A lot to come home with.")
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

class Widget_Travelin_Moments_Strip extends Widget_Base
{
    public function get_name(): string
    {
        return 'travelin-moments-strip';
    }

    public function get_title(): string
    {
        return esc_html__('Travelin Moments Collage', 'agency-companion');
    }

    public function get_icon(): string
    {
        return 'eicon-gallery-grid';
    }

    public function get_categories(): array
    {
        return ['agency-elements'];
    }

    public function get_keywords(): array
    {
        return ['moments', 'collage', 'gallery', 'photos', 'travelin'];
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
                'label' => esc_html__('Collage Header', 'agency-companion'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title_line_1',
            [
                'label'   => esc_html__('Heading Line 1', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('A few days away.', 'agency-companion'),
            ]
        );

        $this->add_control(
            'title_line_2_italic',
            [
                'label'   => esc_html__('Heading Line 2 (Italic)', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('A lot to come home with.', 'agency-companion'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_photos',
            [
                'label' => esc_html__('Editorial Photos', 'agency-companion'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'image',
            [
                'label'   => esc_html__('Photo', 'agency-companion'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'alt_text',
            [
                'label'   => esc_html__('Alt Text / Caption', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('A moment from the trip', 'agency-companion'),
            ]
        );

        $this->add_control(
            'photos_list',
            [
                'label'       => esc_html__('Photos', 'agency-companion'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    ['alt_text' => 'A shared dinner table with the collective'],
                    ['alt_text' => 'Exploring coastal streets and landmarks together'],
                    ['alt_text' => 'Panoramic skyline terrace view'],
                    ['alt_text' => 'Cocktails and conversations at golden hour'],
                    ['alt_text' => 'A view over the city lights'],
                    ['alt_text' => 'The Travel & LIV collective together'],
                    ['alt_text' => 'Relaxed evening smiles on a hosted departure'],
                ],
                'title_field' => '{{{ alt_text }}}',
            ]
        );

        $this->end_controls_section();
    }

    protected function render(): void
    {
        $settings = $this->get_settings_for_display();
        $photos = !empty($settings['photos_list']) ? $settings['photos_list'] : [];
        ?>
        <section class="travelin-moments travelin-scope" aria-label="Moments from Travel and LIV trips">
            <div class="travelin-moments__title">
                <h2>
                    <?php echo esc_html($settings['title_line_1']); ?><br>
                    <em><?php echo esc_html($settings['title_line_2_italic']); ?></em>
                </h2>
            </div>

            <?php if (!empty($photos)) : ?>
                <div class="travelin-moments__grid">
                    <?php foreach ($photos as $i => $item) :
                        $img_url = !empty($item['image']['url']) ? esc_url($item['image']['url']) : Utils::get_placeholder_image_src();
                        $alt = !empty($item['alt_text']) ? esc_attr($item['alt_text']) : 'Trip moment';
                    ?>
                        <div class="travelin-moment-item travelin-moment-<?php echo ($i % 7); ?>">
                            <img src="<?php echo $img_url; ?>" alt="<?php echo $alt; ?>" loading="lazy">
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
        <?php
    }
}
