---
topic: Astra Feature Extraction Index
last_synced: 2026-11-20
source_subject: /root/Desktop/learn/BAse/workspace/test-subjects/themes/astra/astra
---

# Astra Feature Extraction Index

The **extraction map**. Every feature findable by name, each with: entry files, class/function names, dependencies, and a pointer to its extraction recipe in [`05-extraction-guide.md`](05-extraction-guide.md). Ordered by domain, then feature.

Paths are **relative to** `workspace/test-subjects/themes/astra/astra/`.

---

## Legend

| Column | Meaning |
| :--- | :--- |
| Feature | User-facing name |
| Entry | Main PHP file(s) |
| Class / Function | Primary PHP class or function (see file for namespace) |
| Deps | WordPress core only / plugin X / theme customizer / dynamic CSS |
| Extract diff. | 🟢 easy (self-contained) · 🟡 moderate (needs customizer or markup glue) · 🔴 hard (deeply coupled) |
| Recipe | Pointer into `05-extraction-guide.md` |

---

## A. Core infrastructure

| Feature | Entry | Class / Function | Deps | Extract diff. | Recipe |
| :--- | :--- | :--- | :--- | :--- | :--- |
| Theme options getter/setter (`astra_get_option`) | `inc/core/class-astra-theme-options.php`, `inc/core/common-functions.php` | `Astra_Theme_Options`, `astra_get_option()` | Customizer | 🟡 | §Options wrapper |
| Theme strings / i18n | `inc/core/class-theme-strings.php` | `Astra_Theme_Strings` | Core | 🟢 | §Template toolkit |
| SVG icon registry | `inc/core/class-astra-icons.php` | `Astra_Icons` | None | 🟢 | §Icon library |
| Attribute builder | `inc/core/class-astra-attr.php` | `Astra_Attr` | None | 🟢 | §Attr helper |
| Theme-hook declarations | `inc/core/theme-hooks.php` | procedural `astra_*` functions | None | 🟢 | §Hook scaffolding |
| Template tags | `inc/template-tags.php` | `astra_*` template functions | Core | 🟡 | §Template toolkit |
| Markup extras | `inc/markup-extras.php` | 40+ `astra_*` functions | Customizer | 🔴 | §Markup helpers |
| Loop wrapper | `inc/class-astra-loop.php` | `Astra_Loop` | Core | 🟢 | §Loop wrapper |
| Dedicated page walker | `inc/core/class-astra-walker-page.php` | `Astra_Walker_Page` | None | 🟢 | §Page walker |
| Sidebar manager | `inc/core/sidebar-manager.php` | procedural | None | 🟢 | §Sidebar mgmt |
| Mobile header | `inc/class-astra-mobile-header.php` | `Astra_Mobile_Header` | Builder optional | 🟡 | §Mobile header |
| Command Palette integration | `inc/core/class-astra-command-palette.php` | `Astra_Command_Palette` | WP ≥ 6.3 | 🟢 | §Command palette |
| Memory limit admin notice | `inc/class-astra-memory-limit-notice.php` | `Astra_Memory_Limit_Notice` | None | 🟢 | §Memory notice |
| Theme background updater | `inc/theme-update/class-astra-theme-background-updater.php` | `Astra_Theme_Background_Updater` | Core | 🟡 | §Background updater |

---

## B. Dynamic CSS engine (`inc/dynamic-css/` + `inc/class-astra-dynamic-css.php`)

