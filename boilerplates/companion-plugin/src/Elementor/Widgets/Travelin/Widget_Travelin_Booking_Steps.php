<?php
/**
 * Travelin' Booking Journey Steps Widget ("Found your trip? Here's what happens next.")
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

class Widget_Travelin_Booking_Steps extends Widget_Base
{
    public function get_name(): string
    {
        return 'travelin-booking-steps';
    }

    public function get_title(): string
    {
        return esc_html__('Travelin Booking Steps', 'agency-companion');
    }

    public function get_icon(): string
    {
        return 'eicon-bullet-list';
    }

    public function get_categories(): array
    {
        return ['agency-elements'];
    }

    public function get_keywords(): array
    {
        return ['booking', 'steps', 'process', 'timeline', 'travelin'];
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
                'label' => esc_html__('Heading & Lead', 'agency-companion'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'heading_line_1',
            [
                'label'   => esc_html__('Heading Line 1', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('Found your trip?', 'agency-companion'),
            ]
        );

        $this->add_control(
            'heading_line_2_italic',
            [
                'label'   => esc_html__('Heading Line 2 (Italic)', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('Here’s what happens next.', 'agency-companion'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_steps',
            [
                'label' => esc_html__('Booking Milestones', 'agency-companion'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'step_number',
            [
                'label'   => esc_html__('Step Number', 'agency-companion'),
                'type'    => Controls_Manager::TEXT,
                'default' => '01',
            ]
        );

        $repeater->add_control(
            'step_title',
            [
                'label'       => esc_html__('Step Title', 'agency-companion'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Find the trip that fits.', 'agency-companion'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'step_description',
            [
                'label'   => esc_html__('Step Description', 'agency-companion'),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => 'Check the dates against your work schedule, including travel days. Review the itinerary, room options, total price, and booking terms.',
            ]
        );

        $this->add_control(
            'steps_list',
            [
                'label'       => esc_html__('Steps', 'agency-companion'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'step_number'      => '01',
                        'step_title'       => 'Find the trip that fits.',
                        'step_description' => 'Check the dates against your work schedule, including travel days. Review the itinerary, room options, total price, and booking terms.',
                    ],
                    [
                        'step_number'      => '02',
                        'step_title'       => 'Reserve your place.',
                        'step_description' => 'Complete your registration and pay the required deposit or full amount. Your place is secured once payment is confirmed.',
                    ],
                    [
                        'step_number'      => '03',
                        'step_title'       => 'Get ready to go.',
                        'step_description' => 'Receive your trip information and join the private group chat. Wait for written trip confirmation and flight guidance before booking nonrefundable flights.',
                    ],
                ],
                'title_field' => '{{{ step_number }}} - {{{ step_title }}}',
            ]
        );

        $this->end_controls_section();
    }

    protected function render(): void
    {
        $settings = $this->get_settings_for_display();
        $steps = !empty($settings['steps_list']) ? $settings['steps_list'] : [];
        ?>
        <section id="booking" class="travelin-booking travelin-scope">
            <div class="travelin-booking__title">
                <h2>
                    <?php echo esc_html($settings['heading_line_1']); ?><br>
                    <em><?php echo esc_html($settings['heading_line_2_italic']); ?></em>
                </h2>
            </div>

            <?php if (!empty($steps)) : ?>
                <div class="travelin-booking__steps">
                    <?php foreach ($steps as $i => $step) : ?>
                        <div class="travelin-booking-step">
                            <span class="travelin-step-number"><?php echo esc_html($step['step_number']); ?></span>
                            <h3><?php echo esc_html($step['step_title']); ?></h3>
                            <p><?php echo nl2br(esc_html($step['step_description'])); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
        <?php
    }
}
