<?php
/**
 * The template for displaying the header
 *
 * Checks if Elementor Theme Builder has claimed the 'header' location.
 * If true, Elementor outputs its template. Otherwise, renders semantic native fallback.
 *
 * @package BaseClassic
 * @since 1.0.0
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#content">
    <?php esc_html_e('Skip to content', 'base-classic'); ?>
</a>

<?php
if (!function_exists('elementor_theme_do_location') || !elementor_theme_do_location('header')) :
?>
    <header id="masthead" class="site-header">
        <div class="site-container site-header-inner" style="display: flex; justify-content: space-between; align-items: center; padding-top: 1.5rem; padding-bottom: 1.5rem;">
            <div class="site-branding">
                <?php if (has_custom_logo()) : ?>
                    <?php the_custom_logo(); ?>
                <?php else : ?>
                    <h1 class="site-title" style="margin: 0; font-size: 1.5rem;">
                        <a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a>
                    </h1>
                <?php endif; ?>
            </div>

            <nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e('Primary Menu', 'base-classic'); ?>">
                <?php
                wp_nav_menu([
                    'theme_location' => 'primary',
                    'menu_id'        => 'primary-menu',
                    'container'      => false,
                    'fallback_cb'    => false,
                ]);
                ?>
            </nav>
        </div>
    </header>
<?php endif; ?>

<div id="content" class="site-content">
