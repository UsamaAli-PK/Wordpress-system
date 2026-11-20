<?php

namespace Hello_Animation\Core;

/**
 * The theme's settings, on core's Customizer instead of a framework.
 *
 * This replaces the Kirki Customizer Framework, which the theme used to ask
 * people to install before it would show a single setting. Every key, label,
 * default and section title is carried over unchanged, so no template that
 * reads get_theme_mod() had to be touched.
 *
 * WHERE THE SETTINGS ARE DESCRIBED
 *
 * Not here. app/options/*.php each return a plain array describing one section
 * and its fields; this class turns that description into panels, sections,
 * settings and controls, and Dynamic_CSS turns the `output` entries in the same
 * arrays into front-end CSS. Two consumers, one description — which is what
 * keeps adding a colour a one-entry change rather than an edit in three files.
 *
 * ACCEPTED FIELD KEYS
 *
 *   type         Required. text|textarea|url|checkbox|select|radio|number|
 *                color|image|repeater
 *   label        Required. Shown in the customizer.
 *   description  Optional help text under the control.
 *   default      Optional but effectively required — see the note on defaults
 *                below.
 *   choices      select|radio. value => label. The sanitizer treats it as the
 *                allowlist, so an unknown value can never be stored.
 *   input_attrs  number. min/max/step, used for the input and for clamping.
 *   fields       repeater. Row shape; see Controls\Repeater_Control.
 *   section      Overrides the section from the file, for the two fields that
 *                belong in core's Site Identity.
 *   requires     Another field's key. The control hides until that field has a
 *                value, and Dynamic_CSS skips its rule.
 *   output       [ [ element, property, value_pattern ], ... ] for Dynamic_CSS.
 */
final class Customizer {

	/** Panel id. Kept from the Kirki version — nothing else refers to it. */
	const PANEL = 'hello_animation_theme_panel';

	/** The option files, in the order their sections should appear. */
	const FILES = [ 'animation', 'header', 'header-offcanvas', 'banner', 'blog', 'footer' ];

	/** Per-request memo for spec() and fields(). */
	private static $spec   = null;
	private static $fields = null;

	/**
	 * Per-request memo for get_theme_mods().
	 *
	 * The default filters below fire on every single get_theme_mod() call in the
	 * theme, and each one asked get_theme_mods() afresh — so every read of a
	 * setting cost two reads of the theme_mods option instead of one, measured
	 * across the 40-odd keys. The option is autoloaded and cached, so this was
	 * never a database round trip, but it was still the whole option array being
	 * fetched and returned twice per read for no reason.
	 *
	 * Held until something writes a theme mod; see flush_mods().
	 *
	 * @var array|null
	 */
	private static $mods = null;

	public function register() {
		add_action( 'customize_register', [ $this, 'customize_register' ] );

		/**
		 * Defaults have to be filtered in, not merely registered.
		 *
		 * get_theme_mod( $key, $default ) falls back to the *caller's* second
		 * argument; the default handed to add_setting() only seeds the control
		 * in the customizer UI. So without this, a template that reads
		 * get_theme_mod('general_breadcrumb_limit') with no argument of its own
		 * gets '' while the control cheerfully displays 5. Kirki papered over
		 * the same gap with the same filter.
		 *
		 * Filtering each key individually rather than merging defaults into the
		 * `option_theme_mods_*` array is deliberate: set_theme_mod() reads that
		 * option and writes it straight back, so an option-level merge would
		 * bake today's defaults into the database the first time anyone saved
		 * anything, and a later theme update could never change them again.
		 *
		 * Deferred to after_setup_theme:20, and that timing matters. register()
		 * runs from the loader while functions.php is still executing, long
		 * before Theme_Setup calls load_theme_textdomain() at priority 10 — and
		 * reading the spec means running every __() in it. Do that too early
		 * and the labels are cached untranslated for the rest of the request,
		 * which since WordPress 6.7 also earns a _doing_it_wrong() notice.
		 *
		 * Nothing reads a theme mod this early, so there is no race: templates
		 * and the customizer both run long after after_setup_theme.
		 */
		add_action( 'after_setup_theme', [ $this, 'register_default_filters' ], 20 );
	}

