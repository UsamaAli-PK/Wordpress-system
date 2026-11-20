---
topic: Elementor MCP Architecture and Operational Safety Workflow
source_url: https://elementor.com/blog/elementor-mcp-launch/
last_synced: 2026-11-20
---

# Elementor MCP Architecture & Operational Workflow

## 1. Connecting Elementor MCP

Modern AI agents can interface directly with Elementor layouts through two primary MCP implementations:

| Solution | Provider | Architecture | Best For |
| :--- | :--- | :--- | :--- |
| **Official Elementor MCP** | Elementor Core/Pro (3.25+) | Native WP Admin connector under *Elementor > Elementor MCP* | Official Elementor Pro Theme Builder & cloud design system workflows |
| **EMCP Tools (`msrbuilds`)** | Open Source / WordPress.org | Built upon official `WordPress/mcp-adapter` and Abilities API | Comprehensive local & staging agent tooling (240+ abilities, multi-builder support) |

### Client Connection Profile (`.mcp.json`)

```json
{
  "mcpServers": {
    "elementor-mcp": {
      "command": "npx",
      "args": ["-y", "@automattic/mcp-wordpress-remote@latest"],
      "env": {
        "WP_API_URL": "https://example.com/wp-json/mcp/elementor-mcp-server",
        "WP_API_USERNAME": "admin",
        "WP_API_PASSWORD": "xxxx xxxx xxxx xxxx"
      }
    }
  }
}
```

---

## 2. Inspecting Global Kit Tokens

Before proposing or generating any section, the agent must query the active Kit design tokens:

1. **Locate Active Kit:** Fetch option `elementor_active_kit`.
2. **Retrieve System Colors & Typography:** Inspect kit post metadata:
   - System Colors: `primary`, `secondary`, `text`, `accent`.
   - Global Fonts: Font families, weights, and line heights.
3. **Reference Globals:** Always link element settings via `__globals__` references (e.g. `globals/colors?id=primary`) rather than hardcoding static hex codes.

---

## 3. Native Elementor Widgets vs. The Raw HTML Antipattern

> **Strict Rule:** Never dump raw HTML strings or unstyled markup into generic Elementor `html` widgets. 
> 
> Generating unstyled `<div class="card">` blocks inside an HTML widget breaks the visual editor, strips user design controls, and circumvents the site design system.

### The Correct Construction Approach:
- **Containers:** Create native flexbox/grid containers (`elType: "container"`).
- **Headings & Copy:** Use `widgetType: "heading"` and `widgetType: "text-editor"`.
- **Media & Buttons:** Use `widgetType: "image"` and `widgetType: "button"`.
- **Custom Components:** Use our companion plugin widgets (`widgetType: "agency_portfolio_grid"`, etc.).

---

## 4. The 3-Phase Operational Safety Protocol

To guarantee zero regression when managing production sites via MCP:

```
[Phase 1: Read-Only Reconnaissance]
  → Inspect existing page layout tree (_elementor_data)
  → Inspect active Kit design tokens & custom CSS
       ↓
[Phase 2: Atomic Draft Generation]
  → Construct structured Container JSON
  → Apply global tokens via __globals__
  → Save post with post_status = 'draft' (never publish unreviewed)
       ↓
[Phase 3: Visual & DOM Verification]
  → Check live preview endpoint
  → Verify zero CSS specificity conflicts
  → Human sign-off before publishing
```

1. **Read-Only First:** Run read-only audit tools (`get_post`, `get_elementor_data`, `get_active_kit`) before modifying any layout.
2. **Drafts Over Live Edits:** Save modifications as drafts. Elementor MCP detects concurrent edits and prompts users to resolve conflicts cleanly.
3. **Post-Save Visual Check:** Confirm that containers hydrate correctly and that JavaScript frontend handlers mount without console errors.
