---
topic: Premium Addons Widget — Animated Text
last_synced: 2026-11-20
widget_slug: premium-addon-fancy-text
source_file: /root/Desktop/learn/BAse/workspace/test-subjects/plugins/premium-addons-for-elementor/premium-addons-for-elementor/widgets/premium-fancytext.php
---

# Widget: Animated Text (`premium-addon-fancy-text`)

Rotating / typed / sliding text headline widget.

## Identity

| Field | Value |
| :--- | :--- |
| Elementor slug (`get_name()`) | `premium-addon-fancy-text` |
| Elementor label (`get_title()`) | Animated Text |
| Icon (`get_icon()`) | `pa-fancy-text` |
| Keywords (`get_keywords()`) | fancy, text, animation, typed, slide |
| Category | `premium-elements` |
| Class | `PremiumAddons\Widgets\Premium_Fancytext` |
| Source file | `widgets/premium-fancytext.php` |

## Asset dependencies

**CSS handles (`get_style_depends()`):**
- `pa-glass` — PAE's Liquid Glass addon CSS (shared)
- `premium-addons` — the aggregated addon stylesheet

Underlying CSS files:
- `assets/frontend/css/premium-fancy-text.css`
- `assets/frontend/min-css/premium-fancy-text.min.css`

**JS handles (`get_script_depends()`):**
Edit mode (Elementor editor):
- `pa-glass`
- `pa-typed` — bundled Typed.js library
- `pa-vticker` — bundled vertical ticker lib

Frontend:
- Dynamically chosen based on settings: Typed.js for typed animation, vertical ticker for slide-up, CSS-only for highlight/fade.

Underlying JS files:
- `assets/frontend/js/premium-fancy-text.js`
- `assets/frontend/min-js/premium-fancy-text.min.js`
- Vendor libs in `assets/frontend/min-js/lib/` (typed.min.js, etc.)

## Custom controls used

- `Premium_Background` (PAE custom control) — for highlight background
- Elementor `Group_Control_Typography`, `Group_Control_Text_Shadow`
- Elementor Kit globals: `Global_Colors`, `Global_Typography`

## Control sections (8 total)

Located at `start_controls_section()` call sites — lines 180, 358, 625, 703, 739, 1137, 1184, 1234. See file for exact label text per section. Typical structure:

| Section (approx.) | Tab | Purpose |
| :--- | :--- | :--- |
| 1. Content | Content | Prefix, suffix, text repeater (list of phrases), animation type |
| 2. Animation | Content | Animation speed, loop, pause between items |
| 3. Prefix / Suffix style | Style | Typography + colour of surrounding static text |
| 4. Animated Text style | Style | Typography + colour of the rotating text |
| 5. Highlight style | Style | Underline/box/shape highlight behind text |
| 6. Shape style | Style | SVG shape layer behind text (hooked via `pa_atext_shape_style`) |
| 7. Background | Style | Background via Premium_Background |
| 8. Advanced | Advanced | Standard Elementor advanced tab + PAE addons |

## Rendering

- `render()` — outputs root wrapper `.premium-fancy-text-wrapper` + inner animated element.
- `content_template()` — Backbone template for editor live preview.

## Public hooks fired (child extension points)

- `pa_atext_highlight_controls` — append controls into highlight style section
- `pa_atext_shape_style` — append shape style controls

These are the two entry points for extending Animated Text with custom styling without modifying the file.

## Extraction recipe

Classification: **🟢 easy** — single file, lightweight vendor (Typed.js).

1. Copy `widgets/premium-fancytext.php` → `src/Widgets/AnimatedText.php` in your plugin.
2. Replace namespace and class name (`Premium_Fancytext` → `Agency_Animated_Text` or `Agency\Companion\Widgets\AnimatedText`).
3. Change slug: `get_name()` returns `agency-animated-text` (avoids collision).
4. Replace `premium-addons`, `pa-glass`, `pa-typed`, `pa-vticker` style/script handles with your own. Register your own Typed.js enqueue (pull from CDN or vendor-assets folder).
5. Swap `Premium_Background` custom control for either:
   - Elementor's built-in `Group_Control_Background` (simpler), or
   - Lift the custom control per [`../05-extraction-guide.md`](../05-extraction-guide.md) §Custom controls.
6. Copy `assets/frontend/css/premium-fancy-text.css` to your plugin, rename class selectors (`.premium-fancy-text-*` → `.agency-animated-text-*`).
7. Copy `assets/frontend/js/premium-fancy-text.js`. Rewire slug matching in the handler: `frontend/element_ready/premium-addon-fancy-text.default` → `frontend/element_ready/agency-animated-text.default`.
8. Rename the two hook names (`pa_atext_highlight_controls`, `pa_atext_shape_style`) to agency-namespaced, or keep if you'd like extension compatibility.
9. Preflight: drag into Elementor editor, confirm live preview types out text, confirm front-end animates, console clean.

## Risks

- **Typed.js GPL compatibility** — Typed.js is MIT, compatible with GPL. OK.
- **Shape layer SVG** depends on files in `assets/images/`. Lift those or rebuild.
- **`pa-glass` dependency** — if you're not also lifting Liquid Glass, remove `pa-glass` from style deps; the widget will still work without it.

## Field for Component Manifest

Use template from [`../05-extraction-guide.md`](../05-extraction-guide.md) §Component Manifest.