	/**
	 * Load the five option files.
	 *
	 * Loaded on front-end requests too, and that is not waste: Dynamic_CSS
	 * needs the same arrays to build its inline CSS, and the default filters
	 * above need the key list. Five small arrays of strings, measured at well
	 * under half a millisecond to read the lot.
	 *
	 * @return array
	 */
	public static function spec() {
		if ( null !== self::$spec ) {
			return self::$spec;
		}

		self::$spec = [];

		foreach ( self::FILES as $file ) {
			$path = HELLO_ANIMATION_THEME_DIR . '/app/options/' . $file . '.php';

			if ( ! file_exists( $path ) ) {
				continue;
			}

			$group = require $path;

			if ( is_array( $group ) && ! empty( $group['fields'] ) ) {
				self::$spec[ $file ] = $group;
			}
		}

		return self::$spec;
	}

	/**
	 * Every field, flattened to key => field, with `section` resolved.
	 *
	 * @return array
	 */
	public static function fields() {
		if ( null !== self::$fields ) {
			return self::$fields;
		}

		self::$fields = [];

		foreach ( self::spec() as $group ) {
			$section = isset( $group['section']['id'] ) ? $group['section']['id'] : '';

			foreach ( $group['fields'] as $key => $field ) {
				if ( empty( $field['section'] ) ) {
					$field['section'] = $section;
				}

				self::$fields[ $key ] = $field;
			}
		}

		return self::$fields;
	}

	/**
	 * The declared default for one field, or $fallback if it has none.
	 *
	 * @param string $key
	 * @param mixed  $fallback
	 *
	 * @return mixed
	 */
	public static function default_for( $key, $fallback = '' ) {
		$fields = self::fields();

		return isset( $fields[ $key ] ) && array_key_exists( 'default', $fields[ $key ] )
			? $fields[ $key ]['default']
			: $fallback;
	}

	public function register_default_filters() {
		foreach ( self::fields() as $key => $field ) {
			if ( ! array_key_exists( 'default', $field ) ) {
				continue;
			}

			$default = $field['default'];

			add_filter(
				'theme_mod_' . $key,
				static function ( $value ) use ( $key, $default ) {
					// Absence, not falsiness. A checkbox someone deliberately
					// switched off stores false, and substituting the default
					// for it would make every switch impossible to turn off —
					// which is exactly the bug the old hello_animation_option()
					// wrapper had.
					return array_key_exists( $key, self::mods() ) ? $value : $default;
				}
			);
		}

		// Held only as long as the stored mods are unchanged.
		add_action( 'updated_option', [ __CLASS__, 'flush_mods' ] );
		add_action( 'added_option', [ __CLASS__, 'flush_mods' ] );
		add_action( 'deleted_option', [ __CLASS__, 'flush_mods' ] );
	}

	/**
	 * The stored theme mods, read once per request.
	 *
	 * @return array
	 */
	public static function mods() {
		if ( null === self::$mods ) {
			$mods = get_theme_mods();

			self::$mods = is_array( $mods ) ? $mods : [];
		}

		return self::$mods;
	}

	/**
	 * Drop the memo when the mods option is written.
	 *
	 * set_theme_mod() goes through update_option(), so this catches every write
	 * — including the customizer saving a whole batch of settings in one
	 * request, where the later reads must see what the earlier writes stored.
	 *
	 * @param string $option Name of the option that changed.
	 */
	public static function flush_mods( $option ) {
		if ( 0 === strpos( (string) $option, 'theme_mods_' ) ) {
			self::$mods = null;
		}
	}

