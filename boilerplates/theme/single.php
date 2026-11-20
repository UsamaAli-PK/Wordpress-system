<?php
/**
 * The template for displaying all single posts
 *
 * Checks if Elementor Theme Builder has claimed the 'single' location.
 *
 * @package BaseClassic
 * @since 1.0.0
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_header();

if (!function_exists('elementor_theme_do_location') || !elementor_theme_do_location('single')) :
?>
    <main id="primary" class="site-main site-container" style="padding-top: 2rem; padding-bottom: 2rem;">
        <?php
        while (have_posts()) :
            the_post();
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header" style="margin-bottom: 1.5rem;">
                    <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
                    <div class="entry-meta" style="font-size: 0.875rem; color: var(--base-secondary-color);">
                        <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date()); ?></time>
                        <span class="byline"> <?php esc_html_e('by', 'base-classic'); ?> <?php the_author_posts_link(); ?></span>
                    </div>
                </header>

                <?php if (has_post_thumbnail()) : ?>
                    <div class="post-thumbnail" style="margin-bottom: 1.5rem;">
                        <?php the_post_thumbnail('full'); ?>
                    </div>
                <?php endif; ?>

                <div class="entry-content">
                    <?php
                    the_content();

                    wp_link_pages([
                        'before' => '<div class="page-links">' . esc_html__('Pages:', 'base-classic'),
                        'after'  => '</div>',
                    ]);
                    ?>
                </div>

                <?php if (comments_open() || get_comments_number()) : ?>
                    <div class="comments-area" style="margin-top: 3rem;">
                        <?php comments_template(); ?>
                    </div>
                <?php endif; ?>
            </article>
            <?php
        endwhile;
        ?>
    </main>
<?php
endif;

get_footer();
