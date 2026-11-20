<?php

namespace Hello_Animation\Core;

/**
 * Recommends the two plugins the theme is built to work with, through TGMPA.
 *
 * NEITHER PLUGIN IS REQUIRED
 *
 * The theme renders without both of them, and both entries below say
 * `required => false`. Elementor is what the starter templates are built in and
 * Animation Addons is what animates them, so a site that wants either of those
 * wants the plugins — but a site that does not is a working site. Nothing here
 * force-activates, force-deactivates, bundles a zip, or points at a plugin that
 * is not hosted on wordpress.org; a theme in the directory is not allowed to do
 * any of those, and TGMPA will happily do all four if you let it.
 *
 * WHY THIS CLASS EXISTS AT ALL, RATHER THAN JUST require'ing THE LIBRARY
 *
 * TGMPA is 3,900 lines across seven classes, and merely including it pulls
 * core's WP_List_Table in behind it. Left to itself it does that on every
 * request that satisfies `is_admin() && ! DOING_AJAX` — which is every admin
 * page, for every logged-in user, forever, whether or not there is anything
 * left to recommend.
 *
 * So the library is not loaded from the loader. It is loaded from here, on
 * `init` at priority 0, and only once every gate in should_load() has said yes.
 * On a site where both plugins are already active, that is never: the 130 KB is
 * not parsed, not compiled and not hooked, and the theme costs an admin request
 * exactly this file.
 *
 * WHY init:0 AND NOT THE LOADER
 *
 * Two reasons, and the second is the one that matters.
 *
 * TGMPA's constructor hooks `init` at priorities 5 and 10, and priority 0 is
 * still early enough for both — WordPress re-sorts a hook's remaining
 * priorities when a callback is added to the hook it is currently running, so
 * loading at 0 leaves 5 and 10 ahead of us. Anything later than init:0 would
 * silently do nothing at all: the class would be defined, its callbacks would be
 * registered against an action that had already finished, and TGMPA::init()
 * would never run.
 *
 * And should_load() asks current_user_can(). functions.php is parsed during
 * `setup_theme`, before core has resolved the current user, so asking there
 * would force an early wp_get_current_user() and resolve the session ahead of
 * any authentication a plugin means to swap in. By `init` WordPress has done it
 * itself — WP::init() runs immediately before the action fires — so the answer
 * is free and nothing is disturbed.
 *
 * @see \Hello_Animation_TGM_Plugin_Activation The prefixed library copy.
 */
final class Plugin_Manager {

	/**
	 * TGMPA instance id.
	 *
	 * Also the suffix of the `tgmpa_dismissed_notice_{$id}` user meta key, which
	 * is why it keeps the value the 1.0.x releases of this theme used: someone
	 * who dismissed the notice back then stays dismissed.
	 */
	const TGMPA_ID = 'hello-animation-theme';

	/** Submenu slug under Appearance. */
	const MENU_SLUG = 'hello-animation-install-plugins';

	/**
	 * Who this is for.
	 *
	 * Every button the notice draws — Install, Activate — needs this. Stock
	 * TGMPA gates its notice on `publish_posts` instead, which is Contributor
	 * and up, so on any site with authors it shows a nag to several people who
	 * cannot act on it and cannot make it go away. The library copy carries a
	 * corrected default; this is the same answer, asked before the library is
	 * even loaded.
	 */
	const CAPABILITY = 'install_plugins';

	/**
	 * Capability for the Appearance submenu.
	 *
	 * Matched to the parent menu rather than to what the page does — a submenu
	 * of themes.php is unreachable for anyone who cannot see themes.php. What
	 * the page *does* is gated separately and more tightly: TGMPA's admin_menu()
	 * registers nothing at all without install_plugins, and every install and
	 * activate action re-checks its own capability and nonce.
	 */
	const MENU_CAPABILITY = 'edit_theme_options';

	/**
	 * The admin screens TGMPA may run on.
	 *
	 * Matched against $pagenow, which core sets in wp-includes/vars.php long
	 * before the theme is parsed, so this costs a string comparison and no
	 * database read.
	 *
	 * Dashboard, Appearance, Plugins and Tools: the screens where someone is
	 * already thinking about how the site looks or what is installed. TGMPA
	 * hooks `admin_notices` and `admin_head` unconditionally, so without this
	 * list its notice and its stylesheet turn up while you are writing a post.
	 */
	const SCREENS = [
		'index.php',   // Dashboard.
		'themes.php',  // Appearance, and the parent of TGMPA's own page.
		'plugins.php', // Plugins.
		'tools.php',   // Tools.
	];

