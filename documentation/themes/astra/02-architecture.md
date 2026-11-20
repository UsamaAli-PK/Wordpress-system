---
topic: Astra Architecture & Bootstrap
last_synced: 2026-11-20
source_subject: /root/Desktop/learn/BAse/workspace/test-subjects/themes/astra/astra
---

# Astra Architecture & Bootstrap

## 1. Load graph (bird's eye)

`style.css` identifies the theme; everything functional is loaded procedurally through `functions.php`. There is no autoloader — Astra loads **~70 `require_once` statements** in hand-ordered sequence. Load order *is* the architecture. Order matters because many classes query others at file-scope on load.

```
functions.php
├── Constants (ASTRA_THEME_VERSION, ASTRA_THEME_DIR, ASTRA_THEME_URI, ASTRA_EXT_MIN_VER)
├── w-org-version.php shim (WordPress.org-specific adjustments)
│
├── Core bootstrap layer
│   ├── inc/core/class-astra-theme-options.php     # Options getter/setter + defaults
│   ├── inc/core/class-theme-strings.php           # i18n-ready strings
│   ├── inc/core/common-functions.php              # astra_get_option(), helpers
│   └── inc/core/class-astra-icons.php             # SVG icon registry
│
├── Update / migration layer
│   ├── inc/theme-update/astra-update-functions.php
│   └── inc/theme-update/class-astra-theme-background-updater.php
│
├── Typography pipeline
│   ├── inc/customizer/class-astra-font-families.php
│   ├── inc/customizer/class-astra-fonts-data.php  (admin only)
│   ├── inc/lib/webfont/class-astra-webfont-loader.php
│   └── inc/customizer/class-astra-fonts.php
│
├── Dynamic-CSS modules (each a separate file, each registered at load)
│   ├── inc/dynamic-css/custom-menu-old-header.php
│   ├── inc/dynamic-css/container-layouts.php
│   ├── inc/dynamic-css/astra-icons.php
│   ├── inc/dynamic-css/block-editor-compatibility.php
│   ├── inc/dynamic-css/inline-on-mobile.php
│   ├── inc/dynamic-css/content-background.php
│   ├── inc/dynamic-css/dark-mode.php
│   └── …11 more (see inc/dynamic-css/ listing in `04-feature-index.md`)
│
├── Runtime services
│   ├── inc/core/class-astra-walker-page.php       # wp_list_pages walker
│   ├── inc/core/class-astra-enqueue-scripts.php   # Frontend asset enqueue
│   ├── inc/core/class-gutenberg-editor-css.php    # Block editor style sync
│   ├── inc/core/class-astra-wp-editor-css.php     # Classic editor style sync
│   ├── inc/core/class-astra-command-palette.php   # WP 6.3+ Command Palette
│   ├── inc/class-astra-dynamic-css.php            # The dynamic-CSS compositor
│   └── inc/class-astra-global-palette.php         # Global colour palette
│
├── Notification layer (only loads outside starter-templates version collisions)
│   ├── inc/lib/class-astra-nps-notice.php
│   └── inc/lib/class-astra-nps-survey.php
│
├── Markup helpers & templating
│   ├── inc/core/class-astra-attr.php              # Smart attribute builder
│   ├── inc/template-tags.php                      # Public template functions
│   ├── inc/widgets.php                            # Sidebar/widget registration
│   ├── inc/core/theme-hooks.php                   # astra_*() hook declarations
│   ├── inc/admin-functions.php
│   ├── inc/class-astra-memory-limit-notice.php
│   ├── inc/core/sidebar-manager.php
│   ├── inc/markup-extras.php                      # The theme's markup toolkit
│   ├── inc/extras.php                             # Misc helpers
│   ├── inc/blog/blog-config.php
│   ├── inc/blog/blog.php
│   ├── inc/blog/single-blog.php
│   ├── inc/template-parts.php
│   ├── inc/class-astra-loop.php
│   └── inc/class-astra-mobile-header.php
│
├── Setup / admin wiring
│   ├── inc/class-astra-after-setup-theme.php      # after_setup_theme + theme supports
│   ├── inc/core/class-astra-admin-helper.php
│   ├── inc/schema/class-astra-schema.php
│   ├── admin/includes/class-astra-learn.php       # Learn / onboarding module
│   ├── admin/includes/class-astra-api-init.php    # REST controller
│   │
│   └── IF is_admin():
│       ├── inc/core/class-astra-admin-settings.php
│       ├── admin/class-astra-admin-loader.php
│       └── inc/lib/astra-notices/class-bsf-admin-notices.php
│
├── admin/class-astra-bsf-analytics.php            # BSF Analytics telemetry (always loads)
│
├── Metaboxes
│   ├── inc/metabox/class-astra-meta-boxes.php
│   ├── inc/metabox/class-astra-meta-box-operations.php
│   └── inc/metabox/class-astra-elementor-editor-settings.php
│
├── Customizer orchestrator
│   └── inc/customizer/class-astra-customizer.php
│
├── Modules (first-party feature flags)
│   ├── inc/modules/posts-structures/class-astra-post-structures.php
│   └── inc/modules/related-posts/class-astra-related-posts.php
│
├── Compatibility bridges (20+ plugins)
│   ├── inc/compatibility/class-astra-gutenberg.php
│   ├── inc/compatibility/class-astra-jetpack.php
│   ├── inc/compatibility/woocommerce/class-astra-woocommerce.php
│   ├── inc/compatibility/edd/class-astra-edd.php
│   ├── inc/compatibility/lifterlms/class-astra-lifterlms.php
│   ├── inc/compatibility/learndash/class-astra-learndash.php
│   ├── inc/compatibility/class-astra-beaver-builder.php
│   ├── inc/compatibility/class-astra-bb-ultimate-addon.php
│   ├── inc/compatibility/class-astra-contact-form-7.php
│   ├── inc/compatibility/class-astra-visual-composer.php
│   ├── inc/compatibility/class-astra-site-origin.php
│   ├── inc/compatibility/class-astra-gravity-forms.php
│   ├── inc/compatibility/class-astra-bne-flyout.php
│   ├── inc/compatibility/class-astra-ubermeu.php
│   ├── inc/compatibility/class-astra-divi-builder.php
│   ├── inc/compatibility/class-astra-amp.php
│   ├── inc/compatibility/class-astra-yoast-seo.php
│   ├── inc/compatibility/surecart/class-astra-surecart.php
│   ├── inc/compatibility/class-astra-starter-content.php
│   └── inc/compatibility/class-astra-buddypress.php
│
├── Addons (bundled free features — feature flags)
│   ├── inc/addons/transparent-header/
│   ├── inc/addons/breadcrumbs/
│   ├── inc/addons/scroll-to-top/
│   └── inc/addons/heading-colors/
│
├── inc/builder/class-astra-builder-loader.php     # Header/Footer Builder entry
│
└── Conditional bridges (PHP version gates)
    ├── IF PHP ≥ 5.4: Elementor, Elementor Pro, Web Stories
    └── IF PHP ≥ 5.3: Beaver Themer
```

