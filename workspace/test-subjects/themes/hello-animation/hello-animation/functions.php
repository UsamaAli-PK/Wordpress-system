<?php

/*----------------------------------------------------
SHORTHAND CONTANTS FOR THEME VERSION
-----------------------------------------------------*/

/*
 * A STRING, not a float. As `1.0` PHP cast it to the number 1, so every asset
 * URL carried `?ver=1` and had done since the theme shipped — a version bump
 * could never bust a cache, because 1.0, 1.1 and 1.1.0 are not three different
 * numbers to a query string. It matters now: custom-icons.min.css and
 * script.min.js both changed content while keeping their filenames.
 *
 * Keep this in step with the Version header in style.css.
 */
 define( 'HELLO_ANIMATION_VERSION', '1.1.10' );

/*----------------------------------------------------
SHORTHAND CONTANTS FOR THEME ASSETS URL
-----------------------------------------------------*/
/*
 * HELLO_ANIMATION_ASSETS and the three *_DIR constants used to be defined here
 * and were read by nothing at all — not the theme, not its templates, not the
 * bundled plugins. If a child theme was using one, define it there.
 */
define( 'HELLO_ANIMATION_THEME_URI', get_template_directory_uri() );
define( 'HELLO_ANIMATION_IMG', HELLO_ANIMATION_THEME_URI . '/assets/imgs' );
define( 'HELLO_ANIMATION_CSS', HELLO_ANIMATION_THEME_URI . '/assets/css' );
define( 'HELLO_ANIMATION_JS', HELLO_ANIMATION_THEME_URI . '/assets/js' );

/*----------------------------------------------------
SHORTHAND CONTANTS FOR THEME DIRECTORY PATH
-----------------------------------------------------*/
define( 'HELLO_ANIMATION_THEME_DIR', get_template_directory() );



/*----------------------------------------------------
LOAD Classes
-----------------------------------------------------*/
if ( file_exists( dirname( __FILE__ ) . '/app/loader.php' ) ):
    require_once dirname( __FILE__ ) . '/app/loader.php';    
endif;
/*----------------------------------------------------
SET UP THE CONTENT WIDTH VALUE BASED ON THE THEME'S DESIGN
-----------------------------------------------------*/
if ( !isset( $content_width ) ) {
    $content_width = 800;
}

add_filter( 'use_widgets_block_editor', '__return_false' );