| Feature | Entry | Class / Function | Deps | Extract diff. | Recipe |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **CSS compositor** | `inc/class-astra-dynamic-css.php` | `Astra_Dynamic_CSS` | Customizer | 🔴 | §Dynamic-CSS compositor |
| Astra Icons CSS | `inc/dynamic-css/astra-icons.php` | procedural filter cb | — | 🟢 | §Lift a dynamic-CSS module |
| Block editor compatibility | `inc/dynamic-css/block-editor-compatibility.php` | procedural | Gutenberg | 🟡 | §Lift a dynamic-CSS module |
| Comments (classic) | `inc/dynamic-css/comments.php` | procedural | — | 🟢 | §Lift a dynamic-CSS module |
| Comments (flex) | `inc/dynamic-css/comments-flex.php` | procedural | — | 🟢 | §Lift a dynamic-CSS module |
| Container layouts | `inc/dynamic-css/container-layouts.php` | procedural | — | 🟡 | §Lift a dynamic-CSS module |
| Content background | `inc/dynamic-css/content-background.php` | procedural | — | 🟡 | §Lift a dynamic-CSS module |
| Custom-menu old header | `inc/dynamic-css/custom-menu-old-header.php` | procedural | — | 🟡 | §Lift a dynamic-CSS module |
| **Dark mode** | `inc/dynamic-css/dark-mode.php` | procedural | Customizer dark-mode option | 🟡 | §Dark mode |
| Global colour palette | `inc/dynamic-css/global-color-palette.php` + `inc/class-astra-global-palette.php` | `Astra_Global_Palette` | Customizer | 🟡 | §Global palette |
| Inline on mobile | `inc/dynamic-css/inline-on-mobile.php` | procedural | — | 🟢 | §Lift a dynamic-CSS module |
| **Live search** | `inc/dynamic-css/live-search.php` | procedural | admin-ajax or REST | 🟡 | §Live search |
| Logo SVG icons | `inc/dynamic-css/logo-svg-icons.php` | procedural | — | 🟢 | §Lift a dynamic-CSS module |
| Navigation CSS | `inc/dynamic-css/navigation.php` | procedural | — | 🟡 | §Lift a dynamic-CSS module |
| Pagination CSS | `inc/dynamic-css/pagination.php` | procedural | — | 🟢 | §Lift a dynamic-CSS module |
| Single post CSS | `inc/dynamic-css/single-post.php` | procedural | — | 🟡 | §Lift a dynamic-CSS module |

---

## C. Header / Footer Builder (`inc/builder/`)

**Big system.** Astra's free alternative to Elementor Theme Builder. Extracting this whole system is a project; extracting a single element (e.g. the off-canvas drawer) is tractable.

### Infrastructure
| Feature | Entry | Class / Function |
| :--- | :--- | :--- |
| Builder loader | `inc/builder/class-astra-builder-loader.php` | `Astra_Builder_Loader` |
| Base classes | `inc/builder/type/base/` | element/component abstract bases |
| Builder assets | `inc/builder/type/assets/` | enqueuers + builder-scoped CSS |
| Markup helpers | `inc/builder/markup/` | shared render fns |
| Admin controllers | `inc/builder/controllers/` | customizer wiring + preview |

### Header elements (`inc/builder/type/header/<slug>/`)
| Element | Notable features |
| :--- | :--- |
| `above-header`, `primary-header`, `below-header` | Three stackable rows with container layout, background, gap, mobile breakpoint |
| `site-identity` | Logo + site title/tagline + retina logo |
| `menu` | Primary menu element, drop-down behaviours, mega-menu hooks |
| `mobile-menu` | Separate menu for mobile breakpoint |
| `mobile-trigger` | Hamburger button → off-canvas |
| `off-canvas` | Slide-in drawer with content slots |
| `account` | My Account dropdown (WooCommerce / EDD aware) |
| `button` | CTA button |
| `edd-cart` | EDD cart icon + count + dropdown |
| `woo-cart` | WooCommerce cart icon + count + dropdown |
| `search` | Simple / full-screen search element |
| `widget` | Insert a WP widget area into the header |
| `html` | Arbitrary HTML element |
| `social-icon` | Social media icon strip |

### Footer elements (`inc/builder/type/footer/<slug>/`)
`above-footer`, `primary-footer`, `below-footer` (row containers), plus `copyright`, `widget`, `menu`, `button`, `social-icon`, `html`.

**Each element folder typically contains:** `class-astra-builder-<type>-<slug>-component.php`, `customizer/` config files, `dynamic-css/` module, and an entry in `template-parts/header/` or `template-parts/footer/`.

Extract difficulty: 🔴 whole system / 🟡 individual element. Recipe: `§Builder element`.

---

## D. Customizer (`inc/customizer/`)

| Feature | Entry | Purpose |
| :--- | :--- | :--- |
| Orchestrator | `class-astra-customizer.php` | Bootstrap, enqueue, preview registration |
| Sections/panels registrar | `class-astra-customizer-register-sections-panels.php` | Declarative panel/section catalogue |
| Sanitizers | `class-astra-customizer-sanitizes.php` | All `sanitize_callback` implementations |
| Partials (selective refresh) | `class-astra-customizer-partials.php` | Partial refresh region mapping |
| Active callbacks | `class-astra-customizer-callback.php` | Conditional control visibility |
| Base configuration | `class-astra-extended-base-configuration.php` | Abstract config class |
| Font registry | `class-astra-font-families.php`, `class-astra-fonts-data.php`, `class-astra-fonts.php` | Font picker / webfont data |
| Builder customizer glue | `class-astra-builder-customizer.php` | Builder-aware customizer wiring |
| Default overrides | `override-defaults.php` | Override WP core customizer defaults |
| Declarative configs | `configurations/` (50+ files) | Section × control declarations |
| Custom controls | `custom-controls/` | WP_Customize_Control subclasses |
| Extended controls | `extend-custom-controls/`, `extend-customizer/` | JS/React custom control additions |
| Pro upsell hooks | `astra-pro/class-astra-pro-upgrade-link-configs.php` | Upsell UI — strip on extraction |

