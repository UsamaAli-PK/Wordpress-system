<?php 

/**
 * Are the theme's own webfonts wanted on this site?
 *
 * The theme ships DM Sans and Space Grotesk itself — see assets/scss/fonts.scss
 * and assets/fonts/. It used to build a fonts.googleapis.com request here
 * instead, which handed every visitor's IP address to a third party before the
 * page could render. A theme in the WordPress.org directory is not allowed to do
 * that, and it also put a DNS lookup and a TLS handshake on the critical path
 * for text the browser could have started painting sooner.
 *
 * The opt-out is kept from that version, because the reason for it did not go
 * away with the hosting: neither family covers every script, and a locale these
 * fonts cannot render is better served by the system stack than by a download it
 * gains nothing from.
 *
 * @return bool
 */
function hello_animation_use_theme_fonts() {
	/*
    Translators: If there are characters in your language that are not supported
    by chosen font(s), translate this to 'off'. Do not translate into your own language.
    */
	return 'off' !== _x( 'on', 'Theme fonts: on or off', 'hello-animation' );
}


/**
 * Return the specific value from theme options.
 *
 * This used to check the stored value for truthiness and fall back to $default
 * when it was falsy — which meant a setting saved as false, 0 or '' could never
 * be read back. hello_animation_get_breadcrumbs() reads
 * hello_animation_option('blog_banner_breadcrumb_enable', 1) through here, so
 * the "BreadCrumb Enable" switch was one of the settings that could be turned
 * off in the customizer and have no effect whatsoever.
 *
 * get_theme_mod() already returns $default when the mod is unset, which is the
 * only case the old check was reaching for.
 */
if(!function_exists('hello_animation_option')){
   function hello_animation_option( $key = '', $default = '' ) {
        return get_theme_mod( $key , $default );
   }
}

// WP kses allowed tags
// ----------------------------------------------------------------------------------------
function hello_animation_kses( $raw ) {

	$allowed_tags = array(
		'a'								 => array(
			'class'	 => array(),
			'href'	 => array(),
			'rel'	 => array(),
			'title'	 => array(),
			'target'	 => array(),
      ),
      'option' => array(
         'value'	 => array(),
		
      ),
		'abbr'							 => array(
			'title' => array(),
		),
		'b'								 => array(),
		'blockquote'					 => array(
			'cite' => array(),
		),
		'cite'							 => array(
			'title' => array(),
		),
		'code'							 => array(),
		'del'							 => array(
			'datetime'	 => array(),
			'title'		 => array(),
		),
		'dd'							 => array(),
		'div'							 => array(
			'class'	 => array(),
			'title'	 => array(),
			'style'	 => array(),
		),
		'dl'							 => array(),
		'dt'							 => array(),
		'em'							 => array(),
		'h1'							 => array(),
		'h2'							 => array(),
		'h3'							 => array(),
		'h4'							 => array(),
		'h5'							 => array(),
		'h6'							 => array(),
		'i'								 => array(
			'class' => array(),
		),
		'img'							 => array(
			'alt'	 => array(),
			'class'	 => array(),
			'height' => array(),
			'src'	 => array(),
			'width'	 => array(),
		),
		'li'							 => array(
			'class' => array(),
		),
		'ol'							 => array(
			'class' => array(),
		),
		'p'								 => array(
			'class' => array(),
		),
		'q'								 => array(
			'cite'	 => array(),
			'title'	 => array(),
		),
		'span'							 => array(
			'class'	 => array(),
			'title'	 => array(),
			'style'	 => array(),
		),
		'iframe'						 => array(
			'width'			 => array(),
			'height'		 => array(),
			'scrolling'		 => array(),
			'frameborder'	 => array(),
			'allow'			 => array(),
			'src'			 => array(),
		),
		'strike'						 => array(),
		'br'							 => array(),
		'strong'						 => array(),
		/*
		 * There were four more entries here — data-wow-duration, data-wow-delay,
		 * data-wallpaper-options and data-stellar-background-ratio — sitting in
		 * the tag position of this array rather than in any element's attribute
		 * list. wp_kses() reads the keys as element names, so all they did was
		 * permit four elements that do not exist, and none of them allowed the
		 * attribute it was named after on anything. The WOW.js and Stellar
		 * markup they were added for is no longer emitted by the theme.
		 */
		'ul'							 => array(
			'class' => array(),
		),
	);

	if ( function_exists( 'wp_kses' ) ) { // WP is here
		$allowed = wp_kses( $raw, $allowed_tags );
	} else {
		$allowed = $raw;
	}

	return $allowed;
}




