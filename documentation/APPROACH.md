---
topic: Reverse-Engineering Approach & Strategy
last_synced: 2026-11-20
---

# Reverse-Engineering Approach & Strategy

Repeatable method for documenting any WordPress theme or plugin in `workspace/test-subjects/` so its features can be extracted on demand. Builds on [`playbooks/01-reverse-engineer-audit.md`](file:///root/Desktop/learn/BAse/playbooks/01-reverse-engineer-audit.md) with concrete commands and a fixed output shape.

## 0. Principles

1. **Read-only.** Never touch the source tree. All output lands in `documentation/`.
2. **Map first, extract later.** Produce a complete index of where every feature lives. Extraction is cheap once the map exists.
3. **Fixed shape.** Every subject gets the same five-file layout, so cross-referencing stays mechanical.
4. **Evidence over assumption.** Every file path in the docs comes from a grep or `find`; nothing is remembered.
5. **Scope ruthlessly.** The deliverable is an extraction map, not a line-by-line audit. Line-by-line only comes when a specific feature is being lifted.

## 1. Preflight checklist (per subject, ~5 minutes)

Before writing any doc, run this recon:

```bash
cd workspace/test-subjects/<type>/<slug>/<inner>   # the real code dir (watch for zip-nested layout)

# File topology
find . -type f -name "*.php" | wc -l
find . -type f -name "*.js" | wc -l
find . -type f \( -name "*.css" -o -name "*.scss" \) | wc -l
find . -maxdepth 3 -type d | sort

# Plugin/theme header
head -30 <main-file>.php       # or style.css for themes

# Entry point loader
grep -nE "^require|require_once" <main-file>.php | head -80
```

Capture: **version, PHP minimum, WP minimum, vendor, license, text-domain, prefix, headline stats** (PHP/JS/CSS counts).

## 2. Audit sweep — the canonical greps

Run all of these from the inner code directory. Each output feeds one doc section.

### 2.1 Entry topology
```bash
find . -maxdepth 3 -type f \( -name "functions.php" -o -name "*.php" \) \
  -not -path "*/vendor/*" -not -path "*/node_modules/*" -not -path "*/languages/*"
```

### 2.2 Hook registrations (where it listens)
```bash
grep -rnE "add_action\s*\(|add_filter\s*\(" --include="*.php" \
  --exclude-dir={vendor,node_modules,languages} . | wc -l
```

### 2.3 Hook exports (its own public API — what third parties can plug into)
```bash
# Count + unique names
grep -rhoE "do_action\s*\(\s*['\"][^'\"\$]+['\"]" --include="*.php" . | sort -u > /tmp/hooks.txt
grep -rhoE "apply_filters\s*\(\s*['\"][^'\"\$]+['\"]" --include="*.php" . | sort -u > /tmp/filters.txt
wc -l /tmp/hooks.txt /tmp/filters.txt
```

### 2.4 Shortcodes
```bash
grep -rnE "add_shortcode\s*\(" --include="*.php" .
```

### 2.5 Enqueued assets
```bash
grep -rnE "wp_(enqueue|register)_(script|style)" --include="*.php" .
```

### 2.6 Third-party libraries bundled
```bash
grep -rniE "swiper|slick|owlcarousel|gsap|lenis|lottie|isotope|lightbox|bootstrap|fontawesome|remixicon|chart\.js" \
  --include="*.php" --include="*.js" --exclude-dir={vendor,node_modules} .
```

### 2.7 Data model (CPTs, taxonomies, meta)
```bash
grep -rnE "register_post_type\s*\(|register_taxonomy\s*\(" --include="*.php" .
grep -rnE "get_post_meta\s*\(|update_post_meta\s*\(" --include="*.php" . | wc -l
```

### 2.8 REST / abilities surface
```bash
grep -rnE "register_rest_route|register_rest_field|wp_register_ability" --include="*.php" .
```

### 2.9 Elementor registrations (for addon plugins)
```bash
grep -rnE "elementor/widgets/register|elementor/elements/categories_registered|elementor/dynamic_tags/register|elementor/controls/register" --include="*.php" .
```

### 2.10 Proprietary frameworks (lock-in to decouple)
```bash
grep -rniE "Redux::|kirki|fw_get_db|cs_get_option|get_theme_mod" --include="*.php" .
```

### 2.11 Text domain (prefix verification)
```bash
grep -rhoE "__\(\s*[^,]+,\s*['\"][^'\"]+['\"]" --include="*.php" . | \
  grep -oE "['\"][^'\"]+['\"]\s*$" | sort -u
```

## 3. Output layout (fixed per subject)

```
documentation/<themes|plugins>/<slug>/
├── 01-overview.md          Header, deps, scope, headline stats, licensing
├── 02-architecture.md      Bootstrap flow + directory map + lifecycle hooks
├── 03-hooks-filters.md     Public API surface (grouped by domain)
├── 04-feature-index.md     Every feature with file paths + deps — THE EXTRACTION MAP
├── 05-extraction-guide.md  Per-feature recipes for lifting into companion plugin
└── widgets/                (plugins only) one page per widget
    └── <widget-slug>.md
```

### What each file holds

- **01-overview.md** — a reader's briefing. Headers, license, PHP/WP minimums, bundled libs, headline counts, top-level map.
- **02-architecture.md** — how the code boots. Entry file → autoloader → core class → registered hooks → module loads. Draw the load order.
- **03-hooks-filters.md** — the public API. Grouped by domain (markup, dynamic-css, customizer, builder, compatibility). Each entry: hook name, source file and line, purpose in one line.
- **04-feature-index.md** — a table with: *Feature → Files → PHP class/function → CSS selectors → JS handles → libraries → compat deps → extraction recipe pointer*. This is the file you open two months later when you need "that one portfolio carousel".
- **05-extraction-guide.md** — per-feature cookbooks. Each one applies playbooks 02–04. Explicit decoupling moves (what to replace), scoped CSS rules, modern PHP refactors, re-prefixed slugs.
- **widgets/<slug>.md** (plugins only) — one Elementor widget per file. Name, source files, controls list (grouped by tab), assets, JS handler summary, dependencies, extraction notes.

## 4. Writing order per subject

1. Recon → `01-overview.md`.
2. Trace `functions.php` / plugin main file → `02-architecture.md`.
3. Grep public API → `03-hooks-filters.md`.
4. Walk the feature tree (modules / addons / widgets / dynamic-css) → `04-feature-index.md`.
5. Pick 2–3 representative features and write full recipes → `05-extraction-guide.md`.
6. For plugins: deep-dive 2–3 widgets as templates → `widgets/*.md`. Fill the rest on demand.

Stop at step 6. Remaining widgets stay as index entries until someone needs them extracted — that's the Ponytail line between useful and wasted work.

## 5. Component Manifest template (for a lift)

When extraction is actually requested, fill this template inside `05-extraction-guide.md` or as a per-component addendum (copied straight from `playbooks/01-reverse-engineer-audit.md`):

```markdown
# Component Manifest: <Component Name>

### Source Provenance
- Subject: <slug> v<version>
- Files: <paths>

### Data & Model Dependencies
- Post types / taxonomies / meta keys / option keys

### Styling & CSS Footprint
- Root wrapper selector, CSS variables required, breakpoints, icon fonts

### JavaScript & Interactivity
- Libraries + handles, init trigger, re-wire target

### Licensing & Asset Review
- License compatibility, bundled media (strip!), proprietary framework calls
```

## 6. Red flags during audit

Call these out explicitly in `01-overview.md`:

| Flag | What it means | Action |
| :--- | :--- | :--- |
| `Redux::`, `kirki`, `fw_get_db`, `cs_get_option` | Vendor option framework lock-in | Must be replaced — see playbook 02 |
| `get_theme_mod` with hardcoded defaults | Customizer coupling | Replace with widget controls on extraction |
| `$(document).ready` for Elementor widgets | Breaks in Elementor editor | Re-wire to `handlers.Base` + `elementor/frontend/init` |
| Legacy `widgets_registered` / `register_widget_type` | Deprecated Elementor API | Upgrade to `elementor/widgets/register` + `$widgets_manager->register()` |
| `Scheme_Color`, `Scheme_Typography` | Elementor 2.x API, removed | Group Controls or Kit Globals |
| Bundled jQuery + stock plugins | Likely causes conflicts/duplication | Dequeue on extract |
| Commercial photography in `assets/images/` | License risk | Replace with placeholders on extract |
| No PHP 8 null-safety (`??`, `?->`) | Runtime failures on modern stacks | Add on modernization |

## 7. When to stop

Stop writing docs for a subject when:
1. Every feature has a row in `04-feature-index.md` with its file paths.
2. The public hook API is listed in `03-hooks-filters.md` (grouped, not exhaustive — just findable).
3. Two or three representative extraction recipes are in `05-extraction-guide.md`.
4. For addon plugins, two or three widget pages exist as templates in `widgets/`.

Further detail is on-demand. The map is enough to find anything; the extraction recipe for a specific feature is cheap to write when that feature is wanted.

## 8. What NOT to do

- **Don't paraphrase the whole codebase.** Point at files, don't retype them.
- **Don't invent features.** Everything claimed must come from a grep hit.
- **Don't normalize prefixes in the docs.** Record the vendor's actual prefix (`astra_*`, `premium_*`) so later greps still work. Re-prefixing is an extraction step, not a documentation step.
- **Don't deep-dive 37 widgets up-front.** Two or three is enough to establish the pattern. Deliver the rest on demand.
- **Don't commit `.zip` archives or vendor source into git.** The docs reference `test-subjects/`; the subjects themselves are gitignored.

## 9. Toolchain

- `grep`, `find`, `wc`, `sort -u` — recon.
- No execution. No PHP parse. No sandbox. Everything in these docs is derivable by re-running the greps in §2.
- If a question requires behavior (runtime), stop and spin up `wp-env` or `wp-playground`. Those are not configured in this workspace yet.

## 10. Tracking status

Keep the subject's status up to date in [`README.md`](README.md)'s table:

| Status | Meaning |
| :--- | :--- |
| 🟢 Mapped | All five base docs complete, feature index covers the whole surface |
| 🔄 In progress | Some of the five base docs started |
| ⚫ Queued | Subject identified, docs not started |
| 🟣 Deep-dived | Specific features have full Component Manifests in `05-extraction-guide.md` |
