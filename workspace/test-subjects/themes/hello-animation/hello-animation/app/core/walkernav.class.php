<?php
namespace Hello_Animation\Core;
use Walker_Nav_Menu;


class Hello_Animation_Walker_Nav extends Walker_Nav_Menu
{
    /**
     * Prepended to every menu item's DOM id.
     *
     * The theme prints the same menu twice on every page that renders its own
     * header — once in the desktop nav, once inside the offcanvas panel — and
     * the two template parts differ only in menu_class. Both were stamping
     * id="menu-item-{$item->ID}" on their items, so every one of those ids
     * appeared twice in the document. Invalid HTML, and it makes any fragment
     * link or getElementById() a coin toss between the visible nav and the one
     * hidden in the panel.
     *
     * The offcanvas copy passes a prefix; the desktop nav keeps the ids it has
     * always had, so anything already targeting them still works.
     *
     * @var string
     */
    public $id_prefix = '';

    /**
     * @param string $id_prefix Prepended to each item's DOM id.
     */
    public function __construct( $id_prefix = '' ) {
        $this->id_prefix = $id_prefix;
    }

    /**
     * Starts the list before the elements are added.
     *
     * @see Walker::start_lvl()
     *
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param int    $depth  Depth of menu item. Used for padding.
     * @param array  $args   An array of arguments. @see wp_nav_menu()
     */
    public function start_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\" dp-menu\">\n";
    }
    /**
     * Ends the list of after the elements are added.
     *
     * @see Walker::end_lvl()
     *
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param int    $depth  Depth of menu item. Used for padding.
     * @param array  $args   An array of arguments. @see wp_nav_menu()
     */
    public function end_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }
    /**
     * Start the element output.
     *
     * @see Walker::start_el()
     *
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param object $item   Menu item data object.
     * @param int    $depth  Depth of menu item. Used for padding.
     * @param array  $args   An array of arguments. @see wp_nav_menu()
     * @param int    $id     Current item ID.
     */
    public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        // Core marks an item menu-item-has-children whenever it has children in
        // the menu, even when the 'depth' argument stops them being printed. An
        // item on the last printed level then got a submenu arrow and toggle
        // that opened nothing.
        $max_depth = isset( $args->depth ) ? (int) $args->depth : 0;
        if ( $max_depth > 0 && $depth + 1 >= $max_depth ) {
            $classes = array_diff( $classes, array( 'menu-item-has-children' ) );
        }
        /**
         * Filter the CSS class(es) applied to a menu item's list item element.
         *
         * @since 3.0.0
         * @since 4.1.0 The `$depth` parameter was added.
         *
         * @param array  $classes The CSS classes that are applied to the menu item's `<li>` element.
         * @param object $item    The current menu item.
         * @param array  $args    An array of {@see wp_nav_menu()} arguments.
         * @param int    $depth   Depth of menu item. Used for padding.
         */
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
        // New
        $class_names .= ' nav-item';
        
        if (in_array('menu-item-has-children', $classes)) {
            if($depth == 0){
                $class_names .= " dropdown";
            }else{
                $class_names .= " dropdown-submenu ";
            }
           
        }
        if (in_array('current-menu-item', $classes)) {
            $class_names .= ' active';
        }
        //
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
        // print_r($class_names);
        /**
         * Filter the ID applied to a menu item's list item element.
         *
         * @since 3.0.1
         * @since 4.1.0 The `$depth` parameter was added.
         *
         * @param string $menu_id The ID that is applied to the menu item's `<li>` element.
         * @param object $item    The current menu item.
         * @param array  $args    An array of {@see wp_nav_menu()} arguments.
         * @param int    $depth   Depth of menu item. Used for padding.
         */
        $id = apply_filters( 'nav_menu_item_id', $this->id_prefix . 'menu-item-'. $item->ID, $item, $args, $depth );
        $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';
        // New
        // if ($depth === 0) {
        //     $output .= $indent . '<li' . $id . $class_names .'>';
        // }
        //
        $output .= $indent . '<li' . $id . $class_names .'>';
        $atts = array();
        $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
        $atts['target'] = ! empty( $item->target )     ? $item->target     : '';
        $atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
        $atts['href']   = ! empty( $item->url )        ? $item->url        : '';
        // New
        if ($depth === 0) {
            $atts['class'] = 'nav-link';
        }
        if ($depth === 0 && in_array('menu-item-has-children', $classes)) {
            $atts['class']       .= ' has-child dropdown__toggle';
            $atts['data-toggle']  = 'dropdown';
        }
        if ($depth > 0) {
            $manual_class = array_values($classes)[0] .' '. 'dropdown_items';
            $atts ['class']= $manual_class;
            
            if(in_array('menu-item-has-children', $classes)){
                $atts['class']       .= ' has-child dropdown__toggle';
            }
        }
        if (is_array($item->classes) && in_array('current-menu-item', $item->classes)) {
            $atts['class'] .= ' active';
        }
        // print_r($item);
        //
        /**
         * Filter the HTML attributes applied to a menu item's anchor element.
         *
         * @since 3.6.0
         * @since 4.1.0 The `$depth` parameter was added.
         *
         * @param array $atts {
         *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
         *
         *     @type string $title  Title attribute.
         *     @type string $target Target attribute.
         *     @type string $rel    The rel attribute.
         *     @type string $href   The href attribute.
         * }
         * @param object $item  The current menu item.
         * @param array  $args  An array of {@see wp_nav_menu()} arguments.
         * @param int    $depth Depth of menu item. Used for padding.
         */
        $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );
        
        $attributes = '';
        foreach ( $atts as $attr => $value ) {
            if ( ! empty( $value ) ) {
                $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }
        $item_output = $args->before;
        // New
        /*
        if ($depth === 0 && in_array('menu-item-has-children', $classes)) {
            $item_output .= '<a class="nav-link dropdown-toggle"' . $attributes .'data-toggle="dropdown">';
        } elseif ($depth === 0) {
            $item_output .= '<a class="nav-link"' . $attributes .'>';
        } else {
            $item_output .= '<a class="dropdown-item"' . $attributes .'>';
        }
        */
        //
        $item_output .= '<a'. $attributes .'>';
        /** This filter is documented in wp-includes/post-template.php */
        $item_output .= $args->link_before . '<span class="ha-nav-link">' . apply_filters( 'the_title', $item->title, $item->ID ) . '</span>'.$args->link_after;

        if ( in_array('menu-item-has-children', $classes) ) {
            $item_output .= '<span tabindex="0" class="nav-direction-icon" data-icon="+"></span>';
        }
       
        $item_output .= '</a>';
        $item_output .= $args->after;
        /**
         * Filter a menu item's starting output.
         *
         * The menu item's starting output only includes `$args->before`, the opening `<a>`,
         * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
         * no filter for modifying the opening and closing `<li>` for a menu item.
         *
         * @since 3.0.0
         *
         * @param string $item_output The menu item's starting HTML output.
         * @param object $item        Menu item data object.
         * @param int    $depth       Depth of menu item. Used for padding.
         * @param array  $args        An array of {@see wp_nav_menu()} arguments.
         */
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
    /**
     * Ends the element output, if needed.
     *
     * @see Walker::end_el()
     *
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param object $item   Page data object. Not used.
     * @param int    $depth  Depth of page. Not Used.
     * @param array  $args   An array of arguments. @see wp_nav_menu()
     */
    public function end_el( &$output, $item, $depth = 0, $args = array() ) {
        /*
         * Every depth, not just the top one. Closing only at depth 0 left every
         * item inside a submenu unclosed — the parser recovers, because the next
         * <li> or the enclosing </ul> implies the close, so it looked fine and
         * validated as invalid.
         *
         * Safe to change: the submenu <ul> is emitted between start_el and
         * end_el, so a submenu is still the anchor's nextElementSibling, which
         * is what script.js walks to find it.
         */
        $output .= "</li>\n";
    }

    	/**
	 * Menu Fallback
	 * =============
	 * If this function is assigned to the wp_nav_menu's fallback_cb variable
	 * and a manu has not been assigned to the theme location in the WordPress
	 * menu manager the function with display nothing to a non-logged in user,
	 * and will add a link to the WordPress menu manager if logged in as an admin.
	 *
	 * @param array $args passed from the wp_nav_menu function.
	 *
	 */
	public static function fallback( $args ) {

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Read the five keys this uses rather than extract()ing the array.
		// extract() creates whatever variables the caller happened to pass, so
		// the function's real inputs are invisible from its own body — and it
		// is one of the patterns the theme review flags on sight.
		$container       = isset( $args['container'] ) ? $args['container'] : '';
		$container_id    = isset( $args['container_id'] ) ? $args['container_id'] : '';
		$container_class = isset( $args['container_class'] ) ? $args['container_class'] : '';
		$menu_id         = isset( $args['menu_id'] ) ? $args['menu_id'] : '';
		$menu_class      = isset( $args['menu_class'] ) ? $args['menu_class'] : '';

		$fb_output = '';

		if ( $container ) {
			$fb_output .= '<' . tag_escape( $container );

			if ( $container_id ) {
				$fb_output .= ' id="' . esc_attr( $container_id ) . '"';
			}

			if ( $container_class ) {
				$fb_output .= ' class="menu-fallback ' . esc_attr( $container_class ) . '"';
			}

			$fb_output .= '>';
		}

		$fb_output .= '<ul';

		if ( $menu_id ) {
			$fb_output .= ' id="' . esc_attr( $menu_id ) . '"';
		}

		if ( $menu_class ) {
			$fb_output .= ' class="' . esc_attr( $menu_class ) . '"';
		}

		$fb_output .= '>';
		$fb_output .= '<li><a href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '">'
			. esc_html__( 'Add a menu', 'hello-animation' ) . '</a></li>';
		$fb_output .= '</ul>';

		if ( $container ) {
			$fb_output .= '</' . tag_escape( $container ) . '>';
		}

		echo hello_animation_kses( $fb_output );
	}

}