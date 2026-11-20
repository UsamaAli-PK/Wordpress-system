<?php
/**
 * The main template file
 *
 * @package BaseClassic
 * @since 1.0.0
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main id="primary" class="site-main site-container" style="padding-top: 2rem; padding-bottom: 2rem;">
    <?php
    if (have_posts()) :
        while (have_posts()) :
            the_post();
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> style="margin-bottom: 2.5rem;">
                <header class="entry-header">
                    <?php
                    if (is_singular()) :
                        the_title('<h1 class="entry-title">', '</h1>');
                    else :
                        the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
                    endif;
                    ?>
                </header>

                <?php if (has_post_thumbnail() && !is_singular()) : ?>
                    <div class="post-thumbnail" style="margin-bottom: 1rem;">
                        <a href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail('large'); ?>
                        </a>
                    </div>
                <?php endif; ?>

                <div class="entry-content">
                    <?php
                    if (is_singular()) :
                        the_content();
                    else :
                        the_excerpt();
                    endif;
                    ?>
                </div>
            </article>
            <?php
        endwhile;

        the_posts_pagination([
            'prev_text' => esc_html__('&laquo; Previous', 'base-classic'),
            'next_text' => esc_html__('Next &raquo;', 'base-classic'),
        ]);
    else :
        ?>
        <section class="no-results not-found">
            <h2><?php esc_html_e('Nothing Found', 'base-classic'); ?></h2>
            <p><?php esc_html_e('It looks like nothing was found at this location.', 'base-classic'); ?></p>
        </section>
        <?php
    endif;
    ?>
</main>

<?php
get_footer();