Extract difficulty: 🟡 individual section / 🔴 whole customizer. Recipe: `§Customizer config lift`.

---

## E. Compatibility bridges (`inc/compatibility/`)

Each bridge is a self-contained singleton that hooks only when the target plugin is active.

| Bridge | File | Activation check |
| :--- | :--- | :--- |
| Gutenberg | `class-astra-gutenberg.php` | `has_blocks()` + core block detection |
| Jetpack | `class-astra-jetpack.php` | `class_exists('Jetpack')` |
| **WooCommerce** | `woocommerce/class-astra-woocommerce.php` + 20 sub-files | `class_exists('WooCommerce')` |
| **EDD** | `edd/class-astra-edd.php` + 6 sub-files | `class_exists('Easy_Digital_Downloads')` |
| LifterLMS | `lifterlms/class-astra-lifterlms.php` + 2 sub-files | `class_exists('LifterLMS')` |
| LearnDash | `learndash/class-astra-learndash.php` + 2 sub-files | `class_exists('SFWD_LMS')` |
| **Elementor** | `class-astra-elementor.php` (PHP ≥ 5.4) | `did_action('elementor/loaded')` |
| Elementor Pro | `class-astra-elementor-pro.php` | Pro plugin check |
| Web Stories | `class-astra-web-stories.php` | class check |
| Beaver Builder | `class-astra-beaver-builder.php` | class check |
| Beaver Themer | `class-astra-beaver-themer.php` | class check |
| BB Ultimate Addon | `class-astra-bb-ultimate-addon.php` | class check |
| Divi Builder | `class-astra-divi-builder.php` | class check |
| Visual Composer | `class-astra-visual-composer.php` | class check |
| SiteOrigin Page Builder | `class-astra-site-origin.php` | class check |
| Contact Form 7 | `class-astra-contact-form-7.php` | `class_exists('WPCF7')` |
| Gravity Forms | `class-astra-gravity-forms.php` | `class_exists('GFCommon')` |
| Ubermenu | `class-astra-ubermeu.php` | class check |
| BNE Flyout | `class-astra-bne-flyout.php` | class check |
| BuddyPress | `class-astra-buddypress.php` | `class_exists('BuddyPress')` |
| AMP | `class-astra-amp.php` | AMP plugin hooks |
| Yoast SEO | `class-astra-yoast-seo.php` | class check |
| SureCart | `surecart/class-astra-surecart.php` | class check |
| Starter content | `class-astra-starter-content.php` | theme install flow |

Extract difficulty: 🟢 (each bridge is a reference class for how to *write* compat without lock-in). Recipe: `§Compat bridge template`.

---

## F. Free bundled "addons" (`inc/addons/`)

Astra calls these addons but they're feature flags that just load extra files.

| Feature | Folder | Primary class |
| :--- | :--- | :--- |
| **Transparent header** | `addons/transparent-header/` | `Astra_Ext_Transparent_Header` |
| **Breadcrumbs** | `addons/breadcrumbs/` | `Astra_Breadcrumbs` |
| **Scroll-to-top button** | `addons/scroll-to-top/` | `Astra_Scroll_To_Top` |
| **Heading colours** | `addons/heading-colors/` | `Astra_Heading_Colors` |

Extract difficulty: 🟢 for scroll-to-top and heading-colors / 🟡 for breadcrumbs (depends on Yoast/Rank Math schema) / 🟡 for transparent-header (needs customizer tie-in). Recipe: `§Scroll-to-top button`, `§Breadcrumbs`, `§Transparent header`.

---

## G. Modules (`inc/modules/`)

| Feature | Entry | Class |
| :--- | :--- | :--- |
| Posts structures | `modules/posts-structures/class-astra-post-structures.php` | `Astra_Post_Structures` |
| Related posts | `modules/related-posts/class-astra-related-posts.php` | `Astra_Related_Posts` |

Both are free in Astra 4.x. Pro features (recommended posts, carousel variants) are gated behind addon checks.

Extract difficulty: 🟡. Recipe: `§Related posts module`.

