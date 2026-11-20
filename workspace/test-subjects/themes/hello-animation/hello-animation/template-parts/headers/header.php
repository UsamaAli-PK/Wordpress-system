<?php


$header_menu_icon = get_theme_mod( 'header_m_menu_icon', '' ) ? get_theme_mod( 'header_m_menu_icon', '' ) : HELLO_ANIMATION_IMG . '/icons/menu.svg';
$button_text      = get_theme_mod( 'header_button_text', 'Contact Us' );
$button_link      = get_theme_mod( 'ha_button_url', '' );
$ha_button_target = get_theme_mod( 'ha_button_target', '' );
$ha_button_rel    = get_theme_mod( 'ha_button_rel', '' );

/**
 * The value only, not `rel=value`.
 *
 * This used to build the whole attribute pair here and then print it through
 * esc_attr(), which escapes the `=` and the quotes it would need — so the tag
 * came out carrying a literal `rel=nofollow` string rather than an attribute,
 * and the "Rel Type" setting had never once done anything. The attribute name
 * is written in the markup below instead.
 */

?>

<!-- Header area start -->
<header role="banner" class="header__area default-blog-header plr-150">
    <div class="header__inner">
        <div class="header__logo site__logo">
            <?php
	        if ( has_custom_logo() ) {
		        the_custom_logo();
	        } elseif(get_bloginfo( 'name' ))  {
		        /*
		         * <h1> only where the site name *is* the page's heading — the
		         * front page. Everywhere else it was competing with the post or
		         * page title for the document's one top-level heading, which
		         * leaves a screen reader two h1s and no way to tell which
		         * describes the content.
		         */
		        $hello_animation_title_tag = ( is_front_page() || is_home() ) ? 'h1' : 'p';
		        ?>
                <<?php echo esc_html( $hello_animation_title_tag ); ?> class="site-title">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
						<?php echo esc_html( get_bloginfo( 'name' ) ); ?>
					</a>
				</<?php echo esc_html( $hello_animation_title_tag ); ?>>
            <?php
	        }
	        ?>
        </div>
        <div class="header__nav">
            <nav class="main-menu main-menu-js" role="navigation">
                <?php get_template_part( 'template-parts/navigations/nav', 'primary' ); ?>
            </nav>
        </div>
        <div class="header__nav-icon">
            <?php
            /*
             * No URL, no button. It used to print regardless, and the setting
             * defaulted to a placeholder domain, so an untouched install shipped
             * a header button linking somewhere the owner had never heard of.
             */
            if ( $button_link && $button_text ) :

                /*
                 * noopener is added whenever the link opens in a new tab, on top
                 * of whatever the Rel Type setting says.
                 *
                 * Without it the opened page gets a live window.opener handle to
                 * this one and can navigate it somewhere else — reverse
                 * tabnabbing. The setting cannot be relied on to cover it: its
                 * default is "nofollow", which says nothing about opener, and
                 * "Follow" sets no rel at all. Current browsers imply noopener
                 * for target=_blank, but that is a browser default making up for
                 * the markup rather than the markup being right.
                 */
                $hello_animation_rel = array_filter( array( $ha_button_rel ) );

                if ( $ha_button_target ) {
                    $hello_animation_rel[] = 'noopener';
                }

                $hello_animation_rel = implode( ' ', array_unique( $hello_animation_rel ) );
                ?>
                <div class="header-btn">
                    <a class="wcf--theme-btn wc-btn-primary"<?php echo $hello_animation_rel ? ' rel="' . esc_attr( $hello_animation_rel ) . '"' : ''; ?>
                        target="<?php echo $ha_button_target? '_blank': '_self'; ?>"
                        href="<?php echo esc_url( $button_link ); ?>"><?php echo esc_html( $button_text ); ?></a>
                </div>
            <?php endif; ?>
            <button id="hello-animation-openOffcanvas" class="menu-icon-8 info-default-offcanvas"
                aria-controls="hello-animation-offcanvas" aria-expanded="false">
                <img src="<?php echo esc_url( $header_menu_icon ); ?>"
                    alt="<?php echo esc_attr__( 'Offcanvas menu', 'hello-animation' ); ?>">
            </button>
        </div>
    </div>
</header>
<!-- Header area end -->

<?php get_template_part( 'template-parts/headers/content', 'offcanvas' ); ?>