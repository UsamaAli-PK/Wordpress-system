<?php
namespace Hello_Animation;

/**
 *
 * This theme uses PSR-4 and OOP logic instead of procedural coding
 * Every function, hook and action is properly divided and organized inside related folders and files
 * 
 *
 * @package Hello_Animation
 */
final class Init
{
	/**
	 * Store all the classes inside an array
	 * @return array Full list of classes
	 */
	public static function get_services()
	{
		$services = [

			Core\Blog::class,
			Core\Blog_Widgets::class,
			Core\Theme_Setup::class,
			// Before Enqueue: it registers the Elementor theme locations that
			// Enqueue's own gate then asks about.
			Core\Assets::class,
			Core\Enqueue::class,
			// After Enqueue: Dynamic_CSS attaches its inline styles to the
			// stylesheet handle Enqueue registers.
			Core\Customizer::class,
			Core\Dynamic_CSS::class,

		];

		// Only loaded in wp-admin — see the note in loader.php. Asking
		// class_exists() rather than is_admin() a second time keeps the two
		// files from drifting into disagreeing about the condition.
		//
		// Each class is asked for by name. The gate used to test only one of
		// them and enrol the other on the strength of that answer, which meant
		// removing the first would have silently taken the second with it — a
		// notice that stops appearing and never errors is not a failure anyone
		// notices.
		//
		// Registering Plugin_Manager is not the same as loading TGMPA: all this
		// buys is a listener on `hello_animation_tgmpa_register` and a callback
		// on init:0. The library itself is only parsed if that callback decides
		// this request needs it.
		if ( class_exists( Core\Plugin_Manager::class ) ) {
			$services[] = Core\Plugin_Manager::class;
		}

		if ( class_exists( Core\Admin_Notice::class ) ) {
			$services[] = Core\Admin_Notice::class;
		}

		return $services;
	}

	/**
	 * Loop through the classes, initialize them, and call the register() method if it exists
	 * @return
	 */
	public static function register_services()
	{
		foreach ( self::get_services() as $class ) {
			$service = self::instantiate( $class );
			if ( method_exists( $service, 'register') ) {
				$service->register();
			}
		}
	}

	/**
	 * Initialize the class
	 * @param  class $class 		class from the services array
	 * @return class instance 		new instance of the class
	 */
	private static function instantiate( $class )
	{
		return new $class();
	}

}
