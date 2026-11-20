---
topic: Elementor Theme Locations API
source_url: https://developers.elementor.com/docs/themes/
last_synced: 2026-11-20
---

# Elementor Theme Locations API

## 1. Overview & Core Concept

Elementor Pro 2.0+ introduced the **Theme Builder**, transitioning Elementor from a single-page builder to a full site-builder. With the **Theme Locations API**, classic themes declare support for dynamic template replacements:

- **Header:** Replaces `header.php` / site masthead.
- **Footer:** Replaces `footer.php` / site colophon.
- **Single:** Replaces `single.php`, `singular.php`, `page.php`, and `404.php`.
- **Archive:** Replaces `archive.php`, `category.php`, `author.php`, and `search.php`.

When an Elementor Pro user publishes a Header template with a display condition (e.g., "Entire Site"), Elementor intercepts the theme location and outputs the custom layout. If no template condition matches, the theme's native PHP fallback executes seamlessly.

---

## 2. Registering Locations in `functions.php`

Hook into `elementor/theme/register_locations` to expose theme slots to Elementor's Theme Builder.

```php
declare(strict_types=1);

namespace Agency\Theme\Elementor;

use ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager;

/**
 * Register core and custom Elementor theme locations.
 */
function register_elementor_locations( Locations_Manager $elementor_theme_manager ): void {
    // 1. Register all built-in core locations (header, footer, single, archive)
    $elementor_theme_manager->register_all_core_location();

    // 2. Alternatively, register specific locations individually:
    // $elementor_theme_manager->register_location( 'header' );
    // $elementor_theme_manager->register_location( 'footer' );

    // 3. Register custom bespoke locations (e.g., sub-header bar, before-footer banner)
    $elementor_theme_manager->register_location(
        'before_footer_callout',
        [
            'label'           => __( 'Before Footer Callout', 'agency-theme' ),
            'multiple'        => false,
            'edit_in_content' => false,
        ]
    );
}
add_action( 'elementor/theme/register_locations', __NAMESPACE__ . '\\register_elementor_locations' );
```

---

## 3. Integrating Templates with Native Fallbacks

Themes invoke `elementor_theme_do_location( string $location_name )`. This function returns:
- `true` if an active Elementor template was assigned and rendered.
- `false` if no Elementor template matches the current request (or Elementor Pro is inactive).

### Header Integration (`header.php`)

```php
<?php
/**
 * The template for displaying the header.
 */
declare(strict_types=1);
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php
// Elementor Header location with clean native PHP fallback
if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) {
    get_template_part( 'template-parts/header/site-header' );
}
```

### Footer Integration (`footer.php`)

```php
<?php
/**
 * The template for displaying the footer.
 */
declare(strict_types=1);

// Custom pre-footer hook location
if ( function_exists( 'elementor_theme_do_location' ) ) {
    elementor_theme_do_location( 'before_footer_callout' );
}

// Elementor Footer location with native PHP fallback
if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) {
    get_template_part( 'template-parts/footer/site-footer' );
}

wp_footer();
?>
</body>
</html>
```

### Singular Posts & Pages (`singular.php` or `single.php`)

```php
<?php
/**
 * The template for displaying single posts, pages, or CPTs.
 */
declare(strict_types=1);

get_header();

if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) {
    ?>
    <main id="primary" class="site-main container">
        <?php
        while ( have_posts() ) :
            the_post();
            get_template_part( 'template-parts/content/content', get_post_type() );
        endwhile;
        ?>
    </main>
    <?php
}

get_footer();
```

### Archives & Taxonomy Listings (`archive.php`)

```php
<?php
/**
 * The template for displaying archive pages.
 */
declare(strict_types=1);

get_header();

if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'archive' ) ) {
    ?>
    <main id="primary" class="site-main container">
        <header class="page-header">
            <?php
            the_archive_title( '<h1 class="page-title">', '</h1>' );
            the_archive_description( '<div class="archive-description">', '</div>' );
            ?>
        </header>

        <div class="archive-posts-grid">
            <?php
            if ( have_posts() ) :
                while ( have_posts() ) :
                    the_post();
                    get_template_part( 'template-parts/content/content', 'archive' );
                endwhile;
                the_posts_navigation();
            else :
                get_template_part( 'template-parts/content/content', 'none' );
            endif;
            ?>
        </div>
    </main>
    <?php
}

get_footer();
```
