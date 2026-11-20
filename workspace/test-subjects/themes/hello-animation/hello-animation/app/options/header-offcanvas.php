<?php
/*----------------------------------------------------
                Header offcanvas Settings
-----------------------------------------------------*/

/**
 * See app/options/header.php for what this array is and who reads it.
 *
 * Note `ha_offcanvas_galllery_enable` — three l's. That typo is the key
 * template-parts/headers/content-offcanvas.php reads, so it is load-bearing.
 * Same for the "Desciption" label, which is already in the translation
 * catalogue under that spelling.
 */

/**
 * The offcanvas menu links, as chrome.css actually builds them.
 *
 * Named once because two settings point at it — the menu colour and the
 * content alignment — and a selector that two rules disagree about is a bug
 * waiting to happen.
 *
 * `body` leads so the rule outweighs chrome.css's own
 * `.offcanvas__menu-area .hello-animation-mb-menu-items a { color: black }`,
 * which is otherwise more specific than a bare class chain and simply wins.
 * The trailing `.mean-*` pair is the pre-`hello-animation-mb-` markup, kept so
 * a site still running the old mobile-menu script is not left behind.
 */
$ha_offcanvas_menu_links = 'body .offcanvas__menu-area .hello-animation-mb-menu-items a,'
	. ' body .offcanvas__menu-area .dp-menu a,'
	. ' body .offcanvas__menu-wrapper.mean-container .mean-nav ul li a';

/**
 * The same three choices, in the vocabulary flex layouts speak.
 *
 * The offcanvas holds text, a flex menu and a grid gallery, and only the text
 * responds to text-align. Everything else needs the choice translated.
 */
$ha_offcanvas_align_to_flex = [
	'left'   => 'flex-start',
	'center' => 'center',
	'right'  => 'flex-end',
];

