<?php
/**
 * The header template for the theme
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>"> 
    <?php // No maximum-scale: capping zoom at 2x fails WCAG 1.4.4, and on iOS it stops pinch-zoom working at all. ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <a class="skip-link screen-reader-text"
        href="#content-area"><?php esc_html_e( 'Skip to content', 'hello-animation' ); ?></a>
    <?php
        wp_body_open();

        // Let a Theme Builder header take the location if one matches this
        // request. Without this check both would render and the page would
        // carry two headers.
        if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) {
            get_template_part( 'template-parts/headers/header' );
        }
                  
                  