	/**
	 * @param \WP_Customize_Manager $wp_customize
	 */
	public function customize_register( $wp_customize ) {

		$wp_customize->add_panel(
			self::PANEL,
			[
				'priority'    => 10,
				'title'       => esc_html__( 'Hello Animation', 'hello-animation' ),
				'description' => esc_html__( 'Hello Animation Theme Settings.', 'hello-animation' ),
			]
		);

		foreach ( self::spec() as $group ) {
			if ( empty( $group['section']['id'] ) ) {
				continue;
			}

			$wp_customize->add_section(
				$group['section']['id'],
				[
					'title'    => $group['section']['title'],
					'panel'    => self::PANEL,
					'priority' => isset( $group['section']['priority'] ) ? $group['section']['priority'] : 10,
				]
			);
		}

		foreach ( self::fields() as $key => $field ) {
			$this->add_field( $wp_customize, $key, $field );
		}
	}

	/**
	 * @param \WP_Customize_Manager $wp_customize
	 * @param string                $key
	 * @param array                 $field
	 */
	private function add_field( $wp_customize, $key, $field ) {

		/**
		 * The repeater stores an array but its control is one hidden input.
		 *
		 * Core links that input to the setting and, on load, writes the
		 * setting's value into it — and jQuery given an array writes
		 * "a,b" into a text input, destroying the JSON before the user has
		 * touched anything. sanitize_js_callback is the hook for exactly this:
		 * it decides what the *JavaScript* side sees, so JS gets the string it
		 * expects while PHP keeps the array the templates loop over.
		 *
		 * Null for every other type. WP_Customize_Setting reads its arguments
		 * with isset(), so a null here is not merely ignored but genuinely the
		 * same as never passing the key at all.
		 */
		$sanitize_js = 'repeater' === $field['type']
			? static function ( $value ) {
				return is_string( $value ) ? $value : wp_json_encode( $value );
			}
			: null;

		/*
		 * The arguments are spelled out inside the call rather than built up in
		 * a variable first, and that is not a matter of taste.
		 *
		 * Theme Check's Customizer_Check does not parse PHP. It greps the text
		 * between `add_setting(` and the next semicolon for the literal string
		 * "sanitize_callback". Handed `add_setting( $key, $setting_args )` it
		 * finds nothing, and reports a setting named `$key` with no sanitiser —
		 * a REQUIRED failure that rejects the wordpress.org upload outright,
		 * however plainly the callback is sitting in the array one line above.
		 * So the array lives here, where the scanner can see it.
		 */
		$wp_customize->add_setting(
			$key,
			[
				'default'              => array_key_exists( 'default', $field ) ? $field['default'] : '',
				'type'                 => 'theme_mod',
				'capability'           => 'edit_theme_options',
				// Every field reloads the preview. No field here is worth a
				// second copy of its selector living in JavaScript, drifting
				// out of step with the PHP that actually renders it.
				'transport'            => 'refresh',
				'sanitize_callback'    => static function ( $value ) use ( $field ) {
					return self::sanitize( $value, $field );
				},
				'sanitize_js_callback' => $sanitize_js,
			]
		);

		$args = [
			'label'   => $field['label'],
			'section' => $field['section'],
		];

		if ( ! empty( $field['description'] ) ) {
			$args['description'] = $field['description'];
		}

		if ( ! empty( $field['requires'] ) ) {
			$required = $field['requires'];

			$args['active_callback'] = static function () use ( $wp_customize, $required ) {
				$setting = $wp_customize->get_setting( $required );

				return $setting ? (bool) $setting->value() : true;
			};
		}

		switch ( $field['type'] ) {

			case 'color':
				$wp_customize->add_control(
					new \WP_Customize_Color_Control( $wp_customize, $key, $args )
				);
				break;

			case 'image':
				$wp_customize->add_control(
					new \WP_Customize_Image_Control( $wp_customize, $key, $args )
				);
				break;

			case 'repeater':
				// Customizer-only, so it is not required until we are certain
				// we are in the customizer — the theme is deliberate about what
				// it makes the front end parse.
				require_once HELLO_ANIMATION_THEME_DIR . '/app/core/controls/repeater.control.php';

				$args['fields'] = isset( $field['fields'] ) ? $field['fields'] : [];

				$wp_customize->add_control(
					new Controls\Repeater_Control( $wp_customize, $key, $args )
				);
				break;

			case 'select':
			case 'radio':
				$args['type']    = $field['type'];
				$args['choices'] = $field['choices'];
				$wp_customize->add_control( $key, $args );
				break;

			case 'number':
				$args['type']        = 'number';
				$args['input_attrs'] = isset( $field['input_attrs'] ) ? $field['input_attrs'] : [];
				$wp_customize->add_control( $key, $args );
				break;

			case 'checkbox':
				$args['type'] = 'checkbox';
				$wp_customize->add_control( $key, $args );
				break;

			default:
				$args['type'] = $field['type']; // text | textarea | url
				$wp_customize->add_control( $key, $args );
				break;
		}
	}