	/**
	 * Screens that carry an install or update part-way through.
	 *
	 * Not places the notice should appear — places where dropping the library
	 * mid-flow would break something. TGMPA filters
	 * `install_plugin_complete_actions` and `update_plugin_complete_actions` to
	 * put a "return to the recommended plugins page" link on core's installer
	 * result screen, and its bulk installer runs from `admin_init` on the same
	 * request. Leave these out and an install started from the notice finishes
	 * on a dead end.
	 */
	const ENDPOINTS = [
		'update.php',
		'plugin-install.php',
	];

	public function register() {
		// Registered here, not inside maybe_load(): this has to be listening
		// before TGMPA fires it from its own init() at priority 10, and adding
		// a callback costs nothing on the requests where it never fires.
		add_action( 'hello_animation_tgmpa_register', [ $this, 'register_plugins' ] );

		add_action( 'init', [ $this, 'maybe_load' ], 0 );
	}

	/**
	 * The plugins this theme is built around, in the order they matter.
	 *
	 * Animation Addons is an Elementor addon and will not load without it, so
	 * Elementor comes first — TGMPA lists them in array order and someone
	 * working down the list should not end up installing the addon first.
	 *
	 * @return array
	 */
	public static function plugins() {
		/**
		 * The recommended plugins.
		 *
		 * Filterable so a child theme can add its own or drop one of these
		 * without unhooking the whole thing and reimplementing it. Carried over
		 * from the notice this replaced, under the same name, so a child theme
		 * already using it does not have to change.
		 *
		 * Every entry must be hosted on wordpress.org and must stay
		 * `required => false`; see the note at the top of this file.
		 *
		 * @param array $plugins TGMPA plugin definitions.
		 */
		return (array) apply_filters(
			'hello_animation/recommended_plugins',
			[
				[
					'name'     => __( 'Elementor Website Builder', 'hello-animation' ),
					'slug'     => 'elementor',
					'required' => false,
				],
				[
					'name'     => __( 'Animation Addons for Elementor', 'hello-animation' ),
					'slug'     => 'animation-addons-for-elementor',
					'required' => false,
				],
			]
		);
	}

	/**
	 * Hand the list and the configuration to TGMPA.
	 *
	 * Fired by the library, so it only ever runs on a request that passed
	 * should_load().
	 */
	public function register_plugins() {
		hello_animation_tgmpa(
			self::plugins(),
			[
				'id'           => self::TGMPA_ID,
				'menu'         => self::MENU_SLUG,
				'parent_slug'  => 'themes.php',
				'capability'   => self::MENU_CAPABILITY,
				'has_notices'  => true,
				// Not negotiable for a directory theme, and right anyway: the
				// site works without both plugins, so the suggestion has to be
				// something an owner can put away for good.
				'dismissable'  => true,
				'dismiss_msg'  => '',
				// Activate straight after a successful install, rather than
				// returning the user to a list where the thing they just
				// installed still says "Activate".
				'is_automatic' => true,
				'default_path' => '',
				'message'      => '',
			]
		);
	}

	/**
	 * Parse the library, if this request is one that needs it.
	 */
	public function maybe_load() {
		if ( ! self::should_load() ) {
			return;
		}

		$library = HELLO_ANIMATION_THEME_DIR . '/app/vendor/class-tgm-plugin-activation.php';

		if ( ! file_exists( $library ) ) {
			return;
		}

		// The library self-instantiates on include — it checks
		// did_action('plugins_loaded'), which by init has always fired.
		require_once $library;

		// Presentation only, and only where the notice itself can appear.
		add_filter( 'hello_animation_tgmpa_notice_action_links', [ $this, 'style_action_links' ] );
		add_filter( 'hello_animation_tgmpa_notice_rendered_action_links', [ $this, 'drop_link_separator' ] );
		add_action( 'admin_head', [ $this, 'notice_css' ] );
	}

