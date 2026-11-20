<?php

namespace Hello_Animation\Core;

/**
 * Settings that are only ever CSS.
 *
 * Nine of the theme's settings — the offcanvas colours, the banner colours and
 * background, the offcanvas alignment — appear in no template. Under Kirki they
 * worked because each field carried an `output` array and the framework turned
 * it into a stylesheet. Take Kirki away and those controls would still save and
 * still do nothing, which is worse than not having them.
 *
 * So the `output` arrays stayed in app/options/*.php and this class is what
 * reads them. Declaring a new colour is still one array entry next to the
 * field, exactly as before — the alternative, an if/echo per setting in a
 * hand-written stylesheet function, puts the selector somewhere other than the
 * control that sets it and drifts within a release or two.
 */
final class Dynamic_CSS {

	/** Handle used by the wp_head fallback below. */
	const FALLBACK_ID = 'hello-animation-dynamic-inline-css';

	public function register() {
		// After Enqueue, which registers the handle this attaches to.
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue' ], 20 );
	}

	public function enqueue() {
		/**
		 * Blank mode means the theme is printing no markup at all, so none of
		 * these selectors exist on the page. Bailing here rather than relying
		 * on the handle check below keeps it explicit: there is nothing to
		 * style, not merely nowhere to put it.
		 */
		if ( is_admin() || Assets::is_blank_mode() ) {
			return;
		}

		$css = self::build();

		if ( '' === $css ) {
			return;
		}

		/**
		 * base.min.css is conditional (Assets::wants), and wp_add_inline_style
		 * attached to a handle that was never enqueued is silently dropped —
		 * the CSS would simply vanish with no error. If the handle is not
		 * there, print the block directly instead.
		 */
		if ( wp_style_is( Assets::BASE, 'enqueued' ) ) {
			wp_add_inline_style( Assets::BASE, $css );

			return;
		}

		add_action(
			'wp_head',
			static function () use ( $css ) {
				printf(
					'<style id="%1$s">%2$s</style>' . "\n",
					esc_attr( self::FALLBACK_ID ),
					wp_strip_all_tags( $css )
				);
			},
			20
		);
	}

	/**
	 * Walk every field carrying an `output` and build the stylesheet.
	 *
	 * @return string
	 */
	public static function build() {
		$rules = [];

		foreach ( Customizer::fields() as $key => $field ) {

			if ( empty( $field['output'] ) ) {
				continue;
			}

			/**
			 * `requires` gates a field on another one having a value. The
			 * background repeat/position/size/attachment settings all declare
			 * a default, so without this they would print on every page and
			 * override whatever the stylesheet says about a banner that has no
			 * background image in the first place.
			 */
			if ( ! empty( $field['requires'] ) ) {
				$required = get_theme_mod(
					$field['requires'],
					Customizer::default_for( $field['requires'] )
				);

				if ( empty( $required ) ) {
					continue;
				}
			}

			$value = get_theme_mod( $key, Customizer::default_for( $key ) );

			// An unset colour must not become `color: ;`, which invalidates the
			// declaration it sits next to as well as itself.
			if ( '' === $value || null === $value || false === $value || [] === $value ) {
				continue;
			}

			foreach ( $field['output'] as $output ) {

				if ( empty( $output['element'] ) ) {
					continue;
				}

				/**
				 * `with` carries declarations that do not vary with the setting
				 * but are what make it mean anything — the offcanvas gallery
				 * has to be a flex row before justify-content can align it.
				 * They ride along with the setting rather than living in
				 * chrome.css because that file is built from SCSS, and a hand
				 * edit there is one `npm run build:css` away from vanishing.
				 */
				if ( ! empty( $output['with'] ) ) {
					foreach ( $output['with'] as $with_property => $with_value ) {
						$rules[ $output['element'] ][ $with_property ] = self::clean( $with_value );
					}
				}

				if ( empty( $output['property'] ) ) {
					continue;
				}

				/**
				 * `value_map` lets one setting drive a property whose vocabulary
				 * is not its own — the offcanvas alignment is stored as
				 * left/center/right for text-align, and the same choice has to
				 * come out as flex-start/center/flex-end for the flex menu.
				 * A value the map does not cover prints nothing rather than
				 * printing the raw one, which would be an invalid declaration.
				 */
				if ( isset( $output['value_map'] ) ) {
					if ( ! isset( $output['value_map'][ $value ] ) ) {
						continue;
					}

					$css_value = $output['value_map'][ $value ];
				} elseif ( isset( $output['value_pattern'] ) ) {
					$css_value = str_replace( '$', $value, $output['value_pattern'] );
				} else {
					$css_value = $value;
				}

				$rules[ $output['element'] ][ $output['property'] ] = self::clean( $css_value );
			}
		}

		return self::to_css( $rules );
	}

	/**
	 * @param array $rules selector => [ property => value ]
	 *
	 * @return string
	 */
	private static function to_css( $rules ) {
		$css = '';

		foreach ( $rules as $element => $properties ) {
			$declarations = '';

			foreach ( $properties as $property => $value ) {
				if ( '' === $value ) {
					continue;
				}

				$declarations .= $property . ':' . $value . ';';
			}

			if ( '' !== $declarations ) {
				$css .= $element . '{' . $declarations . '}';
			}
		}

		return $css;
	}

	/**
	 * Every value here has already been through Customizer::sanitize() — a hex
	 * colour, a URL, or one of a fixed set of keywords. This is the second
	 * line: a stored value that somehow contains a brace or a semicolon could
	 * otherwise close the rule and start writing its own.
	 *
	 * @param string $value
	 *
	 * @return string
	 */
	private static function clean( $value ) {
		return trim( str_replace( [ '{', '}', ';', '<', '>' ], '', (string) $value ) );
	}
}