	/**
	 * One sanitizer, dispatched on the field's declared type.
	 *
	 * @param mixed $value
	 * @param array $field
	 *
	 * @return mixed
	 */
	public static function sanitize( $value, $field ) {

		$default = array_key_exists( 'default', $field ) ? $field['default'] : '';

		switch ( $field['type'] ) {

			case 'checkbox':
				return (bool) $value;

			case 'url':
			case 'image':
				return esc_url_raw( (string) $value );

			case 'textarea':
				// Not sanitize_textarea_field: the copyright field is printed
				// through hello_animation_kses(), so it is allowed markup.
				return wp_kses_post( (string) $value );

			case 'color':
				if ( '' === $value || null === $value ) {
					return '';
				}

				$color = sanitize_hex_color( $value );

				// sanitize_hex_color() returns null for anything that is not a
				// hex triplet. Falling back to the default beats storing null
				// and printing `color: ;` on the front end.
				return null === $color ? $default : $color;

			case 'select':
			case 'radio':
				$choices = isset( $field['choices'] ) ? $field['choices'] : [];

				return array_key_exists( (string) $value, $choices ) ? (string) $value : $default;

			case 'number':
				$attrs = isset( $field['input_attrs'] ) ? $field['input_attrs'] : [];
				$value = absint( $value );

				if ( isset( $attrs['min'] ) && $value < $attrs['min'] ) {
					$value = (int) $attrs['min'];
				}

				if ( isset( $attrs['max'] ) && $value > $attrs['max'] ) {
					$value = (int) $attrs['max'];
				}

				return $value;

			case 'repeater':
				return self::sanitize_repeater( $value, $field );

			default:
				return sanitize_text_field( (string) $value );
		}
	}

	/**
	 * The control posts JSON; templates loop a PHP array.
	 *
	 * content-offcanvas.php does `foreach ( $gallery as $item )` and reads
	 * $item['image'] and $item['link_url'] straight into attributes, so the
	 * stored value has to be the array, not the JSON — decoding here means
	 * nothing downstream needs to know a hidden input was involved.
	 *
	 * @param mixed $value
	 * @param array $field
	 *
	 * @return array
	 */
	private static function sanitize_repeater( $value, $field ) {
		if ( is_string( $value ) ) {
			$value = json_decode( $value, true );
		}

		if ( ! is_array( $value ) ) {
			return [];
		}

		$row_fields = isset( $field['fields'] ) ? $field['fields'] : [];
		$clean      = [];

		foreach ( $value as $row ) {
			if ( ! is_array( $row ) ) {
				continue;
			}

			$clean_row = [];

			foreach ( $row_fields as $row_key => $row_field ) {
				$row_value = isset( $row[ $row_key ] ) ? $row[ $row_key ] : '';
				$row_type  = isset( $row_field['type'] ) ? $row_field['type'] : 'text';

				$clean_row[ $row_key ] = ( 'url' === $row_type || 'image' === $row_type )
					? esc_url_raw( (string) $row_value )
					: sanitize_text_field( (string) $row_value );
			}

			// A row with no image is an empty slot someone added and never
			// filled; it would render as a broken <img> on the front end.
			if ( empty( $clean_row['image'] ) ) {
				continue;
			}

			$clean[] = $clean_row;
		}

		return $clean;
	}
}
