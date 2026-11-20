<?php
/**
 * The same menu again, inside the offcanvas panel.
 *
 * The walker is given an id prefix because this is the second copy of the menu
 * on the page: without it every item's DOM id — `menu-item-{$item->ID}` — is
 * printed twice per request. See Hello_Animation_Walker_Nav::$id_prefix.
 *
 * See nav-primary.php for why there is no `menu` argument.
 */

wp_nav_menu([
	'theme_location'       => 'primary',
	'container'            => false,
	'menu_id'              => '',
	'menu_class'           => 'hello-animation-mb-menu-items',
	'depth'                => 3,
	'walker'               => new \Hello_Animation\Core\Hello_Animation_Walker_Nav( 'offcanvas-' ),
	'fallback_cb'          => '\Hello_Animation\Core\Hello_Animation_Walker_Nav::fallback',
]);
