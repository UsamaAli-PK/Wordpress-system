<?php
/**
 * Travelin' FAQ Accordion Widget ("Before you book")
 *
 * @package Agency\Companion\Elementor\Widgets\Travelin
 */

declare(strict_types=1);

namespace Agency\Companion\Elementor\Widgets\Travelin;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if (!defined('ABSPATH')) {
    exit;
}

class Widget_Travelin_FAQ extends Widget_Base
{
    public function get_name(): string
    {
        return 'travelin-faq';
    }

    public function get_title(): string
    {
        return esc_html__('Travelin FAQ Accordion', 'agency-companion');
    }

    public function get_icon(): string
    {
        return 'eicon-accordion';
    }

    public function get_categories(): array
    {
        return ['agency-elements'];
    }

    public function get_keywords(): array
    {
        return ['faq', 'accordion', 'questions', 'travelin'];
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
            'section_intro',
            [
                'label' => esc_html__('FAQ Intro', 'agency-companion'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'eyebrow',
            [
                'label'   => esc_html__('Eyebrow', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('A FEW THINGS YOU MIGHT BE WONDERING', 'agency-companion'),
            ]
        );

        $this->add_control(
            'heading_line_1',
            [
                'label'   => esc_html__('Heading Line 1', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('Before', 'agency-companion'),
            ]
        );

        $this->add_control(
            'heading_line_2_italic',
            [
                'label'   => esc_html__('Heading Line 2 (Italic)', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('you book.', 'agency-companion'),
            ]
        );

        $this->add_control(
            'lead_text',
            [
                'label'   => esc_html__('Intro Lead Paragraph', 'agency-companion'),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => "Your money. Your time off.\nYour first group trip.\nLet’s talk about it.",
            ]
        );

        $this->add_control(
            'ask_link_text',
            [
                'label'   => esc_html__('Ask Us Link Text', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('Ask us anything', 'agency-companion'),
            ]
        );

        $this->add_control(
            'ask_link_url',
            [
                'label'   => esc_html__('Ask Us Link URL', 'agency-companion'),
                'type'    => Controls_Manager::URL,
                'default' => [
                    'url'         => 'mailto:Info@travelnliv.com',
                    'is_external' => false,
                ],
            ]
        );

        $this->add_control(
            'all_link_text',
            [
                'label'   => esc_html__('All Questions Link Text', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('All questions', 'agency-companion'),
            ]
        );

        $this->add_control(
            'all_link_url',
            [
                'label'   => esc_html__('All Questions Link URL', 'agency-companion'),
                'type'    => Controls_Manager::URL,
                'default' => [
                    'url'         => '/faq',
                    'is_external' => false,
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_faqs',
            [
                'label' => esc_html__('FAQ Items', 'agency-companion'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'question',
            [
                'label'       => esc_html__('Question', 'agency-companion'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('What does the price cover?', 'agency-companion'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'answer',
            [
                'label'       => esc_html__('Answer', 'agency-companion'),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => 'You’re paying for a 4–5-star stay, the activities in your package, the planning, and Olivia there to host. Meals and transfers vary by trip.',
            ]
        );

        $this->add_control(
            'faq_list',
            [
                'label'       => esc_html__('Questions', 'agency-companion'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'question' => 'What does the price cover?',
                        'answer'   => 'You’re paying for a 4–5-star stay, the activities in your package, the planning, and Olivia there to host. Meals and transfers vary by trip. Check the trip page for the full breakdown; international flights are generally extra. Most trips offer payment plans, with the total and schedule shown at checkout.',
                    ],
                    [
                        'question' => 'How much time off will I need?',
                        'answer'   => 'Check the trip dates against your work schedule, then allow for getting there and home. Flights may mean leaving the day before the trip or arriving home the day after it ends. Ask us about arrival and departure windows before you book if you’re unsure.',
                    ],
                    [
                        'question' => 'Can I join on my own or bring someone?',
                        'answer'   => 'Both. Most travelers book solo, and friends and couples join our general trips too. You can meet other booked travelers in the private trip chat before departure. Any singles-only or other restrictions will be listed on that trip’s page.',
                    ],
                    [
                        'question' => 'Do I have to share a room?',
                        'answer'   => 'You can choose a private room when one is available. On trips with roommate matching, you can also book a shared room on your own and we’ll pair you with another traveler. Check your trip for room options and prices.',
                    ],
                    [
                        'question' => 'Do I have to join every activity or night out?',
                        'answer'   => 'No. You can sit an activity out or have a quiet evening. Missed activities aren’t generally refundable, and you’ll arrange and pay for any transport you need outside the group schedule.',
                    ],
                    [
                        'question' => 'What if I need to cancel?',
                        'answer'   => 'Payments are nonrefundable. Any transfer or credit depends on the terms of your booking and isn’t guaranteed. Read the cancellation policy before paying, and consider travel insurance for eligible unexpected events.',
                    ],
                ],
                'title_field' => '{{{ question }}}',
            ]
        );

        $this->end_controls_section();
    }

    protected function render(): void
    {
        $settings = $this->get_settings_for_display();
        $faqs = !empty($settings['faq_list']) ? $settings['faq_list'] : [];
        $ask_url = !empty($settings['ask_link_url']['url']) ? esc_url($settings['ask_link_url']['url']) : 'mailto:Info@travelnliv.com';
        $all_url = !empty($settings['all_link_url']['url']) ? esc_url($settings['all_link_url']['url']) : '/faq';
        ?>
        <section id="questions" class="travelin-faq travelin-scope">
            <div class="travelin-faq__inner">
                <div class="travelin-faq__intro">
                    <?php if (!empty($settings['eyebrow'])) : ?>
                        <span class="travelin-editorial-label"><?php echo esc_html($settings['eyebrow']); ?></span>
                    <?php endif; ?>

                    <h2 class="travelin-faq__title">
                        <?php echo esc_html($settings['heading_line_1']); ?><br>
                        <em><?php echo esc_html($settings['heading_line_2_italic']); ?></em>
                    </h2>

                    <?php if (!empty($settings['lead_text'])) : ?>
                        <p style="font-size: 15px; line-height: 1.85; color: var(--travelin-muted); margin: 24px 0 32px;">
                            <?php echo nl2br(esc_html($settings['lead_text'])); ?>
                        </p>
                    <?php endif; ?>

                    <div style="display: flex; flex-direction: column; gap: 14px;">
                        <a href="<?php echo $ask_url; ?>" class="travelin-text-link" style="font-size: 14px; color: var(--travelin-foreground); display: inline-flex; align-items: center; gap: 8px;">
                            <?php echo esc_html($settings['ask_link_text']); ?>
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                        </a>
                        <a href="<?php echo $all_url; ?>" class="travelin-text-link" style="font-size: 14px; color: var(--travelin-foreground); display: inline-flex; align-items: center; gap: 8px;">
                            <?php echo esc_html($settings['all_link_text']); ?>
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                        </a>
                    </div>
                </div>

                <?php if (!empty($faqs)) : ?>
                    <div class="travelin-faq__accordion" role="region" aria-label="Frequently Asked Questions">
                        <?php foreach ($faqs as $i => $item) : ?>
                            <div class="travelin-faq__item <?php echo (0 === $i) ? 'is-open' : ''; ?>">
                                <button type="button" class="travelin-faq__question" aria-expanded="<?php echo (0 === $i) ? 'true' : 'false'; ?>">
                                    <span style="display: flex; align-items: baseline; gap: 14px;">
                                        <small style="font-size: 11px; font-family: var(--travelin-sans); color: #7b8474;">0<?php echo ($i + 1); ?></small>
                                        <?php echo esc_html($item['question']); ?>
                                    </span>
                                    <span class="travelin-faq__icon">+</span>
                                </button>
                                <div class="travelin-faq__answer" style="<?php echo (0 === $i) ? 'display: block;' : 'display: none;'; ?>">
                                    <p><?php echo nl2br(esc_html($item['answer'])); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        <?php
    }
}