	/**
	 * Draw the notice's install / update / activate links as buttons.
	 *
	 * TGMPA builds each one from a fixed `<a href="%2$s">%1$s</a>` template and
	 * hands the finished array through this filter, so a class can be added
	 * without reimplementing the links or their capability checks. Dismiss is
	 * left as a link on purpose: it is the quiet way out of the notice, not a
	 * second call to action.
	 *
	 * @param array $links Action links keyed install|update|activate|dismiss.
	 * @return array
	 */
	public function style_action_links( $links ) {
		foreach ( [ 'install', 'update', 'activate' ] as $action ) {
			if ( ! empty( $links[ $action ] ) ) {
				$links[ $action ] = str_replace(
					'<a href=',
					'<a class="hello-animation-tgmpa-button" href=',
					$links[ $action ]
				);
			}
		}

		return $links;
	}

	/**
	 * Drop the pipe TGMPA puts between the action links.
	 *
	 * It implodes them with ' | ', which reads correctly for a row of links and
	 * not at all for a button followed by one. The links are its own fixed
	 * template, so there is nothing else in this string a pipe could belong to.
	 *
	 * @param string $rendered The assembled action links.
	 * @return string
	 */
	public function drop_link_separator( $rendered ) {
		return str_replace( '</a> | <a', '</a> <a', $rendered );
	}