---

## H. Admin UI (`admin/` + `inc/core/class-astra-admin-settings.php`)

| Feature | Entry | Notes |
| :--- | :--- | :--- |
| **Admin menu** | `admin/includes/class-astra-menu.php` | WP admin page tree |
| **Admin React SPA** | `admin/assets/build/` (bundle) + `includes/class-astra-admin-loader.php` | Modern React dashboard, routed client-side |
| **REST controller** | `admin/includes/class-astra-api-init.php` | Routes under `/wp-json/astra/v1` |
| Admin AJAX legacy | `admin/includes/class-astra-admin-ajax.php` | Backward-compat AJAX endpoints |
| **Learn / onboarding** | `admin/includes/class-astra-learn.php` | Chapter + step tree for tutorials |
| **Theme Builder (free)** | `admin/includes/class-astra-theme-builder-free.php` | Scaffold linking to Theme Builder addon |
| Admin settings | `inc/core/class-astra-admin-settings.php` | Older settings page |
| Admin helper | `inc/core/class-astra-admin-helper.php` | Utility functions |
| **BSF Analytics** | `admin/class-astra-bsf-analytics.php` + `inc/lib/bsf-analytics/` | Opt-in telemetry — strip on extraction |
| Admin loader | `admin/class-astra-admin-loader.php` | Asset enqueue + routing |

Extract difficulty: 🟡 for a specific admin page / 🔴 for the full React SPA. Recipe: `§React admin page`.

---

## I. WordPress Abilities API (`inc/abilities/`)

Modern AI/MCP integration. Exposes Astra features as callable abilities for AI agents.

| Feature | Entry | Class |
| :--- | :--- | :--- |
| Bootstrap | `abilities/bootstrap.php` | procedural init |
| Initialiser | `abilities/class-astra-abilities-init.php` | `Astra_Abilities_Init` |
| Abstract base | `abilities/class-astra-abstract-ability.php` | `Astra_Abstract_Ability` |
| Helper | `abilities/class-astra-abilities-helper.php` | `Astra_Abilities_Helper` |
| Response wrapper | `abilities/class-astra-abilities-response.php` | `Astra_Abilities_Response` |
| WP-CLI commands | `abilities/class-astra-abilities-cli.php` | Astra abilities CLI |
| Admin-scoped abilities | `abilities/admin/` | Admin-side ability declarations |
| Customizer-scoped abilities | `abilities/customizer/` | Customizer-side ability declarations |

Extract difficulty: 🟢 (concept + base class are reusable). Recipe: `§Abilities scaffold`.

---

## J. Schema.org structured data (`inc/schema/`)

| Schema type | File | Class |
| :--- | :--- | :--- |
| Breadcrumb | `schema/class-astra-breadcrumb-schema.php` | `Astra_Breadcrumb_Schema` |
| CreativeWork | `schema/class-astra-creativework-schema.php` | `Astra_CreativeWork_Schema` |
| Organization | `schema/class-astra-organization-schema.php` | `Astra_Organization_Schema` |
| Person | `schema/class-astra-person-schema.php` | `Astra_Person_Schema` |
| WPHeader | `schema/class-astra-wpheader-schema.php` | `Astra_WPHeader_Schema` |
| WPFooter | `schema/class-astra-wpfooter-schema.php` | `Astra_WPFooter_Schema` |
| WPSidebar | `schema/class-astra-wpsidebar-schema.php` | `Astra_WPSidebar_Schema` |
| SiteNavigationElement | `schema/class-astra-site-navigation-schema.php` | `Astra_SiteNavigation_Schema` |
| Loader | `schema/class-astra-schema.php` | `Astra_Schema` |

Extract difficulty: 🟢 (each class is self-contained). Recipe: `§Schema module`.

---

## K. Metaboxes (`inc/metabox/`)

| Feature | Entry | Class |
| :--- | :--- | :--- |
| Meta boxes | `metabox/class-astra-meta-boxes.php` | `Astra_Meta_Boxes` — per-post layout overrides |
| Meta box operations | `metabox/class-astra-meta-box-operations.php` | helpers |
| Elementor editor settings | `metabox/class-astra-elementor-editor-settings.php` | `Astra_Elementor_Editor_Settings` |
| Extensions | `metabox/extend-metabox/` | Custom metabox JS/React |

Extract difficulty: 🟡. Recipe: `§Layout override metabox`.

---

## L. Shared libraries (`inc/lib/`)

Brainstorm Force shared packages — reusable in any plugin by the same vendor.

