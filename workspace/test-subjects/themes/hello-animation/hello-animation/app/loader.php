<?php
/*----------------------------------------------------
                UTILITY Functions
-----------------------------------------------------*/
require_once HELLO_ANIMATION_THEME_DIR . '/app/utility/global.php';
require_once HELLO_ANIMATION_THEME_DIR . '/app/utility/helpers.php';
require_once HELLO_ANIMATION_THEME_DIR . '/app/utility/util-part.tpl.php';

/*----------------------------------------------------
                Core Classes
-----------------------------------------------------*/
require_once HELLO_ANIMATION_THEME_DIR . '/app/core/blog.class.php';
require_once HELLO_ANIMATION_THEME_DIR . '/app/core/walkernav.class.php';
require_once HELLO_ANIMATION_THEME_DIR . '/app/core/setup.class.php';
require_once HELLO_ANIMATION_THEME_DIR . '/app/core/assets.class.php';
require_once HELLO_ANIMATION_THEME_DIR . '/app/core/enqueue.class.php';
require_once HELLO_ANIMATION_THEME_DIR . '/app/core/default.widgets.class.php';
require_once HELLO_ANIMATION_THEME_DIR . '/app/core/customizer.class.php';
require_once HELLO_ANIMATION_THEME_DIR . '/app/core/dynamic.css.class.php';

/*----------------------------------------------------
                Admin-only
-----------------------------------------------------*/
/**
 * Neither of these has a front-end job, so neither is parsed on a front-end
 * request. Init::get_services() gates the matching services on the same
 * condition — loading one without the other would fatal on `new`.
 *
 * Note what is NOT here: the TGM Plugin Activation library itself. Requiring it
 * at this point is what the theme used to do, and it meant 130 KB across seven
 * classes — plus core's WP_List_Table, which it drags in behind it — parsed on
 * every single wp-admin request, for every logged-in user, whether or not there
 * was anything left to recommend.
 *
 * Plugin_Manager is the small class that decides. It loads the library from
 * `init` at priority 0, and only for a request that got past every gate in
 * Plugin_Manager::should_load(): the right screen, a user who can actually
 * install a plugin, and at least one recommendation still outstanding. On a site
 * where Elementor and Animation Addons are both active, the library is never
 * parsed at all.
 */
if ( is_admin() ) {
    require_once HELLO_ANIMATION_THEME_DIR . '/app/core/plugin-manager.class.php';
    require_once HELLO_ANIMATION_THEME_DIR . '/app/core/admin.notice.class.php';
}

require_once HELLO_ANIMATION_THEME_DIR . '/app/init.php';

if ( class_exists( 'Hello_Animation\\Init' ) ):
    Hello_Animation\Init::register_services();
endif;