	/**
	 * The notice's own styles.
	 *
	 * WordPress draws this notice from add_settings_error(), which emits a
	 * plain `notice notice-warning` box — the same yellow bar an error uses.
	 * This is not a warning: nothing is broken, nothing is required, and the
	 * theme works exactly as documented if it is dismissed unread. So it takes
	 * the Animation Addons orange instead, which is also what the plugin it
	 * recommends looks like once installed.
	 *
	 * Printed inline rather than enqueued: it is under a kilobyte, it applies
	 * to one element on at most four screens, and a file would cost a request.
	 * Nothing here is printed for a user who has already dismissed the notice —
	 * TGMPA reads the same meta key before rendering.
	 */
	public function notice_css() {
		if ( get_user_meta( get_current_user_id(), 'tgmpa_dismissed_notice_' . self::TGMPA_ID, true ) ) {
			return;
		}

		echo '<style id="hello-animation-plugin-notice">
			#setting-error-tgmpa {
				border-left: 4px solid #ff5f00;
				background: linear-gradient( 90deg, #fff4ec 0%, #fff 340px );
			}
			#setting-error-tgmpa p { color: #1d2327; }
			/* add_settings_error() wraps the whole message in <strong>. */
			#setting-error-tgmpa p > strong { font-weight: 400; }
			#setting-error-tgmpa em { font-style: normal; font-weight: 600; }
			#setting-error-tgmpa a { color: #d94f00; }
			#setting-error-tgmpa .dismiss-notice { color: #646970; font-weight: 400; text-decoration: underline; }
			#setting-error-tgmpa .dismiss-notice:hover { color: #d94f00; }
			#setting-error-tgmpa .hello-animation-tgmpa-button {
				display: inline-block;
				margin: 4px 8px 4px 0;
				padding: 6px 16px;
				border-radius: 4px;
				background: linear-gradient( 90deg, #ff8a00 0%, #ff5f00 100% );
				color: #fff;
				font-weight: 600;
				line-height: 1.6;
				text-decoration: none;
			}
			#setting-error-tgmpa .hello-animation-tgmpa-button:hover,
			#setting-error-tgmpa .hello-animation-tgmpa-button:focus {
				background: linear-gradient( 90deg, #ff7a00 0%, #e55400 100% );
				color: #fff;
			}
			#setting-error-tgmpa .hello-animation-tgmpa-button:focus {
				box-shadow: 0 0 0 2px #fff, 0 0 0 4px #ff5f00;
				outline: 2px solid transparent;
			}
		</style>' . "\n";
	}

	/**
	 * Every gate, cheapest first.
	 *
	 * Ordering is the point. is_admin() is a global read; the capability check
	 * is a handful of array lookups against an already-resolved user; the
	 * outstanding-plugins check reads one autoloaded option. None of them runs
	 * unless the ones above it passed.
	 *
	 * @return bool
	 */
	public static function should_load() {

		// TGMPA is an admin UI. There is no front end to any of it.
		if ( ! is_admin() ) {
			return false;
		}

		/*
		 * Requests that reach admin code without being an admin *page*.
		 *
		 * is_admin() is true for all of these, and stock TGMPA excludes only the
		 * first — so on a site doing any REST traffic at all it was parsing the
		 * library for requests that render no HTML and can show no notice.
		 */
		if ( wp_doing_ajax() || wp_doing_cron() ) {
			return false;
		}

		if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
			return false;
		}

		if ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) {
			return false;
		}

		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			return false;
		}

		/*
		 * Network admin and the user admin have no themes.php to hang the page
		 * off, and on multisite plugin installation is a network capability
		 * anyway. Recommending here would draw a notice that links to a page
		 * that does not exist in this context.
		 */
		if ( is_network_admin() || is_user_admin() ) {
			return false;
		}

		if ( ! self::on_allowed_screen() ) {
			return false;
		}

		/*
		 * Asked before the outstanding check, because it is cheaper and because
		 * it is the honest gate: someone who cannot install or activate a plugin
		 * has no use for any of what follows, whatever is or is not installed.
		 */
		if ( ! current_user_can( self::CAPABILITY ) ) {
			return false;
		}

		/*
		 * The page has to keep working after there is nothing left to suggest —
		 * it is a real admin screen with a real URL and someone may have it
		 * bookmarked. Asked after the capability check, never before.
		 */
		if ( self::is_tgmpa_page() ) {
			return true;
		}

		return self::has_outstanding_plugins();
	}

	/**
	 * @return bool
	 */
	private static function on_allowed_screen() {
		$pagenow = isset( $GLOBALS['pagenow'] ) ? $GLOBALS['pagenow'] : '';

		/**
		 * The admin screens the recommendation may appear on.
		 *
		 * @param array  $screens Values of $pagenow.
		 * @param string $pagenow The current screen.
		 */
		$screens = (array) apply_filters(
			'hello_animation/recommended_plugins/screens',
			array_merge( self::SCREENS, self::ENDPOINTS ),
			$pagenow
		);

		return in_array( $pagenow, $screens, true );
	}

	/**
	 * Is this a request for TGMPA's own install page?
	 *
	 * Reading $_GET['page'] to route, not to act on — no state changes on the
	 * strength of this, so it wants sanitising but not a nonce. Every action the
	 * page then offers carries its own nonce and its own capability check,
	 * inside the library.
	 *
	 * @return bool
	 */
	private static function is_tgmpa_page() {
		if ( ! isset( $GLOBALS['pagenow'] ) || 'themes.php' !== $GLOBALS['pagenow'] ) {
			return false;
		}

		if ( ! isset( $_GET['page'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return false;
		}

		return self::MENU_SLUG === sanitize_key( wp_unslash( $_GET['page'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	}

	/**
	 * Is there anything left to recommend?
	 *
	 * This is where the saving is. Once Elementor and Animation Addons are both
	 * active there is nothing for TGMPA to say, and answering that here costs
	 * one read of an option WordPress has already autoloaded — against parsing
	 * 130 KB of PHP to have the library reach the same conclusion.
	 *
	 * Deliberately not get_plugins(): that reads and parses the header of every
	 * plugin file in wp-content/plugins, which is exactly the kind of work this
	 * check exists to avoid. The trade is that a plugin which is installed but
	 * not active counts as outstanding, which is the right answer anyway — an
	 * inactive plugin is still something the notice should offer to activate.
	 *
	 * @return bool
	 */
	private static function has_outstanding_plugins() {
		$active = self::active_plugin_files();

		foreach ( self::plugins() as $plugin ) {
			if ( empty( $plugin['slug'] ) ) {
				continue;
			}

			if ( ! self::is_active( $plugin['slug'], $active ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @param string $slug   wordpress.org plugin slug.
	 * @param array  $active Plugin files known to be active.
	 *
	 * @return bool
	 */
	private static function is_active( $slug, $active ) {
		$prefix = $slug . '/';

		foreach ( $active as $file ) {
			if ( 0 === strpos( $file, $prefix ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Active plugin files, site and network.
	 *
	 * `active_plugins` is autoloaded, so on a normal request this has already
	 * been fetched and costs nothing to read again. The network list is a site
	 * option, cached the same way, and is only asked for on multisite.
	 *
	 * Matching is by directory name, which for anything installed from
	 * wordpress.org is the slug. A plugin someone renamed on disk reads as
	 * missing — it would then be offered as a recommendation, and TGMPA's own
	 * get_plugins() pass on the install page corrects the picture.
	 *
	 * @return array
	 */
	private static function active_plugin_files() {
		$files = (array) get_option( 'active_plugins', [] );

		if ( is_multisite() ) {
			$files = array_merge(
				$files,
				array_keys( (array) get_site_option( 'active_sitewide_plugins', [] ) )
			);
		}

		return $files;
	}
}
