<?php

namespace Hello_Animation\Core;

/**
 * The one place that decides whether a theme-owned asset is sent.
 *
 * Nothing else in the theme should call wp_enqueue_* directly — ask here first.
 * A single gate is what lets a plugin switch the theme off wholesale for pages
 * it renders itself, without that plugin having to know which handles exist or
 * which template is running.
 *
 * THE CONTRACT WITH PLUGINS
 *
 * `hello_animation/assets/blank_mode` is asked once per request. Return true and
 * the theme prints no CSS and no JS at all — not the stylesheet, not the icon
 * font, not the Google Fonts, and (since nothing is left that needs it) no
 * jQuery. The filter receives the two facts only the theme can establish:
 *
 *   is_elementor_content  Elementor is painting this view's content.
 *   renders_theme_chrome  The theme's own header/footer are still rendering.
 *
 * What the theme deliberately does NOT decide is whether the page's widgets are
 * new enough to stand on their own — that is a question about a plugin's widget
 * registry, and answering it here would mean hard-coding another product's
 * internals into a theme. Animation Addons Pro answers it through this filter.
 *
 * With no plugin listening the filter returns its `false` default and the theme
 * behaves exactly as it always has, which is the point: blank mode is something
 * a site opts into, never a default a theme update imposes.
 */
final class Assets {

	/**
	 * The theme's declared stylesheet — style.css, via get_stylesheet_uri().
	 *
	 * It carries no rules of its own; the theme's CSS is base/chrome/blog. It is
	 * enqueued anyway, and unconditionally, because it is the seam a child theme
	 * hangs off: get_stylesheet_uri() resolves to the *child's* style.css when
	 * one is active, so a theme that never enqueues it gives every child theme a
	 * stylesheet that is never loaded. Loading it is also a wordpress.org
	 * requirement in its own right.
	 */
	const STYLE  = 'hello-animation-style';

	const FONTS  = 'hello-animation-fonts';
	const BASE   = 'hello-animation-base';
	const CHROME = 'hello-animation-chrome';
	const BLOG   = 'hello-animation-blog';

	/**
	 * The icon font.
	 *
	 * Was `wcf-custom-icons`, the one handle in the theme carrying another
	 * product's prefix. Nothing shared it: Animation Addons uses
	 * `wcf-custom-icons` as a post type name, and enqueues its own icon styles
	 * under `wcf-addon-pro-custom-icons` on admin screens only. So the handle
	 * was not a deliberate join between the two, just a name that escaped the
	 * rename — and an unprefixed handle is a collision waiting for whichever
	 * plugin picks the same obvious string.
	 */
	const ICONS  = 'hello-animation-icons';

	const SCRIPT = 'hello-animation-script';

	/** Per-request answers. Keyed by question, not by handle. */
	private static $memo = [];

	public function register() {
		// Without this, Theme Builder headers and footers never take over from
		// the theme's own: elementor_theme_do_location() returns false for a
		// location nobody registered, and header.php falls through to its own
		// markup. It also makes renders_theme_chrome() meaningful — with no
		// locations registered it can only ever answer "yes, the theme renders".
		add_action( 'elementor/theme/register_locations', array( $this, 'register_elementor_locations' ) );
	}

	public function register_elementor_locations( $manager ) {
		$manager->register_all_core_location();
	}

	/**
	 * Should the theme print nothing at all this request?
	 *
	 * Asked in the Elementor preview iframe too, on purpose. The editor is only
	 * useful if it shows what the front end will show, so both sides run the
	 * same rule rather than the editor being special-cased into always loading.
	 *
	 * @return bool
	 */
	public static function is_blank_mode() {
		return self::memo( 'blank', function () {
			return (bool) apply_filters(
				'hello_animation/assets/blank_mode',
				false,
				array(
					'is_elementor_content' => self::is_builder_content(),
					'renders_theme_chrome' => self::renders_theme_chrome(),
					'post_id'              => is_singular() ? get_queried_object_id() : 0,
				)
			);
		} );
	}

	/**
	 * @param string $part One of: fonts, style, icons, script.
	 *
	 * @return bool
	 */
	public static function wants( $part ) {
		if ( self::is_blank_mode() ) {
			return false;
		}

		// Re-entry answers "yes, send it". `icons` legitimately asks about
		// `chrome` and `blog` from inside its own default, and a filter callback
		// may ask about anything at all.
		return self::memo( 'want:' . $part, function () use ( $part ) {
			return (bool) apply_filters(
				'hello_animation/assets/load',
				self::default_for( $part ),
				$part
			);
		}, true );
	}

