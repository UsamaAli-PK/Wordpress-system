<?php
/**
 * Travelin' Trips Carousel & Destination Selector Widget
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

class Widget_Travelin_Trips_Carousel extends Widget_Base
{
    public function get_name(): string
    {
        return 'travelin-trips-carousel';
    }

    public function get_title(): string
    {
        return esc_html__('Travelin Trips Carousel', 'agency-companion');
    }

    public function get_icon(): string
    {
        return 'eicon-slider-push';
    }

    public function get_categories(): array
    {
        return ['agency-elements'];
    }

    public function get_keywords(): array
    {
        return ['trips', 'carousel', 'destinations', 'travelin'];
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
            'section_heading',
            [
                'label' => esc_html__('Section Heading', 'agency-companion'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'section_title',
            [
                'label'   => esc_html__('Title Line 1', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('Where would you', 'agency-companion'),
            ]
        );

        $this->add_control(
            'section_title_italic',
            [
                'label'   => esc_html__('Title Line 2 (Italic)', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('like to go?', 'agency-companion'),
            ]
        );

        $this->add_control(
            'section_subtitle',
            [
                'label'   => esc_html__('Subtitle', 'agency-companion'),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => "See how the dates fit around work.\nThen take a look at where we’ll stay\nand what we’ll do together.",
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_trips',
            [
                'label' => esc_html__('Destinations List', 'agency-companion'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'destination_name',
            [
                'label'   => esc_html__('Destination Name', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('Brazil', 'agency-companion'),
            ]
        );

        $repeater->add_control(
            'region',
            [
                'label'   => esc_html__('Region / Country', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('RIO DE JANEIRO', 'agency-companion'),
            ]
        );

        $repeater->add_control(
            'mood_line',
            [
                'label'   => esc_html__('Mood / Headline Line', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => 'A little rhythm.<br>A whole lot of Rio.',
            ]
        );

        $repeater->add_control(
            'tag',
            [
                'label'   => esc_html__('Topline Badge / Tag', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('Small Group', 'agency-companion'),
            ]
        );

        $repeater->add_control(
            'trip_image',
            [
                'label'   => esc_html__('Card Cover Image', 'agency-companion'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'duration',
            [
                'label'   => esc_html__('Duration', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('7 Days / 6 Nights', 'agency-companion'),
            ]
        );

        $repeater->add_control(
            'dates',
            [
                'label'   => esc_html__('Departure Dates', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('Dec 2026 · Small Group', 'agency-companion'),
            ]
        );

        $repeater->add_control(
            'price',
            [
                'label'   => esc_html__('Starting Price', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('From $2,850', 'agency-companion'),
            ]
        );

        $repeater->add_control(
            'price_note',
            [
                'label'   => esc_html__('Price Note', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('Listed per person · Singles trip · Flights extra', 'agency-companion'),
            ]
        );

        $repeater->add_control(
            'link_url',
            [
                'label'       => esc_html__('Trip Link URL', 'agency-companion'),
                'type'        => Controls_Manager::URL,
                'default'     => [
                    'url'         => '/trips/brazil',
                    'is_external' => false,
                ],
            ]
        );

        $repeater->add_control(
            'cta_label',
            [
                'label'   => esc_html__('Button Label', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('Explore this trip', 'agency-companion'),
            ]
        );

        $this->add_control(
            'trips_list',
            [
                'label'       => esc_html__('Destinations', 'agency-companion'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'destination_name' => 'Brazil',
                        'region'           => 'RIO DE JANEIRO',
                        'mood_line'        => 'A little rhythm.<br>A whole lot of Rio.',
                        'tag'              => 'Dec 2026 · Small Group',
                        'duration'         => '7 Days',
                        'dates'            => 'Dec 2026 · Small Group',
                        'price'            => 'From $2,850',
                        'price_note'       => 'Listed per person · Small group · Flights extra',
                        'cta_label'        => 'Explore this trip',
                    ],
                    [
                        'destination_name' => 'Punta Cana',
                        'region'           => 'DOMINICAN REPUBLIC',
                        'mood_line'        => 'Good company.<br>Caribbean time.',
                        'tag'              => 'SINGLES TRIP',
                        'duration'         => '6 Days',
                        'dates'            => 'Feb 2027 · Singles',
                        'price'            => 'From $2,450',
                        'price_note'       => 'Singles ages 25–40 · Application required · Flights extra',
                        'cta_label'        => 'Explore this trip',
                    ],
                    [
                        'destination_name' => 'Phuket',
                        'region'           => 'THAILAND',
                        'mood_line'        => 'Take the long way<br>to the beach.',
                        'tag'              => 'Villa & Sea',
                        'duration'         => '8 Days',
                        'dates'            => 'Mar 2027 · Villa & Sea',
                        'price'            => 'From $3,100',
                        'price_note'       => '5-star hilltop villa · Private chef · Small group',
                        'cta_label'        => 'Explore this trip',
                    ],
                    [
                        'destination_name' => 'China',
                        'region'           => 'CHENGDU & ANCIENT TOWNS',
                        'mood_line'        => 'Ancient alleys.<br>Modern momentum.',
                        'tag'              => 'Small Group',
                        'duration'         => '9 Days',
                        'dates'            => 'Autumn 2027 · Hosted departure',
                        'price'            => 'From $3,400',
                        'price_note'       => 'Culture, mountain temples & culinary immersion',
                        'cta_label'        => 'Explore this trip',
                    ],
                ],
                'title_field' => '{{{ destination_name }}}',
            ]
        );

        $this->end_controls_section();
    }

    protected function render(): void
    {
        $settings = $this->get_settings_for_display();
        $trips = !empty($settings['trips_list']) ? $settings['trips_list'] : [];
        ?>
        <section id="trips" class="travelin-trips travelin-scope">
            <div class="travelin-trips__heading" style="max-width: 1320px; margin: 0 auto 50px;">
                <span class="travelin-editorial-label" style="visibility: hidden;">&nbsp;</span>
                <h2 class="travelin-serif-heading" style="font-size: clamp(40px, 4.2vw, 62px);">
                    <?php echo esc_html($settings['section_title']); ?><br>
                    <em><?php echo esc_html($settings['section_title_italic']); ?></em>
                </h2>
                <?php if (!empty($settings['section_subtitle'])) : ?>
                    <p style="font-size: 14.5px; line-height: 1.8; color: var(--travelin-muted); max-width: 500px; margin-top: 18px;">
                        <?php echo nl2br(esc_html($settings['section_subtitle'])); ?>
                    </p>
                <?php endif; ?>
            </div>

            <?php if (!empty($trips)) : ?>
                <div class="travelin-trips__tabs" aria-label="Choose a destination">
                    <?php foreach ($trips as $i => $trip) : ?>
                        <button type="button" class="travelin-trips__tab-btn <?php echo (0 === $i) ? 'is-active active' : ''; ?>" aria-pressed="<?php echo (0 === $i) ? 'true' : 'false'; ?>">
                            <?php echo esc_html($trip['destination_name']); ?>
                            <sup style="font-size: 10px; margin-left: 6px;">0<?php echo ($i + 1); ?></sup>
                        </button>
                    <?php endforeach; ?>
                </div>

                <div class="travelin-trips__carousel-stage" style="position: relative; max-width: 1320px; margin: 0 auto;">
                    <?php foreach ($trips as $i => $trip) :
                        $img_url = !empty($trip['trip_image']['url']) ? esc_url($trip['trip_image']['url']) : Utils::get_placeholder_image_src();
                        $link_url = !empty($trip['link_url']['url']) ? esc_url($trip['link_url']['url']) : '#';
                        $target = !empty($trip['link_url']['is_external']) ? ' target="_blank" rel="noopener noreferrer"' : '';
                    ?>
                        <article class="travelin-trip-card <?php echo (0 === $i) ? 'is-active selected' : ''; ?>" style="<?php echo (0 !== $i) ? 'display: none;' : ''; ?>" data-index="<?php echo $i; ?>">
                            <div class="travelin-trip-card__image-wrap">
                                <img src="<?php echo $img_url; ?>" alt="<?php echo esc_attr($trip['destination_name']); ?>" loading="lazy">
                                <?php if (!empty($trip['tag'])) : ?>
                                    <span style="position: absolute; top: 20px; left: 20px; z-index: 2; background: rgba(38,60,50,0.85); color: #fff; font-size: 10px; letter-spacing: 0.12em; text-transform: uppercase; padding: 5px 12px; border-radius: 9999px;">
                                        <?php echo esc_html($trip['tag']); ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <div class="travelin-trip-card__content">
                                <div>
                                    <span class="travelin-trip-card__destination"><?php echo esc_html($trip['region']); ?></span>
                                    <h3 class="travelin-trip-card__title">
                                        <?php echo wp_kses_post($trip['mood_line']); ?>
                                    </h3>

                                    <div class="travelin-trip-card__meta">
                                        <div>
                                            <span style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; color: var(--travelin-muted); display: block; margin-bottom: 4px;"><?php echo esc_html($trip['duration']); ?></span>
                                            <strong style="font-family: var(--travelin-serif); font-size: 22px; color: var(--travelin-foreground); font-weight: 400;"><?php echo esc_html($trip['dates']); ?></strong>
                                        </div>
                                        <div>
                                            <span style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; color: var(--travelin-muted); display: block; margin-bottom: 4px;">Pricing</span>
                                            <strong style="font-size: 18px; color: var(--travelin-foreground); font-weight: 600;"><?php echo esc_html($trip['price']); ?></strong>
                                            <small style="display: block; font-size: 12px; color: var(--travelin-muted); margin-top: 4px;"><?php echo esc_html($trip['price_note']); ?></small>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <a href="<?php echo $link_url; ?>" class="travelin-button travelin-button--dark"<?php echo $target; ?>>
                                        <?php echo esc_html($trip['cta_label']); ?>
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                                    </a>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>

                <div class="travelin-trips__bottom" style="display: flex; justify-content: space-between; align-items: center; max-width: 1320px; margin: 36px auto 0;">
                    <a href="/trips" class="travelin-text-link" style="font-size: 13px; color: var(--travelin-foreground); text-decoration: underline;">
                        View all trips
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                    </a>

                    <div class="carousel-controls" style="display: flex; gap: 12px; align-items: center;">
                        <button type="button" class="carousel-prev-btn" aria-label="Previous trip" style="width: 44px; height: 44px; border-radius: 50%; border: 1px solid rgba(38, 60, 50, 0.3); background: none; cursor: pointer; display: grid; place-items: center;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                        </button>
                        <button type="button" class="carousel-next-btn" aria-label="Next trip" style="width: 44px; height: 44px; border-radius: 50%; border: 1px solid rgba(38, 60, 50, 0.3); background: none; cursor: pointer; display: grid; place-items: center;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                        </button>
                    </div>
                </div>
            <?php endif; ?>
        </section>
        <?php
    }
}
