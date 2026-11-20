# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this repo is

A Markdown-only knowledge base for WordPress + Elementor development. There is no build, lint, or test tooling and no application code. `workspace/` (active builds) and `boilerplates/` (starters, status "Skeleton") hold only a README for now. `INDEX.md` is the master routing table. It is imported below, so use it to find the right doc before answering from memory. The docs are the source of truth for house patterns.

@INDEX.md

## Layout

`INDEX.md` numbers its sections, and the number is not always a folder prefix:

- **01** `rules/` — binding constraints (`architecture-and-boundaries.md`). Read before giving any WordPress advice.
- **02–05** `docs/02-plugins-and-data`, `03-elementor-theme`, `04-elementor-widgets`, `05-mcp-and-cli` — atomic reference docs. `docs/` has no `01-` folder.
- `playbooks/01–04` — ordered procedures: audit → extract/isolate → section-to-widget → assemble theme + companion plugin. Each builds on the previous one.
- `INDEX.md` also carries a keep/skip checklist of upstream `WordPress/agent-skills`.

## Rules (from `rules/architecture-and-boundaries.md`)

- **Local rules beat upstream skills.** The stack is a Classic PHP theme (`header.php`, `footer.php`, `singular.php`, `archive.php`) plus Elementor Theme Locations, `Widget_Base` companion widgets, and Flexbox/Grid containers. Block themes, FSE, `theme.json`, and Gutenberg-only layouts are excluded. `INDEX.md` marks `wp-block-development`, `wp-block-themes`, `wp-interactivity-api`, and `wpds` as **Skip**. Skills it marks Recommended (`wp-plugin-development`, `wp-wpcli-and-ops`, `wp-rest-api`, `wordpress-router`, `wp-project-triage`, and others) are fine to use.
- **Draft-first:** content written through MCP tools uses `post_status: 'draft'` until a human verifies it.
- **Secrets:** never commit `.mcp.json`, `.env*`, API keys, or application passwords. `.gitignore` must keep covering `.mcp.json`, `*.env`, `*.env.*` (except `.env.example`), `*.key`, `*.pem`, and `application-passwords.txt`. Application passwords are scoped to one agent or workflow with minimal capabilities.

## Conventions the docs and playbooks enforce

- **Data lives in the companion plugin, presentation in the theme.** CPTs, taxonomies, shortcodes, and widgets must survive a theme switch. Rewrite flushes happen only on activation and deactivation. `uninstall.php` does the cleanup.
- **Zero degradation without Elementor.** Theme templates call `elementor_theme_do_location()` behind a `function_exists` guard and fall back to native PHP. Theme CSS variables (`--theme-*`) wrap Elementor Kit variables with literal fallbacks, e.g. `var(--e-global-color-primary, #0f172a)`.
- **Never use raw HTML widgets in Elementor.** Build with native containers and widgets, and bind colors and fonts through `__globals__` (`globals/colors?id=primary`) instead of hex codes. MCP work follows the 3 phases: read-only recon, atomic draft, then visual verification with human sign-off.
- **Extraction (playbooks 01–03):** treat the source as read-only, write a Component Manifest first, and keep the DOM unchanged on the first pass (the DOM Parity Rule). Replace Redux/Kirki/`get_theme_mod` calls with widget controls. Re-prefix everything (PSR-4 namespace, function names, CSS classes, text domain). Re-wire `$(document).ready` to `elementorModules.frontend.handlers.Base` and `elementor/frontend/init`.
- **Modern APIs only:** `elementor/widgets/register` and `$widgets_manager->register()`, not the legacy `widgets_registered` or `register_widget_type`. Use `declare(strict_types=1)` and escape output in `render()`.
- **Target stack:** PHP 8.1+, WP 6.4+, Elementor container JSON schema v0.4. The docs use `agency` / `Agency\Companion` / `agency-companion` as placeholder slugs. Swap them for the real project's slugs.
- Pass the preflight gate table in `playbooks/04-build-unified-theme.md` (`php -l`, Elementor-inactive test, and the others) before calling a build done.

## Editing the knowledge base

- Front matter is `topic:` and `last_synced:` (currently `2026-11-20`) on every file. The `docs/` files also carry `source_url:`. Bump `last_synced` when you edit a file.
- Links are absolute `file:///root/Desktop/learn/BAse/<path>` URLs, as in the existing files.
- Adding a file means updating its folder's `README.md` (`rules/`, `docs/`, `playbooks/`) and the matching section of `INDEX.md`. Keep the Status column in the `INDEX.md` table accurate.