	/**
	 * The theme's own opinion, before any filter.
	 *
	 * `base` and `fonts` are unconditional on anything short of blank mode:
	 * base carries the :root palette the other two sheets resolve their colours
	 * against, and the fonts are named in --font-primary / --font-secondary, so
	 * dropping either would leave the rest of the theme's CSS half-applied.
	 */
	private static function default_for( $part ) {
		switch ( $part ) {
			case 'chrome':
				return self::renders_theme_chrome();

			case 'script':
				// script.js drives one thing: the offcanvas menu in the theme's
				// header. Once Theme Builder owns that location the markup is
				// gone and the file has nothing left to bind to.
				return self::renders_theme_chrome();

			case 'icons':
				// NOT chrome-only, however much the offcanvas close button
				// suggests it: the blog card arrow (template-parts/blog/
				// content.php), the banner breadcrumb chevron and searchform.php
				// all draw from this font, and the search form turns up in the
				// sidebar and footer widgets. So it follows the sheets rather
				// than the header — including through the filter, so switching
				// one of them off takes the icons with it.
				return self::wants( 'chrome' ) || self::wants( 'blog' );

			case 'blog':
				// The blog sheet is more than posts — page.php and the homepage
				// template wrap their content in .default-blog__area /
				// .default-blog__grid, so an ordinary Elementor page still needs
				// it. Elementor's own page templates are the exception: Canvas
				// and Full Width bypass those templates entirely and print their
				// own wrapper, so nothing on such a page can match.
				return ! self::uses_elementor_page_template();

			default:
				return true;
		}
	}

	/**
	 * Is this view rendered by one of Elementor's page templates rather than the
	 * theme's own?
	 *
	 * `elementor_theme` is deliberately absent: that one means "use the theme's
	 * template", which is precisely the case where the theme's markup is in play.
	 *
	 * @return bool
	 */
	public static function uses_elementor_page_template() {
		return in_array(
			self::page_template(),
			array( 'elementor_canvas', 'elementor_header_footer' ),
			true
		);
	}

	/**
	 * Is Elementor painting this view's content?
	 *
	 * @return bool
	 */
	public static function is_builder_content() {
		return self::memo( 'builder', function () {
			if ( ! is_singular() || ! did_action( 'elementor/loaded' ) ) {
				return false;
			}

			$document = \Elementor\Plugin::$instance->documents->get( get_queried_object_id() );

			return $document && $document->is_built_with_elementor();
		} );
	}

	/**
	 * Will the theme's own header/footer markup be printed this request?
	 *
	 * Both have to be claimed before the answer is no. A Theme Builder header
	 * over the theme's own footer still leaves footer markup on the page, and
	 * that markup needs the stylesheet.
	 *
	 * Asked here rather than read from elementor_theme_do_location()'s return
	 * value because styles are printed by wp_head(), which header.php calls
	 * before it gets anywhere near the header template part.
	 *
	 * @return bool
	 */
	public static function renders_theme_chrome() {
		return self::memo( 'chrome', function () {
			$renders = true;

			// Elementor's Canvas template calls neither get_header() nor
			// get_footer(), so no chrome of any origin reaches the page.
			if ( 'elementor_canvas' === self::page_template() ) {
				$renders = false;
			}

			// Elementor Pro's Theme Builder, when that is the one in play. The
			// second argument is what makes this a real answer: it asks whether
			// a template matches THIS request's conditions, not merely whether
			// one exists somewhere in the library.
			if ( $renders
				&& function_exists( 'elementor_theme_do_location' )
				&& class_exists( '\ElementorPro\Modules\ThemeBuilder\Module' ) ) {

				$locations = \ElementorPro\Modules\ThemeBuilder\Module::instance()->get_locations_manager();

				$renders = ! ( $locations->location_exits( 'header', true )
					&& $locations->location_exits( 'footer', true ) );
			}

			/**
			 * Header/footer builders the theme has no way to know about answer
			 * here — Animation Addons ships one of its own, and it replaces
			 * header.php wholesale from `get_header` rather than going through
			 * Elementor Pro's location system, so nothing above can see it.
			 *
			 * Return false only when BOTH locations are claimed. A builder
			 * header over the theme's own footer still leaves footer markup on
			 * the page, and that markup needs the stylesheet.
			 *
			 * @param bool $renders Whether the theme's own chrome will render.
			 */
			return (bool) apply_filters( 'hello_animation/assets/renders_theme_chrome', $renders );
		}, true );   // Re-entry answers "the theme does render its chrome" — keeps the stylesheet.
	}

	/** The current view's page template slug, '' when there isn't one. */
	private static function page_template() {
		return self::memo( 'page_template', function () {
			if ( ! is_singular() ) {
				return '';
			}

			return basename( (string) get_page_template_slug( get_queried_object_id() ) );
		} );
	}

	/**
	 * Answer a question once per request.
	 *
	 * The seed is not an optimisation, it is a re-entrancy guard. Two of the
	 * questions below fire a filter while working out their answer, and a
	 * callback on that filter is free to ask the gate something — Animation
	 * Addons' own does. Writing the key only after the closure returned would
	 * leave it unset for the whole of that call, so the re-entrant question
	 * would run the filter again, and again, until the stack gave out.
	 *
	 * Seeding first turns that into a finite, defined answer. $reentry is what
	 * the nested caller sees, so it must be the conservative direction in every
	 * case: keep the assets rather than drop them, because a wrong "drop" ships
	 * an unstyled page and a wrong "keep" only ships bytes.
	 *
	 * @param string   $key
	 * @param callable $answer
	 * @param bool     $reentry Answer given to a call that arrives while $answer is still running.
	 *
	 * @return bool
	 */
	private static function memo( $key, callable $answer, $reentry = false ) {
		if ( array_key_exists( $key, self::$memo ) ) {
			return self::$memo[ $key ];
		}

		self::$memo[ $key ] = $reentry;
		self::$memo[ $key ] = $answer();

		return self::$memo[ $key ];
	}
}
