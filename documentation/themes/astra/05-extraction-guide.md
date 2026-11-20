---
topic: Astra Feature Extraction Guide (Recipes)
last_synced: 2026-11-20
source_subject: /root/Desktop/learn/BAse/workspace/test-subjects/themes/astra/astra
---

# Astra Feature Extraction Guide

Per-feature extraction recipes. Each recipe is a mini-plan that applies [`playbooks/02-extract-isolate-merge.md`](file:///root/Desktop/learn/BAse/playbooks/02-extract-isolate-merge.md), [`03-section-to-elementor-widget.md`](file:///root/Desktop/learn/BAse/playbooks/03-section-to-elementor-widget.md), and [`04-build-unified-theme.md`](file:///root/Desktop/learn/BAse/playbooks/04-build-unified-theme.md) to the specific feature. Target destination is your own companion plugin under namespace `Agency\Companion\Extracted\Astra\<slug>`.

Shared moves (apply to every recipe):
1. **Re-prefix.** `astra_*` → `agency_astra_*` or feature-scoped. `Astra_Foo` → `Agency\Companion\Extracted\Astra\Foo`. CSS classes `.ast-*` → `.agency-ast-*`. Text domain → your plugin's.
2. **Modernize PHP.** Add `declare(strict_types=1)`. Type class properties, method params/returns. Replace `isset($x) ? $x : 'default'` with `$x ?? 'default'`. Replace singletons by whatever your plugin's container uses.
3. **Decouple options.** Every `astra_get_option('key')` or `get_theme_mod('key')` becomes either a widget control (`$settings['key']`), a plugin option (`get_option('agency_ast_key')`), or a filter the integrator passes values through.
4. **Dedupe assets.** If Astra's dynamic-CSS module paints into a class the companion plugin also styles, choose one source. Dequeue the other.
5. **Strip telemetry + upsell.** No `bsf-analytics`, no `astra_pro_*`, no `astra_addon_*` branches.
6. **Keep DOM identical on first pass.** Refactor markup only after the extracted feature works.

Recipes below assume a companion plugin scaffold matches `docs/02-plugins-and-data/companion-plugin-architecture.md`.

---

## §Scroll-to-top button (easy — 🟢)

**Source:** `inc/addons/scroll-to-top/class-astra-scroll-to-top.php` + `template-parts/scroll-to-top.php` + matching dynamic-CSS module.

Steps:
1. Copy the class into `src/Extracted/Astra/ScrollToTop.php`.
2. Replace `astra_get_option('scroll-to-top-enable')` with plugin option `agency_scroll_to_top_enabled`. Expose via a single admin toggle or Elementor site-wide setting.
3. Copy `template-parts/scroll-to-top.php` into your plugin's `templates/` and render through a hook of your own (e.g. `wp_footer`).
4. Copy the CSS from the matching dynamic-CSS contributor and ship it as a static stylesheet enqueued when the toggle is on.
5. Rewire JS (small inline snippet) to listen on `scroll` and animate via `window.scrollTo({ top: 0, behavior: 'smooth' })` — drop the jQuery dependency.
6. Test: enable toggle, scroll, click arrow — smooth scroll fires. Disable toggle, no markup emitted.

Risk: original class reads 4–6 customizer options (position, size, colour, icon). Convert each to a plugin option at extract time.

---

## §Breadcrumbs (moderate — 🟡)

**Source:** `inc/addons/breadcrumbs/class-astra-breadcrumbs.php` + schema class `inc/schema/class-astra-breadcrumb-schema.php` + dynamic-CSS entries.

Steps:
1. Lift the markup builder from `Astra_Breadcrumbs::render()` into `src/Extracted/Astra/Breadcrumbs.php`.
2. The original delegates to Yoast / Rank Math / Astra's own fallback. Keep the fallback, add toggleable Yoast / Rank Math support behind `function_exists()` guards (per `docs/02-plugins-and-data/acf-and-defensive-checks.md`).
3. Lift `Astra_Breadcrumb_Schema` separately so JSON-LD output is optional.
4. Replace `astra_get_option('breadcrumb-separator')` etc. with shortcode or widget attributes.
5. Expose as:
   - Shortcode `[agency_breadcrumbs separator=">"]`, **and**
   - Elementor widget using `docs/04-elementor-widgets/widget-base-and-rendering.md` template (the breadcrumb widget maps 1:1 to a `Widget_Base` with two controls: separator, prefix text).
6. Test: all three breadcrumb sources (Yoast → Rank Math → native) render correct link list.

Risk: schema output must come once per page only. Guard with a static flag.

---

## §Transparent header (moderate — 🟡)

**Source:** `inc/addons/transparent-header/class-astra-ext-transparent-header.php` + customizer section + dynamic-CSS contributor.

Steps:
1. Copy the class, strip customizer-section wiring.
2. Replace the customizer check with a single plugin option *per page context* (home, archive, single, custom post types).
3. The magic is CSS: body gets `.ast-theme-transparent-header` + the header gets `position: absolute`. Lift the CSS, port to scoped selectors.
4. If your site uses Elementor Theme Builder for the header: add a one-click "transparent on this page" toggle to your page metabox that outputs the class.

Risk: coupling to the Header/Footer Builder. If extracting for use with Elementor Pro header, the transparency is just a body class + a wrapper style, not the whole addon.

---

## §Related posts module (moderate — 🟡)

**Source:** `inc/modules/related-posts/class-astra-related-posts.php` + CSS + template.

Steps:
1. Copy class as `src/Extracted/Astra/RelatedPosts.php`.
2. The query builder picks posts by category / tag / taxonomy term. Preserve as a public method `get_related_posts( int $post_id, array $args = [] ): WP_Query`.
3. Render output in two ways:
   - **Shortcode** `[agency_related_posts count=3]` for classic themes.
   - **Elementor widget** with Content tab controls (count, taxonomy source, sort), Style tab controls (card background, typography via Group Controls).
4. Strip the "Pro only" style variations in the original.
5. Expose filter `agency_related_posts_query_args` for integrators.

Risk: original uses `astra_get_option('related-posts-*')` for 10+ styling knobs. Decide whether each becomes a widget control or a CSS variable with a sensible default.

---

## §Dark mode (moderate — 🟡)

**Source:** `inc/dynamic-css/dark-mode.php` + related customizer options + palette switching logic.

Steps:
1. Extract the palette generator (reads light-mode colours, outputs dark overrides via `prefers-color-scheme: dark` OR explicit `[data-theme="dark"]`).
2. Port into a static class `src/Extracted/Astra/DarkMode.php` with method `get_css( array $palette ): string`.
3. Expose via a shortcode `[agency_dark_mode_toggle]` plus a small JS handler toggling `document.documentElement.dataset.theme`.
4. Make palette source configurable: either Elementor Kit globals (`--e-global-color-*`), or custom plugin option, or auto via `prefers-color-scheme`.
5. Lift the dynamic-CSS contributor and ship as a static stylesheet with CSS variables.

Risk: hard-coded Astra token names. Rename to agency variables or wrap via `var(--agency-*, var(--e-global-*, <hex>))`.

---

## §Live search (moderate — 🟡)

**Source:** `inc/dynamic-css/live-search.php` (CSS) + admin-ajax handler in `inc/markup-extras.php` or dedicated class + JS handler under `assets/js/`.

Steps:
1. Locate the AJAX endpoint (grep `wp_ajax_astra_live_search`).
2. Port endpoint to `WP_REST_Controller` under `/wp-json/agency/v1/search` — better than admin-ajax for future-proofing.
3. Rewrite JS (small jQuery handler) as vanilla ES6 with fetch + debounce (lodash-free).
4. Deliver as an Elementor widget: search input → dropdown with live results. Control for post types to search, max results, highlight behaviour.
5. CSS: lift live-search dynamic-CSS contributor as a static scoped stylesheet.

Risk: the native implementation queries `WP_Query` with LIKE; large sites get slow. Consider adding an optional ElasticPress/Algolia backend behind `apply_filters('agency_live_search_backend', ...)`.

---

## §Global palette (moderate — 🟡)

**Source:** `inc/class-astra-global-palette.php` + `inc/dynamic-css/global-color-palette.php`.

Steps:
1. Lift `Astra_Global_Palette` as `src/Extracted/Astra/GlobalPalette.php`. It defines colour slots and generates `--ast-global-color-N` CSS variables.
2. Map Astra variables to **Elementor Kit** variables (`--e-global-color-primary`, etc.) via the pattern from `docs/03-elementor-theme/global-styles-and-css-sync.md`.
3. Output goes into a static stylesheet enqueued early with `wp_add_inline_style( 'agency-theme-css', $palette_css )`.
4. Offer an admin screen or a plugin option to edit slots; bridge to Customizer only if the destination theme is a classic theme.

Risk: Astra uses palette slots #1–#8. Document the semantic meaning of each so the destination theme uses them consistently.

---

## §Builder element (per-element — 🟡)

Example: lifting the **off-canvas drawer** from `inc/builder/type/header/off-canvas/`.

Steps:
1. Copy the element folder (`component class`, `customizer config`, `dynamic-CSS`, `template-part`).
2. Replace the Astra Builder base class (`Astra_Builder_Base_Dynamic_CSS` etc.) with a minimal abstract class in your plugin's `src/Extracted/Astra/Builder/`.
3. Strip customizer wiring — expose as:
   - a shortcode `[agency_off_canvas id="main"]`, **or**
   - an Elementor widget, **or**
   - a page-level hook called from `header.php`.
4. Lift the slide-in CSS + the mobile trigger JS. The drawer animation is CSS transforms + `inert` attribute for a11y. Keep that pattern.
5. Content inside the drawer should accept a WP widget area (classic) or an Elementor template (Pro Theme Builder).

Risk: Astra's off-canvas is wired to the mobile-trigger element. If you lift drawer only, re-expose a public toggle API (`AgencyOffCanvas.open('main')`).

---

## §Lift a dynamic-CSS module (template — 🟢 for simple ones)

Pattern that applies to any file under `inc/dynamic-css/`:

1. **Read the file.** Each one registers a callback on `astra_dynamic_css`. The callback returns a CSS string.
2. **Identify option reads.** Grep `astra_get_option(` inside the file. Each one becomes a parameter.
3. **Port** the callback into a static method: `AgencyDynamicCss::<module>($options): string`.
4. **Compile at init time** rather than request time — write to a transient or a cached file.
5. **Enqueue** as inline on your wrapper handle: `wp_add_inline_style( 'agency-theme-css', $css )`.

For modules with no options (icons, pagination defaults), you can just lift the CSS directly into a static `.css` file and skip the compilation layer entirely.

---

## §Dynamic-CSS compositor (hard — 🔴)

Lifting the whole `Astra_Dynamic_CSS` class is a project. Only worth doing if you're building a classic theme with heavy Customizer integration. For Elementor-first stacks, use CSS variables (`docs/03-elementor-theme/global-styles-and-css-sync.md`) instead.

---

## §Markup helpers (hard — 🔴)

`inc/markup-extras.php` is 2000+ lines of tightly-coupled procedural functions. Don't lift the file — cherry-pick individual functions when needed.

---

## §Attr helper (easy — 🟢)

**Source:** `inc/core/class-astra-attr.php`.

Steps:
1. Copy class. Rename to `Agency_Html_Attr` or `Agency\Companion\Html\Attr`.
2. Replace Astra-specific context-name filters with your own prefix.
3. Useful on its own as a drop-in for any new theme that wants filterable attributes without template edits.

---

## §Icon library (easy — 🟢)

**Source:** `inc/core/class-astra-icons.php`.

Steps:
1. Copy. The class registers SVG icons keyed by name and renders them inline.
2. Keep the registration API, change the prefix.
3. Great companion to any extracted feature that needs scoped icons without pulling in Font Awesome.

---

## §BSF library lift — template (easy — 🟢)

For any `inc/lib/<package>/` library:

1. **Astra Notices** → standalone notice mgmt lib. Copy folder, rename classes `BSF_*` → `Agency_Notice_*`. API: `$notices->add( ['id'=>..., 'type'=>'warning', 'message'=>...] )`.
2. **Webfont loader** → self-hosting Google Fonts. Lift verbatim, keep interface — pass font URLs, it caches locally.
3. **Docs loader** → in-admin contextual help. Rename, register your own documentation URLs.
4. **NPS Survey** → NPS popup. Only lift if you also need telemetry.
5. **Batch processing** → background job runner on WP cron. Reusable for data migrations.

Each of these libs is public-domain-style within the BSF ecosystem — they ship in multiple BSF plugins unchanged, so they're battle-tested.

---

## §Compat bridge template (easy — 🟢 for the pattern)

Any file under `inc/compatibility/class-astra-<plugin>.php` is a reference for writing a clean plugin compatibility bridge:

1. Singleton class.
2. Constructor registers hooks **only after checking the target plugin is loaded** (`class_exists`, `did_action`).
3. Each public method adjusts a specific plugin behaviour.
4. No fatals if the plugin is absent.

Use this pattern verbatim for your own agency bridges (e.g. Jetpack, Rank Math, specific Elementor addons).

---

## §Schema module (easy — 🟢)

**Source:** any `inc/schema/class-astra-<type>-schema.php`.

Steps:
1. Copy a single schema class.
2. Rename, rehome, change the schema `@context` output if needed (Astra's are correct).
3. Hook on `wp_head` priority 1 and output JSON-LD.
4. Combine with [Yoast / Rank Math] guard checks so you don't double-output.

---

## §React admin page (moderate — 🟡)

For the Astra admin SPA pattern:

1. Study `admin/class-astra-admin-loader.php` for enqueue/localisation pattern.
2. The React bundle at `admin/assets/build/` is compiled; its source isn't in the theme zip. For extraction you need to either reuse the compiled bundle (not advisable) or build your own React admin from scratch using the same enqueue pattern.
3. Lift the **REST controller pattern** from `admin/includes/class-astra-api-init.php` instead — it's the extensible part.

---

## §Command palette (easy — 🟢)

**Source:** `inc/core/class-astra-command-palette.php`.

Steps:
1. Lift class.
2. Replace the "register Astra customizer panels" loop with a filterable list: `apply_filters('agency_command_palette_commands', [...])`.
3. Works on WP 6.3+.

Rare in community themes — worth keeping around as a reusable pattern.

---

## §Abilities scaffold (easy — 🟢)

**Source:** `inc/abilities/class-astra-abstract-ability.php` + `class-astra-abilities-init.php`.

Steps:
1. Lift the abstract base and the init/registration pattern.
2. Rebrand `astra_ability_*` filters to `agency_ability_*`.
3. Keep `astra_ability_mcp_public` and `_show_in_rest` semantics — those are the hooks a companion plugin needs to expose abilities cleanly per `docs/05-mcp-and-cli/wp-cli-and-mcp-adapter.md`.

---

## §Customizer config lift

When you only want a single Customizer section (not the whole system):

1. Open the config file in `inc/customizer/configurations/<section>.php`.
2. Copy the array of control declarations.
3. Register them under your own panel using `$wp_customize->add_section()` / `add_control()` directly — don't lift the whole `Astra_Customizer` orchestrator.
4. Port sanitizers you reference from `class-astra-customizer-sanitizes.php`.

---

## §Everything else

For a feature not listed here, open [`04-feature-index.md`](04-feature-index.md), find the row, then apply the generic pipeline:

1. Grep the source class name — list every file where it appears.
2. Grep `astra_get_option(` inside those files — build the option-dependency list.
3. Grep the class's CSS selectors inside `assets/css/` — list the stylesheet lines to lift.
4. Grep JS files for the class's slug — list handler scripts.
5. Decide output form: shortcode, Elementor widget, standalone admin page, or integration hook.
6. Apply the shared moves at the top of this file.

---

## Component Manifest template (fill one per extraction)

Copied straight from `playbooks/01-reverse-engineer-audit.md` §3. Produce one of these as the deliverable for each actual extraction:

```markdown
# Component Manifest: <Feature Name>

### Source Provenance
- Source: Astra 4.14.3 (/root/Desktop/learn/BAse/workspace/test-subjects/themes/astra/astra)
- Files:
  - PHP: <paths>
  - CSS: <paths>
  - JS:  <paths>
  - Template: <paths>

### Data & Model Dependencies
- Options used: astra_get_option('<key>') × N
- Post meta keys: ...
- Taxonomies: ...

### Styling & CSS Footprint
- Root wrapper selector: ...
- Required CSS variables: ...
- Media query breakpoints: ...

### JavaScript & Interactivity
- Library deps: ...
- Init trigger: ...
- Re-wire target: `elementor/frontend/init` or `wp_footer` etc.

### Licensing & Asset Review
- License: GPL-2.0+ (Astra parent)
- Bundled media: ...
- Proprietary framework calls: `astra_get_option`, `astra_pro_*` (strip)
```
