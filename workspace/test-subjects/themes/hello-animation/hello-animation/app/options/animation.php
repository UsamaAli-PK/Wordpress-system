<?php
/*----------------------------------------------------
                Animation Settings
-----------------------------------------------------*/

/**
 * See app/options/header.php for what this array is and who reads it.
 *
 * All three are off until somebody switches them on. The theme is called Hello
 * Animation and wants to be seen animated, but motion is also the thing most
 * likely to surprise a site that updated into it, so it is opt-in, switched on
 * from Customizer > Hello Animation > Animations.
 *
 * Who reads them:
 *   ha_anim_menu                  body.ha-motion-menu     header menu, dropdowns, offcanvas
 *   ha_anim_menu_split            body.ha-motion-split    letter-by-letter roll on main menu hover
 *   ha_anim_content               body.ha-motion-content  blog cards, sidebar, post and page content
 *   blog_banner_title_animation   hello_animation_banner_title_animated()
 */

return [
	'section' => [
		'id'       => 'ha_animation_section',
		'title'    => __( 'Animations', 'hello-animation' ),
		'priority' => 5,
	],

	'fields' => [

		'ha_anim_menu' => [
			'type'        => 'checkbox',
			'label'       => __( 'Animate Menu', 'hello-animation' ),
			'description' => __( 'Menu items ease in on load, dropdowns slide open, and the mobile menu staggers its items in.', 'hello-animation' ),
			'default'     => false,
		],

		'ha_anim_menu_split' => [
			'type'        => 'checkbox',
			'label'       => __( 'Split-Text Menu Hover', 'hello-animation' ),
			'description' => __( 'Hovering a main menu item rolls its letters up one after another. Works on its own, and is skipped for Arabic, Hebrew and Indic scripts.', 'hello-animation' ),
			'default'     => false,
		],

		'ha_anim_content' => [
			'type'        => 'checkbox',
			'label'       => __( 'Animate Blog and Content', 'hello-animation' ),
			'description' => __( 'Blog cards, sidebar widgets, post headings and paragraphs ease in, and the rest reveal as you scroll to them.', 'hello-animation' ),
			'default'     => false,
		],

		'blog_banner_title_animation' => [
			'type'        => 'checkbox',
			'label'       => __( 'Animate Banner Title', 'hello-animation' ),
			'description' => __( 'The banner title words rise into place when the banner loads.', 'hello-animation' ),
			'default'     => false,
		],
	],
];