return [
	'section' => [
		'id'       => 'ha_offcanvas_section',
		'title'    => __( 'Offcanvas Settings', 'hello-animation' ),
		'priority' => 10,
	],

	'fields' => [

		// Core's Site Identity section, beside the logo it replaces when the
		// offcanvas panel is open. See the note in header.php.
		'offcanvas_logo' => [
			'type'        => 'image',
			'section'     => 'title_tagline',
			'label'       => __( 'Offcanvas Logo', 'hello-animation' ),
			'description' => __( 'The saved value will the URL.', 'hello-animation' ),
			'default'     => '',
		],

		'ha_offcanvas_direction' => [
			'type'        => 'checkbox',
			'label'       => __( 'Left Direction', 'hello-animation' ),
			'description' => __( 'Open Offcanvas From left', 'hello-animation' ),
			'default'     => false,
		],

		'ha_offcanvas_content_align' => [
			'type'        => 'radio',
			'label'       => __( 'Content Align', 'hello-animation' ),
			'description' => __( 'Align Content', 'hello-animation' ),
			'default'     => 'left',
			'choices'     => [
				'left'   => __( 'Left', 'hello-animation' ),
				'center' => __( 'Center', 'hello-animation' ),
				'right'  => __( 'Right', 'hello-animation' ),
			],
			'output'      => [
				/**
				 * start/end rather than left/right: the menu below is aligned
				 * with flex-start/flex-end, which follow the reading direction,
				 * and a physical text-align did not — on an RTL site "Left" put
				 * the menu on the right and the description on the left.
				 */
				[
					'element'   => 'body .offcanvas__body',
					'property'  => 'text-align',
					'value_map' => [
						'left'   => 'start',
						'center' => 'center',
						'right'  => 'end',
					],
				],

				/**
				 * The menu needs saying separately, because text-align cannot
				 * move it. chrome.css lays each menu link out as
				 * `display: flex; justify-content: space-between` — so the
				 * anchor is a flex container, its label is a flex item, and a
				 * flex item is positioned by justify-content, not by the
				 * inherited text-align. The links inherited `text-align: right`
				 * happily and sat on the left regardless, which is what made
				 * this control look broken.
				 *
				 * space-between exists to push the submenu arrow to the far
				 * edge. Replacing it parks the arrow next to its label instead,
				 * which is what an aligned menu should look like anyway.
				 */
				[
					'element'   => $ha_offcanvas_menu_links,
					'property'  => 'justify-content',
					'value_map' => $ha_offcanvas_align_to_flex,
				],

				/**
				 * And the gallery needs saying a third time, for the same
				 * reason in a different layout mode. chrome.css builds it as
				 * `display: grid; grid-template-columns: repeat(3, 1fr)`, and
				 * 1fr tracks always fill the row — so justify-content has no
				 * leftover space to distribute, and two images in a three-wide
				 * grid stay pinned to the left with an empty column beside them
				 * no matter what the alignment says.
				 *
				 * Flex with an explicit one-third basis draws the same three-up
				 * gallery, but a half-filled last row now leaves real slack for
				 * justify-content to move. The 20px is the two 10px gaps
				 * chrome.css puts between three columns.
				 */
				[
					'element'   => 'body .offcanvas__gallery .gallery__items',
					'property'  => 'justify-content',
					'value_map' => $ha_offcanvas_align_to_flex,
					'with'      => [
						'display'   => 'flex',
						'flex-wrap' => 'wrap',
					],
				],
				[
					'element' => 'body .offcanvas__gallery .gallery__item',
					'with'    => [
						'flex' => '0 0 calc((100% - 20px) / 3)',
					],
				],
			],
		],

		/**
		 * Space between a menu label and its submenu +/- toggle. The links are
		 * flex rows (see $ha_offcanvas_menu_links), so `gap` sets it directly
		 * under every alignment. The toggle's tap target reaches back across
		 * this gap (chrome.css), so a wider gap is also a bigger miss margin
		 * between "open the page" and "open the submenu".
		 */
		'ha_offcanvas_menu_icon_gap' => [
			'type'        => 'number',
			'label'       => __( 'Submenu Icon Gap (px)', 'hello-animation' ),
			'description' => __( 'Space between a menu item and its + / - toggle.', 'hello-animation' ),
			'default'     => 10,
			'input_attrs' => [
				'min'  => 0,
				'max'  => 60,
				'step' => 1,
			],
			'output'      => [
				[
					'element'       => $ha_offcanvas_menu_links,
					'property'      => 'gap',
					'value_pattern' => '$px',
				],
			],
		],

		/**
		 * Where the +/- toggle sits in its row. "Next to label" leaves the
		 * links aligned by Content Align with Submenu Icon Gap between label
		 * and toggle. "Space between" spreads each link across the full width:
		 * label at the start, toggle at the far edge.
		 *
		 * It writes the same selector and property as Content Align's
		 * justify-content, and is declared after it, so it wins when set; the
		 * map has no entry for 'next', which prints nothing and leaves
		 * alignment in charge.
		 */
		'ha_offcanvas_menu_icon_position' => [
			'type'        => 'radio',
			'label'       => __( 'Submenu Icon Position', 'hello-animation' ),
			'default'     => 'next',
			'choices'     => [
				'next'    => __( 'Next to label', 'hello-animation' ),
				'between' => __( 'Space between', 'hello-animation' ),
			],
			'output'      => [
				[
					'element'   => $ha_offcanvas_menu_links,
					'property'  => 'justify-content',
					'value_map' => [ 'between' => 'space-between' ],
				],
			],
		],

		'offcanvas_bodycolor_hex' => [
			'type'        => 'color',
			'label'       => __( 'Body Color', 'hello-animation' ),
			'description' => __( 'Offcanvas Body Color', 'hello-animation' ),
			'default'     => '',
			'output'      => [
				[
					'element'  => 'body .offcanvas',
					'property' => 'background-color',
				],
			],
		],

		/**
		 * The selector this carried under Kirki matched nothing.
		 *
		 * It asked for `.offcanvas__menu-wrapper.mean-container .mean-nav ul li a`
		 * — the markup the meanmenu script used to generate. The theme builds
		 * its offcanvas menu itself now, as `.hello-animation-mb-menu-items`,
		 * and `.mean-container` is never added to anything. So the rule was
		 * emitted on every page and matched no element on any of them: the
		 * control saved a colour and the menu stayed black.
		 */
		'offcanvas_menucolor_hex' => [
			'type'        => 'color',
			'label'       => __( 'Menu Color', 'hello-animation' ),
			'description' => __( 'Menu Color', 'hello-animation' ),
			'default'     => '',
			'output'      => [
				[
					'element'  => $ha_offcanvas_menu_links,
					'property' => 'color',
				],
			],
		],

		'ha_offcanvas_content' => [
			'type'    => 'textarea',
			'label'   => __( 'Desciption', 'hello-animation' ),
			'default' => __( 'Your Site Description', 'hello-animation' ),
		],

		'offcanvas_descolor_hex' => [
			'type'        => 'color',
			'label'       => __( 'Desc Color', 'hello-animation' ),
			'description' => __( 'Description Color', 'hello-animation' ),
			'default'     => '',
			'output'      => [
				[
					'element'  => '.offcanvas__logo .desc',
					'property' => 'color',
				],
			],
		],

		'ha_offcanvas_galllery_enable' => [
			'type'        => 'checkbox',
			'label'       => __( 'Gallery Enable', 'hello-animation' ),
			'description' => __( 'Enable Offcanvas Gallery', 'hello-animation' ),
			'default'     => true,
		],

		'ha_offcanvas_gallery_title' => [
			'type'    => 'text',
			'label'   => __( 'Heading', 'hello-animation' ),
			'default' => __( 'Gallery', 'hello-animation' ),
		],

		'offcanvas_g_headingcolor_hex' => [
			'type'        => 'color',
			'label'       => __( 'Color', 'hello-animation' ),
			'description' => __( 'Heading Color', 'hello-animation' ),
			'default'     => '',
			'output'      => [
				[
					'element'  => '.offcanvas__gallery .offcanvas__title',
					'property' => 'color',
				],
			],
		],

		/**
		 * Row shape is fixed by the reader: content-offcanvas.php loops the
		 * saved value and reads $item['image'] and $item['link_url'] straight
		 * into src and href attributes. Changing these keys breaks the panel.
		 */
		'ha_offcanvas_gallery' => [
			'type'    => 'repeater',
			'label'   => __( 'Gallery Images', 'hello-animation' ),
			'default' => [],
			'fields'  => [
				'image'    => [
					'type'  => 'image',
					'label' => __( 'Image', 'hello-animation' ),
				],
				'link_url' => [
					'type'  => 'url',
					'label' => __( 'Link URL', 'hello-animation' ),
				],
			],
		],
	],
];
