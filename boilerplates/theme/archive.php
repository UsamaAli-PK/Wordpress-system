<?php
/**
 * The template for displaying archive pages
 *
 * Checks if Elementor Theme Builder has claimed the 'archive' location.
 *
 * @package BaseClassic
 * @since 1.0.0
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_header();

if (!function_exists('elementor_theme_do_location') || !elementor_theme_do_location('archive')) :
?>
    <main id="primary" class="site-main site-container" style="padding-top: 2rem; padding-bottom: 2rem;">
        <header class="page-header" style="margin-bottom: 2.5rem; border-bottom: 1px solid var(--base-border-color); padding-bottom: 1rem;">
            <?php
            the_archive_title('<h1 class="page-title">', '</h1>');
            the_archive_description('<div class="archive-description" style="color: var(--base-secondary-color); margin-top: 0.5rem;">', '</div>');
            ?>
        </header>

        <?php
        if (have_posts()) :
            while (have_posts()) :
                the_post();
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> style="margin-bottom: 2rem;">
                    <header class="entry-header">
                        <?php the_title('<h2 class="entry-title" style="margin-bottom: 0.5rem;"><a href="' . esc_url(get_permalink()) . '">', '</a></h2>'); ?>
                    </header>
                    <div class="entry-summary">
                        <?php the_excerpt(); ?>
                    </div>
                </article>
                <?php
            endwhile;

            the_posts_pagination();
        else :
            ?>
            <p><?php esc_html_e('No posts found in this archive.', 'base-classic'); ?></p>
            <?php
        endif;
        ?>
    </main>
<?php
endif;

get_footer();
