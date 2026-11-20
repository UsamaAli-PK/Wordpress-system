---
topic: Custom Post Types, Taxonomies, and Shortcodes
source_url: https://developer.wordpress.org/plugins/post-types/
last_synced: 2026-11-20
---

# Custom Post Types, Taxonomies, and Shortcodes

## 1. Registering Custom Post Types (`register_post_type`)

Custom Post Types (CPTs) model structured business data (portfolios, testimonials, products).

```php
declare(strict_types=1);

namespace Agency\Companion\PostTypes;

final class PortfolioCPT {
    public const SLUG = 'agency_portfolio';

    public static function register(): void {
        $labels = [
            'name'                  => _x( 'Portfolios', 'Post type general name', 'agency-companion' ),
            'singular_name'         => _x( 'Portfolio', 'Post type singular name', 'agency-companion' ),
            'menu_name'             => _x( 'Portfolios', 'Admin Menu text', 'agency-companion' ),
            'add_new'               => __( 'Add New', 'agency-companion' ),
            'add_new_item'          => __( 'Add New Portfolio Project', 'agency-companion' ),
            'edit_item'             => __( 'Edit Portfolio', 'agency-companion' ),
            'new_item'              => __( 'New Portfolio', 'agency-companion' ),
            'view_item'             => __( 'View Portfolio', 'agency-companion' ),
            'search_items'          => __( 'Search Portfolios', 'agency-companion' ),
            'not_found'             => __( 'No portfolios found.', 'agency-companion' ),
            'not_found_in_trash'    => __( 'No portfolios found in Trash.', 'agency-companion' ),
            'all_items'             => __( 'All Portfolios', 'agency-companion' ),
        ];

        $args = [
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => [ 'slug' => 'portfolio', 'with_front' => false ],
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 20,
            'menu_icon'          => 'dashicons-portfolio',
            'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ],
            'show_in_rest'       => true, // Required for Gutenberg & REST API support
        ];

        register_post_type( self::SLUG, $args );
    }
}
```

---

## 2. Registering Custom Taxonomies (`register_taxonomy`)

Taxonomies group and categorize CPT items.

```php
namespace Agency\Companion\Taxonomies;

use Agency\Companion\PostTypes\PortfolioCPT;

final class ProjectTypeTaxonomy {
    public const SLUG = 'project_type';

    public static function register(): void {
        $labels = [
            'name'              => _x( 'Project Types', 'taxonomy general name', 'agency-companion' ),
            'singular_name'     => _x( 'Project Type', 'taxonomy singular name', 'agency-companion' ),
            'search_items'      => __( 'Search Project Types', 'agency-companion' ),
            'all_items'         => __( 'All Project Types', 'agency-companion' ),
            'edit_item'         => __( 'Edit Project Type', 'agency-companion' ),
            'update_item'       => __( 'Update Project Type', 'agency-companion' ),
            'add_new_item'      => __( 'Add New Project Type', 'agency-companion' ),
            'new_item_name'     => __( 'New Project Type Name', 'agency-companion' ),
            'menu_name'         => __( 'Project Types', 'agency-companion' ),
        ];

        $args = [
            'hierarchical'      => true, // true = like categories, false = like tags
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => [ 'slug' => 'project-type' ],
            'show_in_rest'      => true, // Enables taxonomy panel in block editor
        ];

        register_taxonomy( self::SLUG, [ PortfolioCPT::SLUG ], $args );
    }
}
```

---

## 3. Rewrite Rule Flushing: The Safe Pattern

> **Anti-Pattern Warning:** Never call `flush_rewrite_rules()` on `init` or on every page load. It runs expensive database queries and rebuilds the URL rewrite table on every request.

### The Correct Plugin Activation Flow

```php
namespace Agency\Companion;

function activate_plugin(): void {
    // 1. Register CPTs and taxonomies directly in activation callback
    PostTypes\PortfolioCPT::register();
    Taxonomies\ProjectTypeTaxonomy::register();

    // 2. Flush rewrite rules once into the database
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, __NAMESPACE__ . '\\activate_plugin' );

function deactivate_plugin(): void {
    // Unregister post type if necessary, then clean rewrite rules
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, __NAMESPACE__ . '\\deactivate_plugin' );
```

---

## 4. Modern Shortcode Architecture

Shortcodes must **never echo output directly**. They must return a string. Use PHP output buffering (`ob_start()` / `ob_get_clean()`) to keep templates clean.

```php
namespace Agency\Companion\Shortcodes;

use WP_Query;

final class PortfolioGridShortcode {
    public const TAG = 'agency_portfolio_grid';

    public static function register(): void {
        add_shortcode( self::TAG, [ self::class, 'render' ] );
    }

    /**
     * @param array<string, mixed>|string $atts Shortcode attributes.
     */
    public static function render( array|string $atts = [] ): string {
        // Standardize and sanitize attributes
        $attributes = shortcode_atts(
            [
                'count'    => 6,
                'category' => '',
            ],
            $atts,
            self::TAG
        );

        $limit = max( 1, (int) $attributes['count'] );
        $tax_query = [];

        if ( ! empty( $attributes['category'] ) ) {
            $tax_query[] = [
                'taxonomy' => 'project_type',
                'field'    => 'slug',
                'terms'    => sanitize_title( (string) $attributes['category'] ),
            ];
        }

        $query = new WP_Query( [
            'post_type'      => 'agency_portfolio',
            'posts_per_page' => $limit,
            'post_status'    => 'publish',
            'tax_query'      => $tax_query,
        ] );

        if ( ! $query->have_posts() ) {
            return '<p class="agency-empty">' . esc_html__( 'No projects found.', 'agency-companion' ) . '</p>';
        }

        ob_start();
        ?>
        <div class="agency-portfolio-grid">
            <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class( 'portfolio-card' ); ?>>
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="portfolio-thumbnail">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail( 'medium_large' ); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    <h3 class="portfolio-title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h3>
                </article>
            <?php endwhile; ?>
        </div>
        <?php
        wp_reset_postdata();

        return (string) ob_get_clean();
    }
}
```
