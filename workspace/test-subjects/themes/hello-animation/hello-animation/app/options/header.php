<?php
/*----------------------------------------------------
                Header Settings
-----------------------------------------------------*/

/**
 * A description of the header settings, not a registration of them.
 *
 * Two consumers read this array: Core\Customizer turns it into panel sections
 * and controls, and Core\Dynamic_CSS turns any `output` key into front-end CSS.
 * Keeping it declarative is what makes adding a colour a one-entry change — the
 * same property the Kirki version had, without the framework.
 *
 * See Core\Customizer::spec() for the accepted keys.
 */

return [
	'section' => [
		'id'       => 'ha_header_section',
		'title'    => __( 'Header Settings', 'hello-animation' ),
		'priority' => 10,
	],

	'fields' => [

		/**
		 * Lives in core's Site Identity section rather than ours, which is where
		 * it has always been — it sits next to the logo it stands in for on
		 * small screens. `section` overrides the file's own section for this
		 * one field.
		 */
		'header_m_menu_icon' => [
			'type'        => 'image',
			'section'     => 'title_tagline',
			'label'       => __( 'Mobile Menu Icon', 'hello-animation' ),
			'description' => __( 'Offcanvas menu Icon.', 'hello-animation' ),
			'default'     => '',
		],

		'header_button_text' => [
			'type'    => 'text',
			'label'   => __( 'Button Text', 'hello-animation' ),
			'default' => __( 'Contact Us', 'hello-animation' ),
		],

		/*
		 * Empty, not a placeholder.
		 *
		 * This defaulted to 'https://yoururl.com/', and because
		 * register_default_filters() makes a declared default what
		 * get_theme_mod() actually returns, every fresh install shipped a live
		 * "Contact Us" button in the header pointing at somebody else's domain.
		 * With no URL set the header now prints no button at all — see
		 * template-parts/headers/header.php.
		 */
		'ha_button_url' => [
			'type'        => 'url',
			'label'       => __( 'Button URL', 'hello-animation' ),
			'description' => __( 'Leave empty to hide the header button.', 'hello-animation' ),
			'default'     => '',
		],

		'ha_button_target' => [
			'type'        => 'checkbox',
			'label'       => __( 'Target Blank', 'hello-animation' ),
			'description' => __( 'Specifies where to open the linked document', 'hello-animation' ),
			'default'     => true,
		],

		'ha_button_rel' => [
			'type'        => 'select',
			'label'       => __( 'Rel Type', 'hello-animation' ),
			'description' => __( 'Specifies the relationship between the current document and the linked document', 'hello-animation' ),
			'default'     => 'nofollow',
			'choices'     => [
				''           => __( 'Follow', 'hello-animation' ),
				'nofollow'   => __( 'No Follow', 'hello-animation' ),
				'noreferrer' => __( 'No Referrer', 'hello-animation' ),
				'noopener'   => __( 'No Opener', 'hello-animation' ),
				'external'   => __( 'External', 'hello-animation' ),
			],
		],
	],
];
