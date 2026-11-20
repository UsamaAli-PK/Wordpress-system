---
topic: Premium Addons — Architecture & Bootstrap
last_synced: 2026-11-20
source_subject: /root/Desktop/learn/BAse/workspace/test-subjects/plugins/premium-addons-for-elementor/premium-addons-for-elementor
---

# Premium Addons — Architecture & Bootstrap

## 1. Load graph

```
premium-addons-for-elementor.php                 # Plugin entry
├── defines constants (VERSION, PATH, URL, BASENAME, STABLE_VERSION)
├── defines wp_upload_dir()-derived PREMIUM_ASSETS_PATH / URL
├── require autoload.php                         # Custom spl_autoload_register
└── require includes/class-pa-core.php
    └── new PremiumAddons\Includes\PA_Core()     # Singleton root
         │
         ├── Hooks:
         │   add_action('init',             [$this,'i18n'])
         │   add_action('init',             [$this,'init'], -999)      # very early init
         │   add_action('plugins_loaded',   [$this,'pa_init'])
         │   register_activation_hook                                   # seeds install
         │   register_deactivation_hook
         │   register_uninstall_hook
         │
         └── pa_init() runs on plugins_loaded:
              ├── Admin_Helper::get_instance()
              │    # Reads enabled widgets/addons toggle state
              │
              ├── Addons_Integration::get_instance()
              │    ├── Helper_Functions::check_elementor_version()
              │    ├── self::$modules      = Admin_Helper::get_enabled_elements()
              │    ├── self::$integrations = Admin_Helper::get_integrations_settings()
              │    ├── $this->register_hooks()
              │    ├── $this->register_ajax_hooks()
              │    ├── $this->load_extensions()
              │    ├── add_filter('elementor/editor/localize_settings',
              │    │               [$this,'add_papro_elements'], 20)
              │    └── Assets_Manager::get_instance($modules, $integrations)
              │
              └── $this->load_abilities()
                   ├── add_action('pa_oauth_gc', OAuth\Store::class . '::gc')
                   │    # Always registered so daily cron keeps running even if
                   │    # the AI switcher is OFF.
                   ├── Route_Detector::init()
                   │    # Detects whether other MCP servers already carry PA abilities
                   └── IF enabled_elements['premium-ai-abilities']:
                        Abilities\Bootstrap::get_instance()
                        └── registers REST route  premium-addons/mcp
                            + OAuth
                            + PA-specific MCP tools
```

## 2. Custom autoloader (`autoload.php`)

PSR-ish but **not** PSR-4. `spl_autoload_register` maps:

```
PremiumAddons\Widgets\Premium_Fancytext
          ↓
PREMIUM_ADDONS_PATH + widgets/premium-fancytext.php
```

