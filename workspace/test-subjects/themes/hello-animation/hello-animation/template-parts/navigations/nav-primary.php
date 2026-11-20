<?php
/**
 * The desktop navigation.
 *
 * There is no `menu` argument. It used to say `'menu' => 'primary'`, and
 * wp_nav_menu() checks that before it checks theme_location — so a site that
 * happened to have a menu named, or slugged, "primary" got that one rendered
 * here whether or not it was the menu assigned to the Primary location in
 * Appearance → Menus. theme_location is the assignment the user actually made.
 */

wp_nav_menu([
	'theme_location'       => 'primary',
	'container'            => false,
	'menu_id'              => '',
	'menu_class'           => '',
	'depth'                => 3,
	'walker'               => new \Hello_Animation\Core\Hello_Animation_Walker_Nav(),
	'fallback_cb'          => '\Hello_Animation\Core\Hello_Animation_Walker_Nav::fallback',
]);
