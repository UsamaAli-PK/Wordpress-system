---
topic: Premium Addons — Per-Widget Deep-Dive Index
last_synced: 2026-11-20
---

# Premium Addons — Widget Deep-Dive Pages

One page per widget. Each page follows the same template:

1. Identity (slug, class, source file)
2. Asset dependencies (CSS/JS handles + file paths + vendor libs)
3. DOM structure (if the widget has a non-trivial one)
4. Control sections (approximate breakdown — section count + what each covers)
5. Custom Elementor controls used
6. Public hooks fired (child extension points)
7. Extraction recipe (shortest-path steps + risks)
8. Interesting patterns to copy

## Deep-dived (template references)

- [`premium-fancytext.md`](premium-fancytext.md) — Animated Text (Typed.js). 🟢 easy.
- [`premium-blog.md`](premium-blog.md) — Blog Post Grid (Isotope + admin-ajax). 🟡 moderate, large (2700 LoC).

## Pending (fill on demand)

For the remaining 35 widgets listed in [`../04-feature-index.md`](../04-feature-index.md) §A, open the source file in `widgets/<slug>.php` and apply the deep-dive template above. The feature index already names:
- the source file path,
- the primary vendor library (if any),
- the extraction difficulty.

That is enough to write a page in ~5 minutes per widget. Stop before filling all 35 unless a specific widget is being extracted — see [`../../APPROACH.md`](../../APPROACH.md) §7.

## Writing a new page — 5-minute recipe

From inside the inner subject directory:

```bash
cd /root/Desktop/learn/BAse/workspace/test-subjects/plugins/premium-addons-for-elementor/premium-addons-for-elementor

slug=premium-weather

# Identity
grep -nE "get_name|get_title|get_icon|get_categories|get_keywords" widgets/${slug}.php | head

# Asset deps
grep -nE "get_style_depends|get_script_depends" -A5 widgets/${slug}.php

# Control sections
grep -cE "start_controls_section" widgets/${slug}.php

# Hooks fired
grep -nE "do_action|apply_filters" widgets/${slug}.php | head -20

# JS handler
head -40 assets/frontend/js/${slug//-/_}.js   # naming varies - check
```

Paste results into the template above, fill in words, done.