Rules:
1. Strip the `PremiumAddons\` prefix.
2. Insert hyphens between lowercase→uppercase transitions (`PremiumFancytext` → `premium-fancytext`).
3. Replace underscores with hyphens.
4. Replace backslashes with directory separators.
5. Lowercase the whole thing.
6. Append `.php`.

So folder layout *must* be kebab-case lowercase; class names mix PascalCase and underscore (`Premium_Fancytext`, `Admin_Helper`, `PA_Core`).

The MCP SDK gets its **own** autoloader at `includes/abilities/vendor/autoload.php` — that one is proper Composer PSR-4.

## 3. Elementor registration points

`Addons_Integration::register_hooks()` wires into Elementor's modern (3.x+) registration API:

| Elementor hook | PAE callback | What it registers |
| :--- | :--- | :--- |
| `elementor/elements/categories_registered` (prio 9) | `register_widgets_category` | A `premium-elements` widget category |
| `elementor/controls/register` | `register_controls` | 6 custom controls (background, select, post-filter, tax-filter, acf-selector, image-choose) |
| `elementor/widgets/register` | `register_widgets` | 37 widgets, each guarded by its own enabled toggle |
| `elementor/preview/enqueue_styles` | `enqueue_preview_styles` | Editor preview CSS |
| `elementor/preview/enqueue_scripts` (prio 999) | `load_preview_libs_on_demand` | On-demand libs (isotope, lottie, luxon) |
| `elementor/editor/before_enqueue_styles` | `enqueue_editor_styles` | Editor UI icons & theme adjustments |
| `elementor/editor/before_enqueue_scripts` | `before_enqueue_scripts` | Editor-only JS |
| `elementor/editor/after_enqueue_scripts` | `after_enqueue_scripts` | `pa-controls-handlers`, `pa-editor-handler`, localized `PremiumSettings` |
| `elementor/editor/localize_settings` (prio 20) | `add_papro_elements` | Inserts Pro upsell items |

**On-demand vendor libs** — PAE lazy-loads heavy vendor libraries only if the editor contains a widget needing them, mapped by constant:
```php
const PREVIEW_ON_DEMAND_LIBS = [
    'isotope' => 'isotope-js',
    'lottie'  => 'lottie-js',
    'luxon'   => 'pa-luxon',
];
```

## 4. Widget registration shape

Each widget is a single PHP file under `widgets/<slug>.php` with:
- `namespace PremiumAddons\Widgets;`
- `class Premium_<Name> extends \Elementor\Widget_Base`
- `get_name()`, `get_title()`, `get_icon()`, `get_categories()`, `get_keywords()`
- `get_style_depends()` / `get_script_depends()` referencing handles registered by `Assets_Manager`
- `register_controls()` with Content + Style + (optional) Advanced tabs
- `render()` server-side output
- `content_template()` client-side Backbone template for live editor preview

Example (from `widgets/premium-fancytext.php`): widget slug `premium-addon-fancy-text`, class `Premium_Fancytext`, uses `Premium_Background` custom control + Elementor Group Controls (Typography, Text_Shadow) + Kit globals (Global_Colors, Global_Typography). Style deps: `pa-glass`, `premium-addons`.

## 5. Assets Manager (`includes/assets-manager.php`)

Not standard WordPress enqueue — PAE scans post content to decide what to load:

- Key constant: `const ASSETS_KEY = '_pa_widget_elements';`
- Per-post meta that caches which PA widgets/addons appear in that post.
- On `wp_enqueue_scripts`, Assets_Manager reads this cache and enqueues only matching per-widget CSS/JS handles.
- Cache is built when Elementor saves a document (`elementor/document/after_save`).
- Registers all PA scripts with `wp_register_*` but only enqueues on demand.

This is the **right** performance pattern: zero cost on pages not using PA, full assets on pages that do.

## 6. Admin orchestration (`admin/includes/admin-helper.php`)

- Singleton: `Admin_Helper::get_instance()`
- Seeds the plugin admin menu (top-level "Premium Addons" + submenus for Settings, Setup Wizard, API Keys, Templates, MCP, Rollback)
- Loads all admin sub-modules: `Admin_Bar`, `Admin_Notices`, `Beta_Testers`, `Duplicator`, `Elements` (toggler), `Feedback`, `Keys`, `Pa_Rollback`, `Promotion_Pointer`
- Exposes two central APIs the rest of the codebase relies on:
  - `Admin_Helper::get_enabled_elements()` — associative array of toggle slug → bool
  - `Admin_Helper::get_integrations_settings()` — API keys and per-integration settings

Every widget/addon checks `get_enabled_elements()` before registering. Disabling a widget in the admin UI prevents it from loading at all (no enqueue cost, no registration cost).

## 7. MCP server (`includes/abilities/`)

A stand-alone MCP server that exposes Elementor and PAE capabilities to AI clients.

```
includes/abilities/
├── bootstrap.php                 # Entry — wires MCP tools to the REST route
├── route-detector.php            # Detects foreign MCP servers also carrying PA abilities
├── elementor-mcp.php             # Elementor-specific tool definitions (create, read, update layouts)
├── connection-log.php            # Persists MCP connection events
├── helpers.php, input-normalizer.php
├── dashboard/
│   ├── disable-unused-widgets.php   # MCP tool: disable widgets not used in any post
│   └── get-settings.php             # MCP tool: read site/PA settings
├── Registry/Ability_Registry.php    # Abstract registry
├── OAuth/
│   ├── Store.php                    # OAuth code store (static gc() runs on pa_oauth_gc cron)
│   └── (challenge handler, …)
└── vendor/                       # Bundled Composer deps for the MCP SDK (do not edit)
```

Key architectural choices:
- REST namespace `premium-addons/mcp` (i.e. `/wp-json/premium-addons/mcp/*`).
- Category labels prefixed `PA` so abilities don't collide with other MCP servers.
- Gated by `premium-ai-abilities` toggle. If the gate is OFF, `Bootstrap::get_instance()` is never called but the OAuth GC cron is still scheduled — a defensive choice so turning the feature on later doesn't leak expired tokens.
- `Connection_Log::init()` clears logs when an Application Password is deleted (data hygiene).

## 8. Display Conditions (`includes/pa-display-conditions/`)

A separate proprietary conditions engine running independently of Elementor Pro's native conditions:

- `pa-controls-handler.php` injects a "Display Conditions" tab into every element's Advanced panel.
- `conditions/` holds one PHP class per condition (user role, device, date, time, URL, referrer, query var, cart contents, logged-in state, user meta, cookie, geolocation, language).
- `lang-locale.php` adds language condition logic (WPML/PolyLang aware).

Non-trivial — the whole engine is a candidate for extraction as its own standalone plugin.

## 9. Global addons (`addons/`)

Each is a single file exposing additional controls on all Elementor elements (not just PAE widgets):

```
addons/
├── display-conditions.php   # Uses engine in includes/pa-display-conditions/
├── equal-height.php
├── floating-effects.php
├── liquid-glass.php
├── shape-divider.php
├── shapes.php
├── tooltips.php
└── wrapper-link.php
```

They hook into `elementor/element/after_section_end` (or similar) to append controls, then into frontend render filters to output markup / CSS / JS.

## 10. Extensions (`includes/extras/`)

Smaller feature modules loaded by `Addons_Integration::load_extensions()`:

- `angie-integration.php` — Angie AI integration glue
- `cf7-inserter.php` — Contact Form 7 form picker in Elementor
- `cross-copy-paste.php` — copy elements across different WP sites
- `live-editor.php` + `live-editor-modal.php` — in-editor modal preview

## 11. Lifecycle hooks and timing

| Hook | What happens |
| :--- | :--- |
| plugin load | Constants, autoload, PA_Core singleton created |
| `init` prio 10 | `i18n()` loads translations |
| `init` prio -999 | Early init (reserved for very-early feature registration) |
| `plugins_loaded` | `pa_init()`: Admin_Helper, Addons_Integration, Abilities bootstrap |
| `elementor/elements/categories_registered` prio 9 | PA widget category registered |
| `elementor/controls/register` | Custom controls registered |
| `elementor/widgets/register` | Widgets registered (gated by toggles) |
| `elementor/editor/before_enqueue_styles` | Editor UI styling |
| `elementor/preview/enqueue_scripts` prio 999 | On-demand vendor libs for editor preview |
| `wp_enqueue_scripts` | Frontend assets via Assets_Manager per-post cache |
| `pa_oauth_gc` (daily cron) | OAuth::Store::gc() clears expired tokens |

## 12. Database footprint

Grepped options and meta keys:

| Key | Purpose |
| :--- | :--- |
| `pa_install_time` | First install timestamp |
| `pa_review_notice` | Review-prompt snooze state |
| `_pa_widget_elements` (post meta) | Per-post cache of which PA elements appear |
| `pa_save_version` / `premium_addons_*` | Various per-feature settings |
| Admin_Helper::get_enabled_elements storage | Widget/addon toggles |

See `includes/class-pa-core.php::handle_activation()` for exact initial state seeding.

## 13. Internationalization

- Text domain: `premium-addons-for-elementor`
- Translations loaded on `init` via `PA_Core::i18n()`
- Language files in `languages/`

## 14. Why this architecture matters for extraction

- Every widget is a **single file**. Widget extraction is almost always a file copy + dependency fix.
- Every global addon is a **single file**. Same story.
- The custom controls (`includes/controls/`) are **small, reusable** — lifting them standalone is practical.
- The **Assets_Manager** pattern (per-post cache of needed elements) is itself worth extracting; it solves asset bloat for any large widget library.
- The **MCP server** is a complete reference implementation of the Elementor-MCP integration pattern described in `docs/05-mcp-and-cli/elementor-mcp-workflow.md`.
- The **Display Conditions** engine is self-contained and the biggest single reusable sub-system.

See [`04-feature-index.md`](04-feature-index.md) for the exhaustive map.
