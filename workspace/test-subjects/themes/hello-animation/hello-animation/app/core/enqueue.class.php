<?php

namespace Hello_Animation\Core;

/**
 * Enqueue.
 *
 * Every decision about whether an asset is sent lives in Assets, not here. This
 * class only knows how to send them.
 */
class Enqueue
{

	/**
	 * register default hooks and actions for WordPress
	 * @return
	 */
	public function register()
	{
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		// Late, so the handles it removes have all been registered by whoever
		// adds them — core adds the block stylesheets at default priority.
		add_action( 'wp_enqueue_scripts', array( $this, 'dequeue_block_assets' ), 100 );
		// Early in <head>, ahead of the stylesheets, so the fetch starts before
		// the CSS that names the fonts has been downloaded and parsed.
		add_action( 'wp_head', array( $this, 'preload_fonts' ), 2 );
	}

	/**
	 * Start fetching the two Latin text fonts before the CSS asks for them.
	 *
	 * Without this the browser finds out it needs them only after fonts.min.css
	 * is parsed and the text is laid out, so first text paints in the fallback
	 * font and swaps. These are the two files nearly every page uses — DM Sans
	 * for body text, Space Grotesk for headings — and exactly the ones the
	 * unicode-range split would pull for a Latin page anyway, so preloading them
	 * never costs a download the page would not have made.
	 *
	 * Same gate as the stylesheet that declares them: nothing is preloaded when
	 * the theme is not sending its fonts. `crossorigin` is required even for
	 * same-origin fonts; without it the preload is fetched in a different mode
	 * and thrown away, and the font downloads twice.
	 */
	public function preload_fonts()
	{
		if ( is_admin() || Assets::is_blank_mode() || ! Assets::wants( 'fonts' ) || ! hello_animation_use_theme_fonts() ) {
			return;
		}

		foreach ( array( 'dm-sans-latin', 'space-grotesk-latin' ) as $font ) {
			printf(
				'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
				esc_url( HELLO_ANIMATION_THEME_URI . '/assets/fonts/' . $font . '.woff2' )
			);
		}
	}

	public function enqueue_scripts()
	{

		if ( is_admin() ) {
			return;
		}

		/*
		 * style.css, and deliberately above the blank-mode gate.
		 *
		 * The theme's own CSS is base/chrome/blog; style.css carries nothing but
		 * the header and the licence. It is still enqueued, on every front-end
		 * request, for a reason that has nothing to do with this theme's styling:
		 * get_stylesheet_uri() resolves to the *child* theme's style.css whenever
		 * one is active. A parent that never enqueues it hands every child theme
		 * a stylesheet that is silently never loaded — and "my CSS does nothing"
		 * is a hard thing to debug from the child's side.
		 *
		 * That is also why blank mode does not suppress it. Blank mode is a
		 * plugin saying "print none of the theme's CSS", and it still means that:
		 * every sheet below is inside the gate. But a child theme's stylesheet is
		 * the site owner's own CSS, not the theme's, and silently dropping it
		 * would be a surprise no filter asked for.
		 *
		 * wordpress.org requires the stylesheet to be loaded in any case.
		 */
		wp_enqueue_style( Assets::STYLE, get_stylesheet_uri(), array(), HELLO_ANIMATION_VERSION );

		if ( Assets::is_blank_mode() ) {
			return;
		}

		// stylesheets
		// :::::::::::::::::::::::::::::::::::::::::::::::
		// Self-hosted, not fetched from fonts.googleapis.com. fonts.min.css is
		// nothing but @font-face rules pointing at assets/fonts/; the browser
		// picks which of the five files it needs from their unicode-range, so a
		// Latin page still pulls exactly two.
		if ( Assets::wants( 'fonts' ) && hello_animation_use_theme_fonts() ) {
			wp_enqueue_style( Assets::FONTS, HELLO_ANIMATION_CSS . '/fonts.min.css', null, HELLO_ANIMATION_VERSION );
		}

		// One sheet per thing that can be absent from a page, rather than the
		// single 100 KB stylesheet these replaced. base carries the :root
		// palette the other two resolve against, so both declare it as a
		// dependency — that also keeps it first in the printed order, which the
		// cascade needs.
		if ( Assets::wants( 'base' ) ) {
			wp_enqueue_style( Assets::BASE, HELLO_ANIMATION_CSS . '/base.min.css', null, HELLO_ANIMATION_VERSION );
		}

		if ( Assets::wants( 'chrome' ) ) {
			wp_enqueue_style( Assets::CHROME, HELLO_ANIMATION_CSS . '/chrome.min.css', array( Assets::BASE ), HELLO_ANIMATION_VERSION );
		}

		if ( Assets::wants( 'blog' ) ) {
			wp_enqueue_style( Assets::BLOG, HELLO_ANIMATION_CSS . '/blog.min.css', array( Assets::BASE ), HELLO_ANIMATION_VERSION );
		}

		// RTL: each of the three has a mirrored -rtl twin built by rtlcss
		// (build/rtl.mjs). 'replace' swaps the twin in for the LTR sheet on an
		// RTL locale instead of stacking an override on top of it, and 'suffix'
		// tells core the .min sits before .css, so base.min.css maps to
		// base-rtl.min.css rather than base.min-rtl.css. fonts and icons have
		// nothing directional to mirror.
		foreach ( array( Assets::BASE, Assets::CHROME, Assets::BLOG ) as $handle ) {
			wp_style_add_data( $handle, 'rtl', 'replace' );
			wp_style_add_data( $handle, 'suffix', '.min' );
		}

		if ( Assets::wants( 'icons' ) ) {
			wp_enqueue_style( Assets::ICONS, HELLO_ANIMATION_CSS . '/custom-icons.min.css', null, HELLO_ANIMATION_VERSION );
		}

		// javascripts
		// :::::::::::::::::::::::::::::::::::::::::::::::
		if ( Assets::wants( 'script' ) ) {

			// No jQuery dependency: script.js never used it for anything but
			// $(document).ready and $.extend, and declaring it pulled
			// jquery-core plus jquery-migrate — around 90 KB — onto every page
			// of the site for two lines of convenience.
			wp_enqueue_script( Assets::SCRIPT, HELLO_ANIMATION_JS . '/script.min.js', array(), HELLO_ANIMATION_VERSION, array( 'strategy' => 'defer', 'in_footer' => true ) );

			$_data = apply_filters('hello-animation/script/custom/data',[
				 'ajax_url' => admin_url( 'admin-ajax.php' ),
			]);

			wp_localize_script( Assets::SCRIPT, 'helo_anim_obj', $_data);
		}

		// Load WordPress Comment js
		//
		// Inside the blank-mode return above rather than outside it: the atomic
		// comment widgets enqueue this themselves when they render
		// (Comments_NY/class-aae-a-comment-form.php), so a page that has given
		// up the theme's markup still gets threading from whatever is actually
		// drawing the comments.
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}

	/**
	 * Drop the block stylesheets on pages Elementor renders.
	 *
	 * Elementor replaces the_content() wholesale, so not one block runs — and
	 * the theme has the block widget editor switched off (functions.php), so the
	 * sidebars cannot contribute any either. That leaves wp-block-library and
	 * its theme companion as pure dead weight, and they are not small.
	 *
	 * global-styles is deliberately left alone: it is a couple of KB and it
	 * carries the --wp--preset--* custom properties that plugins outside the
	 * block editor still reference.
	 */
	public function dequeue_block_assets()
	{
		if ( is_admin() || ! Assets::is_builder_content() ) {
			return;
		}

		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'wp-block-library-theme' );
		wp_dequeue_style( 'classic-theme-styles' );
	}
}
