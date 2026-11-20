<?php
/**
 * The template for displaying the footer
 *
 * Checks if Elementor Theme Builder has claimed the 'footer' location.
 * If true, Elementor outputs its template. Otherwise, renders semantic native fallback.
 *
 * @package BaseClassic
 * @since 1.0.0
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}
?>
</div><!-- #content -->

<?php
if (!function_exists('elementor_theme_do_location') || !elementor_theme_do_location('footer')) :
?>
    <footer id="colophon" class="site-footer" style="background-color: var(--base-surface-color); border-top: 1px solid var(--base-border-color); padding: 2.5rem 0; margin-top: 3rem;">
        <div class="site-container site-footer-inner" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div class="site-info" style="font-size: 0.875rem; color: var(--base-secondary-color);">
                <p style="margin: 0;">
                    &copy; <?php echo esc_html(gmdate('Y')); ?> <?php bloginfo('name'); ?>. <?php esc_html_e('All rights reserved.', 'base-classic'); ?>
                </p>
            </div>

            <nav class="footer-navigation" aria-label="<?php esc_attr_e('Footer Menu', 'base-classic'); ?>">
                <?php
                wp_nav_menu([
                    'theme_location' => 'footer',
                    'menu_id'        => 'footer-menu',
                    'container'      => false,
                    'depth'          => 1,
                    'fallback_cb'    => false,
                ]);
                ?>
            </nav>
        </div>
    </footer>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
