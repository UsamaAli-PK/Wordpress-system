<?php
/**
 * Travelin' Google Reviews & Lightbox Widget
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

class Widget_Travelin_Google_Reviews extends Widget_Base
{
    public function get_name(): string
    {
        return 'travelin-google-reviews';
    }

    public function get_title(): string
    {
        return esc_html__('Travelin Google Reviews', 'agency-companion');
    }

    public function get_icon(): string
    {
        return 'eicon-star-o';
    }

    public function get_categories(): array
    {
        return ['agency-elements'];
    }

    public function get_keywords(): array
    {
        return ['google', 'reviews', 'testimonials', 'stars', 'travelin'];
    }

    public function get_script_depends(): array
    {
        return ['travelin-frontend-js'];
    }

    public function get_style_depends(): array
    {
        return ['travelin-reviews-css'];
    }

    protected function register_controls(): void
    {
        $this->start_controls_section(
            'section_badge',
            [
                'label' => esc_html__('Google Reviews Header Badge', 'agency-companion'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'google_score',
            [
                'label'   => esc_html__('Rating Score', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => '5.0',
            ]
        );

        $this->add_control(
            'badge_label',
            [
                'label'   => esc_html__('Badge Label', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('Google Reviews', 'agency-companion'),
            ]
        );

        $this->add_control(
            'google_url',
            [
                'label'   => esc_html__('Google Reviews URL', 'agency-companion'),
                'type'    => Controls_Manager::URL,
                'default' => [
                    'url'         => 'https://share.google/NVLZTqQg771xWsmAo',
                    'is_external' => true,
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_reviews',
            [
                'label' => esc_html__('Reviews List', 'agency-companion'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'author',
            [
                'label'   => esc_html__('Reviewer Name', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('Taylor S.', 'agency-companion'),
            ]
        );

        $repeater->add_control(
            'initials',
            [
                'label'   => esc_html__('Avatar Initials', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => 'TS',
            ]
        );

        $repeater->add_control(
            'role_tag',
            [
                'label'   => esc_html__('Role Badge', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('Booked Solo', 'agency-companion'),
            ]
        );

        $repeater->add_control(
            'trip_name',
            [
                'label'   => esc_html__('Trip Name', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('Punta Cana Singles Trip', 'agency-companion'),
            ]
        );

        $repeater->add_control(
            'rating',
            [
                'label'   => esc_html__('Star Rating (1-5)', 'agency-companion'),
                'type'    => Controls_Manager::NUMBER,
                'min'     => 1,
                'max'     => 5,
                'step'    => 1,
                'default' => 5,
            ]
        );

        $repeater->add_control(
            'review_text',
            [
                'label'   => esc_html__('Review Text', 'agency-companion'),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => 'I was so nervous to book a trip by myself, but Olivia made everyone feel like family within the first ten minutes of the welcome mixer.',
            ]
        );

        $repeater->add_control(
            'photo',
            [
                'label'   => esc_html__('Trip Photo Attachment (Optional)', 'agency-companion'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'photo_alt',
            [
                'label'   => esc_html__('Photo Caption / Alt', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('Private catamaran day on Caribbean waters', 'agency-companion'),
            ]
        );

        $this->add_control(
            'reviews_list',
            [
                'label'       => esc_html__('Curated Reviews', 'agency-companion'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'author'      => 'Taylor S.',
                        'initials'    => 'TS',
                        'role_tag'    => 'Booked Solo',
                        'trip_name'   => 'Punta Cana Singles Trip',
                        'rating'      => 5,
                        'review_text' => 'I was so nervous to book a trip by myself, but Olivia made everyone feel like family within the first ten minutes of the welcome mixer. The catamaran day was hands down one of the best days of my life! If you’re on the fence about going solo, just book it.',
                        'photo_alt'   => 'Private catamaran day on Caribbean waters',
                    ],
                    [
                        'author'      => 'Brianna M.',
                        'initials'    => 'BM',
                        'role_tag'    => 'Verified Traveler',
                        'trip_name'   => 'Dominican Republic Escape',
                        'rating'      => 5,
                        'review_text' => 'This wasn’t just a vacation, it was genuine community. Everything from the 5-star resort to the off-road buggies and private dinners was curated to perfection. We literally have a group chat that talks every single day still!',
                        'photo_alt'   => 'Sunset all-white welcome mixer',
                    ],
                    [
                        'author'      => 'Chloe D.',
                        'initials'    => 'CD',
                        'role_tag'    => 'Booked Solo',
                        'trip_name'   => 'Bali & Tropical Retreats',
                        'rating'      => 5,
                        'review_text' => 'Getting out of the group chat is so real! My friends always talk about traveling but cancel last minute. Travel & LIV took care of every single logistical headache. Olivia is hilarious, attentive, and an unmatched host.',
                        'photo_alt'   => 'Gala evening celebration with the collective',
                    ],
                    [
                        'author'      => 'Jasmine R.',
                        'initials'    => 'JR',
                        'role_tag'    => 'Verified Traveler',
                        'trip_name'   => 'Rio de Janeiro Getaway',
                        'rating'      => 5,
                        'review_text' => 'The balance between planned excursions and free poolside time was chef’s kiss. No tourist traps—just immaculate vibes, beautiful sunsets, and incredible women who are actually down to explore.',
                        'photo_alt'   => 'Private boat excursion in Rio',
                    ],
                    [
                        'author'      => 'Ashley K.',
                        'initials'    => 'AK',
                        'role_tag'    => 'Booked Solo',
                        'trip_name'   => 'Punta Cana Escape',
                        'rating'      => 5,
                        'review_text' => 'The private dinners and VIP airport transfers were top tier. As a solo traveler safety was my #1 priority, and I never felt anything less than 100% safe and looked after the entire week. 10/10 experience!',
                        'photo_alt'   => 'Shared sunset dinner on the terrace',
                    ],
                    [
                        'author'      => 'Morgan L.',
                        'initials'    => 'ML',
                        'role_tag'    => 'Verified Traveler',
                        'trip_name'   => 'Caribbean Hosted Experience',
                        'rating'      => 5,
                        'review_text' => 'Olivia has this natural gift of bringing people together. By day two it felt like I was on vacation with childhood best friends. Already planning my second trip with the collective for 2026!',
                        'photo_alt'   => 'Cocktails and golden hour laughter',
                    ],
                ],
                'title_field' => '{{{ author }}} ({{{ role_tag }}})',
            ]
        );

        $this->end_controls_section();
    }

    protected function render(): void
    {
        $settings = $this->get_settings_for_display();
        $reviews = !empty($settings['reviews_list']) ? $settings['reviews_list'] : [];
        $google_url = !empty($settings['google_url']['url']) ? esc_url($settings['google_url']['url']) : '#';
        $google_target = !empty($settings['google_url']['is_external']) ? ' target="_blank" rel="noopener noreferrer"' : '';
        ?>
        <section id="reviews" class="travelin-reviews-scope" aria-label="Google Reviews">
            <div class="gr-stage">
                <!-- Centered Google Reviews Pill Header -->
                <div class="gr-header-centered">
                    <a href="<?php echo $google_url; ?>" class="gr-google-pill" title="View verified reviews on Google"<?php echo $google_target; ?>>
                        <svg class="gr-google-icon" viewBox="0 0 24 24" width="18" height="18" aria-hidden="true">
                            <path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.66-5.17 3.66-9.17z"/>
                            <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.25v3.15C3.26 21.36 7.34 24 12 24z"/>
                            <path fill="#FBBC05" d="M5.28 14.27c-.25-.72-.38-1.49-.38-2.27s.13-1.55.38-2.27V6.58H1.25C.45 8.18 0 9.97 0 12s.45 3.82 1.25 5.42l4.03-3.15z"/>
                            <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.34 0 3.26 2.64 1.25 6.58l4.03 3.15c.95-2.83 3.6-4.98 6.72-4.98z"/>
                        </svg>
                        <span class="gr-badge-score"><?php echo esc_html($settings['google_score']); ?></span>
                        <div class="gr-badge-stars" aria-label="5 out of 5 stars">
                            <?php for ($s = 0; $s < 5; $s++) : ?>
                                <svg class="gr-star-icon filled" width="12" height="12" viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                            <?php endfor; ?>
                        </div>
                        <span class="gr-badge-sep">•</span>
                        <span class="gr-badge-text"><?php echo esc_html($settings['badge_label']); ?></span>
                        <svg class="gr-ext-icon" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                    </a>
                </div>

                <!-- Review Cards Track -->
                <?php if (!empty($reviews)) : ?>
                    <div class="gr-grid-wrapper">
                        <div class="gr-cards-track">
                            <?php foreach ($reviews as $idx => $review) :
                                $photo_url = !empty($review['photo']['url']) ? esc_url($review['photo']['url']) : '';
                                $rating = isset($review['rating']) ? (int) $review['rating'] : 5;
                            ?>
                                <article class="gr-card">
                                    <div class="gr-card-header">
                                        <div class="gr-author-block">
                                            <div class="gr-avatar" aria-hidden="true"><?php echo esc_html($review['initials']); ?></div>
                                            <div class="gr-author-meta">
                                                <div class="gr-author-name">
                                                    <strong><?php echo esc_html($review['author']); ?></strong>
                                                    <span class="gr-verified-tag" title="Verified traveler">
                                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                                    </span>
                                                </div>
                                                <div class="gr-author-subtitle">
                                                    <span class="gr-tag-pill"><?php echo esc_html($review['role_tag']); ?></span>
                                                    <span class="gr-tag-sep">•</span>
                                                    <span class="gr-trip-name"><?php echo esc_html($review['trip_name']); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <svg class="gr-quote-icon" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 21c3 0 7-1 7-8V5c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v6c0 4.4 2.6 8 7 8zm14 0c3 0 7-1 7-8V5c0-1.1-.9-2-2-2h-4c-1.1 0-2 .9-2 2v6c0 4.4 2.6 8 7 8z"></path></svg>
                                    </div>

                                    <div class="gr-stars" aria-label="<?php echo esc_attr($rating); ?> out of 5 stars">
                                        <?php for ($i = 0; $i < $rating; $i++) : ?>
                                            <svg class="gr-star filled" width="13" height="13" viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                                        <?php endfor; ?>
                                    </div>

                                    <p class="gr-review-text">&ldquo;<?php echo esc_html($review['review_text']); ?>&rdquo;</p>

                                    <?php if (!empty($photo_url)) : ?>
                                        <div class="gr-card-photo-box">
                                            <button type="button" class="gr-photo-trigger" data-photo-src="<?php echo $photo_url; ?>" data-photo-alt="<?php echo esc_attr($review['photo_alt']); ?>" data-author="<?php echo esc_attr($review['author']); ?>" aria-label="View photo submitted by <?php echo esc_attr($review['author']); ?>">
                                                <div class="gr-thumbnail-wrap">
                                                    <img src="<?php echo $photo_url; ?>" alt="<?php echo esc_attr($review['photo_alt']); ?>" loading="lazy" class="gr-thumbnail">
                                                </div>
                                                <span class="gr-photo-badge">Trip Photo</span>
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Lightbox Modal Shell -->
            <div class="gr-lightbox-backdrop" role="dialog" aria-modal="true">
                <div class="gr-lightbox-content">
                    <button type="button" class="gr-lightbox-close" aria-label="Close photo preview">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                    <div class="gr-lightbox-image-wrap">
                        <img src="" alt="" class="gr-lightbox-image">
                    </div>
                    <div class="gr-lightbox-caption">
                        <span></span>
                        <p></p>
                    </div>
                </div>
            </div>
        </section>
        <?php
    }
}