## 2. Singleton pattern

Every major class follows the same shape:

```php
class Astra_Foo {
    private static $instance;
    public static function get_instance() {
        if ( ! isset( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    private function __construct() { /* register hooks */ }
}
Astra_Foo::get_instance();
```

A reader can treat every class's constructor as the registration manifest for that module.

## 3. Header/Footer Builder (`inc/builder/`)

This is Astra's flagship first-party builder — a lighter, theme-native alternative to Elementor Theme Builder.

```
inc/builder/
├── class-astra-builder-loader.php         # Loader + orchestrator
├── type/
│   ├── base/                              # Shared base classes for all builder elements
│   ├── assets/                            # Builder-specific assets
│   ├── header/                            # Header-element modules
│   │   ├── above-header/
│   │   ├── primary-header/
│   │   ├── below-header/
│   │   ├── site-identity/
│   │   ├── menu/
│   │   ├── mobile-menu/
│   │   ├── mobile-trigger/
│   │   ├── off-canvas/
│   │   ├── account/
│   │   ├── button/
│   │   ├── edd-cart/
│   │   ├── woo-cart/
│   │   ├── search/
│   │   ├── widget/
│   │   ├── html/
│   │   └── social-icon/
│   └── footer/
│       ├── above-footer/
│       ├── primary-footer/
│       ├── below-footer/
│       ├── copyright/
│       ├── widget/
│       ├── menu/
│       ├── button/
│       ├── social-icon/
│       └── html/
├── markup/                                # Template rendering
└── controllers/                           # Builder admin controllers
```

Each builder element is a self-contained unit with:
- a class extending the base element
- customizer config files
- dynamic-css contributors
- front-end rendered template under `template-parts/header/` and `template-parts/footer/`

## 4. Dynamic CSS architecture

Not `<link>`-based stylesheets. Astra composes CSS strings in PHP at request time:

1. Each dynamic-css module is a file exposing `astra_dynamic_css` filter contributors.
2. `inc/class-astra-dynamic-css.php::Astra_Dynamic_CSS::return_output()` concatenates all contributions.
3. The result is inlined via `wp_add_inline_style( 'astra-theme-css', $css )` on `wp_enqueue_scripts`.
4. Customizer updates trigger fresh compilation; no file writes on save.

Trade-offs on extraction: dynamic CSS is tied to `get_theme_mod` values, so lifting a dynamic-css module means also lifting the matching customizer options (or substituting widget controls if going into Elementor).

## 5. Customizer organization

`inc/customizer/` wires a declarative section/panel system:

```
customizer/
├── class-astra-customizer.php                  # Orchestrator + register_sections_panels
├── class-astra-customizer-register-sections-panels.php
├── class-astra-customizer-callback.php         # active_callback helpers
├── class-astra-customizer-partials.php         # selective refresh
├── class-astra-customizer-sanitizes.php        # All sanitize_callbacks
├── class-astra-extended-base-configuration.php # Base class for configs
├── class-astra-builder-customizer.php          # Builder customizer glue
├── class-astra-font-families.php
├── class-astra-fonts.php
├── class-astra-fonts-data.php
├── configurations/                             # Declarative section configs (50+ files)
├── custom-controls/                            # Custom WP_Customize_Control subclasses
├── extend-customizer/                          # JS/React additions to Customizer UI
├── extend-custom-controls/
├── astra-pro/                                  # Pro upsell hooks
└── override-defaults.php
```

Options read via `astra_get_option( 'key', $default )` which wraps `get_theme_mod` with filterable defaults.

## 6. Admin UI (React SPA)

`admin/` contains a modern React admin experience distinct from Customizer:

```
admin/
├── class-astra-admin-loader.php                # WP menu registration
├── class-astra-bsf-analytics.php               # Telemetry enrollment
├── assets/
│   ├── build/                                  # Compiled React bundle
│   └── theme-builder/                          # Theme-builder-specific assets
└── includes/
    ├── class-astra-menu.php                    # Submenu structure
    ├── class-astra-learn.php                   # Onboarding/tutorial content tree
    ├── class-astra-theme-builder-free.php      # Scaffolding for optional Theme Builder addon
    ├── class-astra-api-init.php                # REST endpoints (/astra/v1)
    └── class-astra-admin-ajax.php              # Legacy admin-ajax.php handlers
```

The React admin consumes `/wp-json/astra/v1/*` routes exposed by `class-astra-api-init.php`.

## 7. Abilities API integration (`inc/abilities/`)

Astra exposes its features as **WordPress Abilities** for AI/MCP consumption:

```
inc/abilities/
├── bootstrap.php
├── class-astra-abilities-init.php
├── class-astra-abstract-ability.php
├── class-astra-abilities-helper.php
├── class-astra-abilities-response.php
├── class-astra-abilities-cli.php              # WP-CLI commands
├── admin/                                     # Abilities exposed to admin UI
└── customizer/                                # Abilities exposed to customizer
```

Filter `astra_ability_mcp_public` controls whether an ability is exposed to the default MCP server. Filter `astra_ability_show_in_rest` controls REST visibility.

## 8. Template file layout

Classic hierarchy with Astra-specific overrides:

```
<root>/
├── header.php, footer.php, single.php, page.php,
│   archive.php, search.php, 404.php, index.php,
│   comments.php, sidebar.php, searchform.php
├── template-parts/
│   ├── 404/
│   ├── advanced-footer/
│   ├── blog/
│   ├── footer/
│   ├── header/
│   ├── single/
│   ├── archive-banner.php, single-banner.php,
│   │ special-banner.php, scroll-to-top.php, …
│   └── content-*.php variants
└── theme.json                                 # Block-editor palette only
```

Root templates are thin shims that call `astra_entry_header()`, `astra_entry_content()`, etc. — functions defined in `inc/template-tags.php`.

## 9. Lifecycle hooks worth knowing

| Hook | What fires here |
| :--- | :--- |
| `after_setup_theme` | `Astra_After_Setup_Theme` loads supports, image sizes, nav menus. |
| `init` | Shortcodes (none in Astra), post types from modules (none in free theme), schema. |
| `wp_enqueue_scripts` | `Astra_Enqueue_Scripts` + dynamic CSS inline injection. |
| `customize_register` | Entire customizer section/control tree. |
| `rest_api_init` | `Astra_API_Init` routes. |
| `admin_menu` | `Astra_Menu`, `Astra_Admin_Loader`. |
| `widgets_init` | `inc/widgets.php` sidebars. |

See [`03-hooks-filters.md`](03-hooks-filters.md) for the full astra_*/filter-level API.
