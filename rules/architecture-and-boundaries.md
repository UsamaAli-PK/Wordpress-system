---
topic: Workspace Architecture, Boundaries & Precedence Rules
last_synced: 2026-11-20
---

# Architecture, Boundaries & Precedence Rules

## 1. Skill Precedence Rule (Local Overrides Upstream)

Whenever external or upstream WordPress agent skills (such as `WordPress/agent-skills`) provide recommendations, the following local hierarchy is strictly enforced:

> **Precedence Invariant:** Local `rules/` and workspace documentation ALWAYS take absolute precedence over upstream skills whenever an upstream skill recommends Full Site Editing (FSE), `theme.json`, or Block Editor patterns over **Classic PHP Theme Development + Elementor Theme Locations**.

- **Allowed:** Classic PHP templates (`header.php`, `footer.php`, `singular.php`, `archive.php`), Elementor Theme Locations API, `Widget_Base` companion widgets, and Flexbox/Grid Containers.
- **Excluded by Local Mandate:** Block themes (`theme.json`), FSE templates, and Gutenberg block-only paradigms for page layouts.

---

## 2. Security & Credentials Boundaries

1. **Never Commit Secrets:** Never commit `.mcp.json`, `.env`, API keys, or WordPress Application Passwords to Git version control.
2. **Git Ignore Invariant:** `.mcp.json` and local credential files must be listed in `.gitignore`.
3. **Application Password Scoping:** Always generate application passwords dedicated to specific AI agents or workflows with the minimum necessary capabilities.
4. **Draft-First Rule:** When writing layouts or posts via MCP tools, always write with `post_status: 'draft'` to require visual verification and human approval before publishing.