| Library | Folder | Function |
| :--- | :--- | :--- |
| **Astra Notices** | `lib/astra-notices/` | Admin notice manager with dismissibility + classes `BSF_Admin_Notices` |
| **BSF Analytics** | `lib/bsf-analytics/` | Opt-in telemetry sender |
| **NPS Survey** | `lib/nps-survey/` + `class-astra-nps-notice.php` + `class-astra-nps-survey.php` | NPS popup component |
| **Batch Processing** | `lib/batch-processing/` | Background job runner for long upgrades |
| **Docs loader** | `lib/docs/class-astra-docs-loader.php` | Contextual help links |
| **Webfont loader** | `lib/webfont/class-astra-webfont-loader.php` | Self-hosts Google Fonts |

Extract difficulty: 🟢. Recipe: `§BSF library lift` for each.

---

## M. Theme-level setup (`inc/class-astra-after-setup-theme.php` and bootstrap)

| Theme support declared | Source |
| :--- | :--- |
| `align-wide` | after_setup_theme |
| `automatic-feed-links` | after_setup_theme |
| `title-tag` | after_setup_theme |
| `post-thumbnails` | after_setup_theme |
| `starter-content` (compat layer) | after_setup_theme |
| `custom-background`, `custom-header`, `custom-logo` | after_setup_theme |
| `customize-selective-refresh-widgets` | after_setup_theme |
| `responsive-embeds` | after_setup_theme |
| `woocommerce`, `wc-product-gallery-zoom`, `wc-product-gallery-lightbox`, `wc-product-gallery-slider` | WooCommerce compat |
| `rank-math-breadcrumbs` | breadcrumbs addon |
| `editor-color-palette` (block editor) | Gutenberg compat |
| `web-stories` | web-stories compat |
| `fl-theme-builder-headers/footers/parts` | Beaver Themer compat |
| `lifterlms` | LifterLMS compat |

---

## N. Everything not covered above (low-value in a free theme)

- `inc/widgets.php` — classic WP widget area registration.
- `inc/blog/` — blog layout helpers (`blog-config.php`, `blog.php`, `single-blog.php`).
- `inc/template-parts.php` — `get_template_part()` convenience functions.
- `inc/extras.php` — misc helpers.
- `inc/admin-functions.php` — admin-side helpers used by multiple classes.
- `inc/core/class-astra-enqueue-scripts.php` — frontend asset enqueuing.
- `inc/core/class-gutenberg-editor-css.php` / `class-astra-wp-editor-css.php` — editor style sync.

---

## O. Novel concepts to be aware of before extracting

| Concept | Where seen | Why it matters |
| :--- | :--- | :--- |
| **"Dynamic CSS"** | §B | CSS is composed per-request in PHP. If you lift any visual feature, you probably also lift one or two dynamic-CSS contributors — otherwise customization becomes dead weight. |
| **Attribute-centric markup** | §3 (hooks) | Astra's HTML attrs route through `Astra_Attr`. On extraction either preserve this helper or de-templatize into raw HTML. |
| **Pro upsell wall** | §D (Customizer `astra-pro/`) | Many Customizer controls render *disabled* UI pointing to Astra Pro. Lifting them pulls in dead upsell UI. Strip during extraction. |
| **BSF Analytics telemetry** | §L | Opt-in but **do not ship** in your extracted code. Remove the `inc/lib/bsf-analytics/` require and admin wiring. |
| **Freemium split-file pattern** | Throughout | Features often check `defined('ASTRA_EXT_VERSION')` or `astra_addon_*` filters. Those branches become dead code on extraction. |
| **BSF shared libs** | §L | `astra-notices`, `nps-survey`, `batch-processing`, `webfont`, `docs` are reusable across BSF plugins — the same files appear in Spectra, Sure plugins, etc. Lift once, reuse everywhere. |
| **Learn tutorial tree** | §H | Astra ships an in-admin onboarding tour. Interesting UX pattern — can be extracted as a generic admin tutorial module. |
| **Command Palette** | §A | Modern WP 6.3+ Command Palette integration. Rare in community themes — worth extracting. |
| **Abilities API** | §I | Modern AI/MCP exposure. Reusable scaffold — abstract ability class + CLI + customizer binder. |
| **Hybrid theme.json** | §M | Classic theme with `theme.json` purely for editor palette. Pattern worth copying in a new theme. |

---

## Next step

Pick any row. Its recipe in [`05-extraction-guide.md`](05-extraction-guide.md) names the exact decoupling + re-prefixing + PHP modernisation moves for that feature.
