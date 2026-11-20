<?php
/*----------------------------------------------------
                Banner Settings
-----------------------------------------------------*/

/**
 * See app/options/header.php for what this array is and who reads it.
 *
 * The six `blog_banner_bg_*` fields were one Kirki Background control, a single
 * setting holding six sub-values. Core has no such control, so they are six
 * plain settings here — and because Dynamic_CSS groups every `output` entry by
 * selector, they still come out as one rule block. No template read the old
 * composite key, so nothing had to be migrated.
 */

return [
	'section' => [
		'id'       => 'ha_blog_banner_section',
		'title'    => __( 'Banner Settings', 'hello-animation' ),
		'priority' => 10,
	],

	'fields' => [

		'blog_banner_enable' => [
			'type'        => 'checkbox',
			'label'       => __( 'Banner Enable', 'hello-animation' ),
			'description' => __( 'Enable Banner', 'hello-animation' ),
			'default'     => true,
		],

		'blog_banner_breadcrumb_enable' => [
			'type'        => 'checkbox',
			'label'       => __( 'BreadCrumb Enable', 'hello-animation' ),
			'description' => __( 'Enable Banner breadcrumb', 'hello-animation' ),
			'default'     => true,
		],

		'general_breadcrumb_limit' => [
			'type'        => 'number',
			'label'       => __( 'Breadcrumb Limit (words)', 'hello-animation' ),
			'default'     => 5,
			'input_attrs' => [
				'min'  => 1,
				'max'  => 20,
				'step' => 1,
			],
		],

		'blog_banner_bg_color' => [
			'type'    => 'color',
			'label'   => __( 'Background Color', 'hello-animation' ),
			'default' => '',
			'output'  => [
				[
					'element'  => 'body .default-breadcrumb__area',
					'property' => 'background-color',
				],
			],
		],

		'blog_banner_bg_image' => [
			'type'        => 'image',
			'label'       => __( 'Background Image', 'hello-animation' ),
			'description' => __( 'The five settings below only take effect once an image is chosen.', 'hello-animation' ),
			'default'     => '',
			'output'      => [
				[
					'element'  => 'body .default-breadcrumb__area',
					'property' => 'background-image',
					// url() rather than a bare value — see Dynamic_CSS::value_for().
					'value_pattern' => 'url("$")',
				],
			],
		],

		'blog_banner_bg_repeat' => [
			'type'     => 'select',
			'label'    => __( 'Background Repeat', 'hello-animation' ),
			'default'  => 'repeat',
			'choices'  => [
				'repeat'    => __( 'Repeat', 'hello-animation' ),
				'repeat-x'  => __( 'Repeat Horizontally', 'hello-animation' ),
				'repeat-y'  => __( 'Repeat Vertically', 'hello-animation' ),
				'no-repeat' => __( 'No Repeat', 'hello-animation' ),
			],
			// Pointless without an image, and printing it unconditionally would
			// override whatever the stylesheet sets for the default banner.
			'requires' => 'blog_banner_bg_image',
			'output'   => [
				[
					'element'  => 'body .default-breadcrumb__area',
					'property' => 'background-repeat',
				],
			],
		],

		'blog_banner_bg_position' => [
			'type'     => 'select',
			'label'    => __( 'Background Position', 'hello-animation' ),
			'default'  => 'center center',
			'choices'  => [
				'left top'      => __( 'Left Top', 'hello-animation' ),
				'left center'   => __( 'Left Center', 'hello-animation' ),
				'left bottom'   => __( 'Left Bottom', 'hello-animation' ),
				'center top'    => __( 'Center Top', 'hello-animation' ),
				'center center' => __( 'Center Center', 'hello-animation' ),
				'center bottom' => __( 'Center Bottom', 'hello-animation' ),
				'right top'     => __( 'Right Top', 'hello-animation' ),
				'right center'  => __( 'Right Center', 'hello-animation' ),
				'right bottom'  => __( 'Right Bottom', 'hello-animation' ),
			],
			'requires' => 'blog_banner_bg_image',
			'output'   => [
				[
					'element'  => 'body .default-breadcrumb__area',
					'property' => 'background-position',
				],
			],
		],

		'blog_banner_bg_size' => [
			'type'     => 'select',
			'label'    => __( 'Background Size', 'hello-animation' ),
			'default'  => 'cover',
			'choices'  => [
				'cover'   => __( 'Cover', 'hello-animation' ),
				'contain' => __( 'Contain', 'hello-animation' ),
				'auto'    => __( 'Auto', 'hello-animation' ),
			],
			'requires' => 'blog_banner_bg_image',
			'output'   => [
				[
					'element'  => 'body .default-breadcrumb__area',
					'property' => 'background-size',
				],
			],
		],

		'blog_banner_bg_attachment' => [
			'type'     => 'select',
			'label'    => __( 'Background Attachment', 'hello-animation' ),
			'default'  => 'scroll',
			'choices'  => [
				'scroll' => __( 'Scroll', 'hello-animation' ),
				'fixed'  => __( 'Fixed', 'hello-animation' ),
			],
			'requires' => 'blog_banner_bg_image',
			'output'   => [
				[
					'element'  => 'body .default-breadcrumb__area',
					'property' => 'background-attachment',
				],
			],
		],

		'blog_banner_title_color' => [
			'type'        => 'color',
			'label'       => __( 'Title Color', 'hello-animation' ),
			'description' => __( 'Banner Title color', 'hello-animation' ),
			'default'     => '',
			'output'      => [
				[
					'element'  => 'body .default-breadcrumb__title',
					'property' => 'color',
				],
			],
		],

		'blog_banner_breadcrumb_color' => [
			'type'        => 'color',
			'label'       => __( 'Breadcrumb Color', 'hello-animation' ),
			'description' => __( 'Breadcrumb color', 'hello-animation' ),
			'default'     => '',
			'output'      => [
				[
					'element'  => 'body .default-breadcrumb__list li',
					'property' => 'color',
				],
			],
		],

		'blog_banner_breadcrumb_active_color' => [
			'type'        => 'color',
			'label'       => __( 'Breadcrumb active Color', 'hello-animation' ),
			'description' => __( 'Breadcrumb current page title color', 'hello-animation' ),
			'default'     => '',
			'output'      => [
				[
					'element'  => 'body .default-breadcrumb__list li.active',
					'property' => 'color',
				],
			],
		],
	],
];
