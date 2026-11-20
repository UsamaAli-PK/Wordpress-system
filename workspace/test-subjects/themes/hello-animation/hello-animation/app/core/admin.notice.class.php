<?php

namespace Hello_Animation\Core;

/**
 * Tells site owners why the Kirki plugin is no longer asked for.
 *
 * The theme used to recommend the Kirki Customizer Framework and register every
 * setting through it. Kirki has since become a page-builder product rather than
 * the options framework it was, so the theme now builds its settings straight
 * on core's Customizer API. Nothing an owner saved has moved or been renamed —
 * but a plugin quietly disappearing from the recommended list, and another one
 * left installed doing nothing, is worth one sentence of explanation.
 *
 * It goes away for good in one of two ways, and both are permanent: the X, or
 * simply having been shown MAX_VIEWS times. The second matters more than it
 * looks — an announcement nobody dismisses is an announcement that nags
 * forever, and the people most likely to ignore the X are the ones who already
 * read it. Three screens is enough for a sentence this short.
 *
 * Both are stored per user rather than per site: the notice explains a change
 * to someone who administers the site, and a second administrator has not read
 * it just because the first one clicked the X.
 */
class Admin_Notice {

	/**
	 * How many times one user may be shown the notice before it retires itself.
	 */
	const MAX_VIEWS = 3;

	/**
	 * Views so far, per user. Leading underscore keeps it out of the Custom
	 * Fields UI.
	 *
	 * One counter carries both states rather than a counter plus a dismissed
	 * flag: dismissing writes MAX_VIEWS, which is the same thing the third view
	 * arrives at on its own. Every check is then a single read and a single
	 * comparison, and there is no way for the two to disagree.
	 */
	const META = '_hello_animation_kirki_notice_views';

	/** Nonce action and the AJAX action, which share a name deliberately. */
	const ACTION = 'hello_animation_dismiss_kirki_notice';

	/** Who the notice is for, and who may dismiss it. */
	const CAPABILITY = 'edit_theme_options';

	public function register() {
		add_action( 'admin_notices', [ $this, 'render' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue' ] );
		add_action( 'wp_ajax_' . self::ACTION, [ $this, 'ajax_dismiss' ] );
	}

	/**
	 * @return bool
	 */
	private function should_show() {
		if ( ! current_user_can( self::CAPABILITY ) ) {
			return false;
		}

		return $this->views() < self::MAX_VIEWS;
	}

	/**
	 * @return int
	 */
	private function views() {
		return (int) get_user_meta( get_current_user_id(), self::META, true );
	}

	public function render() {
		if ( ! $this->should_show() ) {
			return;
		}

		/**
		 * Only worth saying if the plugin is actually still sitting there. Asked
		 * of the plugin file rather than a class, because Kirki is loaded on
		 * `plugins_loaded` and admin_notices runs long after — but a deactivated
		 * plugin that is still installed defines no class at all, and "you can
		 * delete it" is exactly the case worth mentioning.
		 */
		$kirki_present = file_exists( WP_PLUGIN_DIR . '/kirki/kirki.php' ) || class_exists( 'Kirki' );
		?>
		<div id="hello-animation-kirki-notice" class="notice notice-info is-dismissible">
			<p>
				<strong><?php esc_html_e( 'Hello Animation no longer needs the Kirki plugin.', 'hello-animation' ); ?></strong>
			</p>
			<p>
				<?php
				esc_html_e(
					'Kirki has turned into a page-builder product rather than the options framework this theme used it as, so the theme\'s settings are now built directly on the WordPress Customizer. Every setting keeps the name and the value it already had — nothing needs to be re-entered.',
					'hello-animation'
				);
				?>
			</p>

			<?php if ( $kirki_present ) : ?>
				<p>
					<?php esc_html_e( 'The Kirki plugin is still installed on this site. The theme no longer uses it, so you can deactivate and delete it whenever you like.', 'hello-animation' ); ?>
				</p>
			<?php endif; ?>

			<p>
				<a class="button button-secondary" href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>">
					<?php esc_html_e( 'Open the Customizer', 'hello-animation' ); ?>
				</a>
			</p>
		</div>
		<?php

		/**
		 * Counted after printing, so the third view is one the user actually
		 * gets rather than the one that retires the notice. enqueue() runs on
		 * admin_enqueue_scripts, which fires before admin_notices — it reads the
		 * count and never writes it, so the dismiss script is always present on
		 * a screen that shows the notice, including the last one.
		 */
		update_user_meta( get_current_user_id(), self::META, $this->views() + 1 );
	}

	/**
	 * Core draws the dismiss button and hides the notice; it does not remember
	 * anything. This is the half that makes "forever" true.
	 */
	public function enqueue() {
		if ( ! $this->should_show() ) {
			return;
		}

		// `common` is core's own admin script — always present here, already
		// depends on jQuery, and is what draws the dismiss button in the first
		// place, so the handler cannot be bound before the button exists.
		wp_add_inline_script(
			'common',
			sprintf(
				'jQuery( function ( $ ) {
	$( document ).on( "click", "#hello-animation-kirki-notice .notice-dismiss", function () {
		$.post( window.ajaxurl, { action: %1$s, nonce: %2$s } );
	} );
} );',
				wp_json_encode( self::ACTION ),
				wp_json_encode( wp_create_nonce( self::ACTION ) )
			)
		);
	}

	public function ajax_dismiss() {
		// Nonce first, then capability: a valid nonce proves the request came
		// from our screen, the capability proves this user was allowed to be
		// looking at it. Neither alone is enough.
		check_ajax_referer( self::ACTION, 'nonce' );

		if ( ! current_user_can( self::CAPABILITY ) ) {
			wp_send_json_error( null, 403 );
		}

		// Jump straight to the cap: dismissing early and running out of views
		// are the same end state, so they are the same stored value.
		update_user_meta( get_current_user_id(), self::META, self::MAX_VIEWS );

		wp_send_json_success();
	}
}
