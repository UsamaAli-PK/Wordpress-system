---
topic: Reverse-Engineering & Codebase Audit Protocol
last_synced: 2026-11-20
---

# Reverse-Engineering & Codebase Audit Protocol

## 1. Golden Rule: Read-Only Reconnaissance

Before modifying, copying, or extracting any component from a third-party theme or plugin:
1. **Never mutate the source repository.** Treat the target as an immutable reference.
2. **Conduct static reconnaissance first.** Trace all implicit dependencies (CSS classes, custom post meta, options tables, global JS variables).
3. **Draft a Component Manifest** before extracting a single line of code.

---

## 2. Automated Inspection & Search Patterns

Run these shell inspection commands from the target theme or plugin root directory:

### Step 1: Map Entry Points & File Topology
```bash
# Locate theme/plugin architecture entry points
find . -maxdepth 3 -type f \( -name "functions.php" -o -name "*.php" \) \
  -not -path "*/vendor/*" -not -path "*/node_modules/*"
```

### Step 2: Discover Registered Hooks & Extensions
```bash
# Locate all actions, filters, shortcodes, and widget registrations
grep -rnE "add_action\s*\(|add_filter\s*\(|add_shortcode\s*\(" --exclude-dir={vendor,node_modules,languages} .

# Search for Elementor widget registrations (modern & legacy)
grep -rnE "elementor/widgets/register|widgets_registered|register_widget_type" --exclude-dir={vendor,node_modules} .
```

### Step 3: Discover Enqueued Assets & Third-Party Libraries
```bash
# Locate enqueued CSS/JS handles and file paths
grep -rnE "wp_enqueue_script|wp_enqueue_style|wp_register_script|wp_register_style" --exclude-dir={vendor,node_modules} .

# Detect third-party libraries (Swiper, Slick, GSAP, Bootstrap, FontAwesome)
grep -rniE "swiper|slick|owlcarousel|gsap|isotope|lightbox|bootstrap|fontawesome|remixicon" --exclude-dir={vendor,node_modules} .
```

### Step 4: Map Custom Post Types, Taxonomies & Meta Keys
```bash
# Detect CPTs and Taxonomies
grep -rnE "register_post_type\s*\(|register_taxonomy\s*\(" --exclude-dir={vendor,node_modules} .

# Extract custom meta keys and ACF calls
grep -rnE "get_post_meta\s*\(|get_field\s*\(|get_sub_field\s*\(" --exclude-dir={vendor,node_modules} .
```

### Step 5: Detect Proprietary Framework Calls
```bash
# Detect legacy theme options (Redux, Kirki, Unyson, Titan, Codestar)
grep -rniE "Redux::|kirki|fw_get_db|cs_get_option|get_theme_mod" --exclude-dir={vendor,node_modules} .
```

---

## 3. Required Component Manifest Template

For every extracted section or component, complete this structured audit table:

```markdown
# Component Manifest: [Component Name]

### 1. Source Provenance
- Source Theme/Plugin: `[Theme/Plugin Name & Version]`
- Source Files:
  - PHP Template: `template-parts/section-[name].php`
  - Function Logic: `inc/helpers.php` (lines 45-82)
  - Stylesheet: `assets/css/[name].css`
  - Script: `assets/js/[name].js`

### 2. Data & Model Dependencies
- Post Types: `post` | `agency_portfolio`
- Taxonomies: `category` | `project_type`
- Custom Fields / Meta Keys:
  - `_project_client` (string)
  - `_project_gallery` (array of attachment IDs)
- Global Options / Settings: `get_theme_mod('accent_color', '#0f172a')`

### 3. Styling & CSS Footprint
- Root Wrapper Selector: `.theme-project-carousel`
- CSS Variables Required:
  - `--theme-primary`
  - `--theme-radius`
- Media Query Breakpoints: `1024px` (tablet), `767px` (mobile)
- External Icon Fonts / Webfonts: `FontAwesome 6`, `Inter`

### 4. JavaScript & Interactivity
- Library Dependencies: `swiper@11` (Handle: `swiper-bundle`)
- Init Event Trigger: `$(document).ready()` (MUST be re-wired to `elementor/frontend/init`)
- Global JS Variables: `window.themeConfig.ajaxUrl`

### 5. Licensing & Asset Review
- License: GPL-2.0-or-later compatible? [Yes / No]
- Bundled Media/Images: Placeholders required (do NOT retain client/commercial photos).
- Proprietary Framework Calls: `Redux::getOption()` found (must replace with widget controls).
```
