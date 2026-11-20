---
topic: Global Styles, Elementor Kits, and CSS Variable Sync
source_url: https://developers.elementor.com/docs/data-structure/global-styles
last_synced: 2026-11-20
---

# Global Styles, Elementor Kits, and CSS Synchronization

## 1. Where Global Styles Live (The Kit Document)

In Elementor, global design tokens (colors, typography, container widths, breakpoints) are **not** stored within individual page layouts. They reside inside an active **Kit**:

- Stored in `wp_posts` as a post with `post_type = 'elementor_library'`.
- Identified by post meta `_elementor_template_type = 'kit'`.
- The current active kit ID is registered in WordPress options table under option name: `elementor_active_kit`.

---

## 2. Global References in JSON (`__globals__`)

When an Elementor widget or container control binds to a global style, it stores a reference string rather than hardcoding static hex codes or font names.

### Reference Syntax

```text
globals/<type>?id=<global-id>
```

- `<type>`: `colors` or `typography`.
- `<global-id>`:
  - System Defaults: `primary`, `secondary`, `text`, `accent`.
  - Custom User Styles: Alphanumeric hash (e.g., `8e2c1a4`, `3d8a9b1`).

### Example Widget JSON with Global Bindings

```json
{
  "id": "h1029384",
  "elType": "widget",
  "widgetType": "heading",
  "isInner": false,
  "settings": {
    "title": "Clean Global Design System",
    "__globals__": {
      "title_color": "globals/colors?id=primary",
      "typography_typography": "globals/typography?id=primary"
    }
  },
  "elements": []
}
```

At render time, Elementor's CSS generator replaces the global reference with the CSS custom property corresponding to that kit token.

---

## 3. Elementor Generated CSS Custom Properties

Elementor compiles Kit settings into standardized CSS variables attached to `:root` or body scopes:

```css
:root {
  /* Global Colors */
  --e-global-color-primary: #0f172a;
  --e-global-color-secondary: #475569;
  --e-global-color-text: #334155;
  --e-global-color-accent: #2563eb;

  /* Custom Kit Color */
  --e-global-color-8e2c1a4: #10b981;

  /* Global Typography */
  --e-global-typography-primary-font-family: "Inter", sans-serif;
  --e-global-typography-primary-font-weight: 700;
  --e-global-typography-secondary-font-family: "Inter", sans-serif;
  --e-global-typography-text-font-family: "Inter", sans-serif;
  --e-global-typography-accent-font-family: "Inter", sans-serif;
}
```

---

## 4. Harmonizing Theme CSS with Elementor Site Settings

To prevent CSS specificity conflicts and `!important` wars between custom theme styles and Elementor, declare your theme variables with CSS fallbacks mapped directly to Elementor's custom properties:

```css
/* theme-variables.css */
:root {
  /* Map theme design tokens to Elementor globals with fallback values */
  --theme-color-primary: var(--e-global-color-primary, #0f172a);
  --theme-color-secondary: var(--e-global-color-secondary, #475569);
  --theme-color-text: var(--e-global-color-text, #334155);
  --theme-color-accent: var(--e-global-color-accent, #2563eb);

  --theme-font-primary: var(--e-global-typography-primary-font-family, system-ui, sans-serif);
  --theme-font-text: var(--e-global-typography-text-font-family, system-ui, sans-serif);

  --theme-container-max: var(--e-container-default-width, 1200px);
}

/* Use semantic theme variables across native theme components */
.site-header {
  background-color: #ffffff;
  color: var(--theme-color-text);
  font-family: var(--theme-font-primary);
}

.button-primary {
  background-color: var(--theme-color-accent);
  color: #ffffff;
  font-family: var(--theme-font-primary);
}
```

### Architectural Benefits:
1. **Unified Design System:** Updating Site Settings in Elementor immediately updates native theme parts (headers, footers, WooCommerce elements).
2. **Zero Invalidation on Deactivation:** If Elementor is deactivated, the native fallback values (`#0f172a`, `system-ui`) kick in immediately without broken styles.
3. **No Specificity Battles:** No overriding of Elementor widget selectors or aggressive specificity chains.
