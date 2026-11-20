---
topic: Reverse-Engineering Documentation Index
last_synced: 2026-11-20
---

# Reverse-Engineering Documentation

Developer-facing, extraction-ready documentation for third-party WordPress themes and Elementor plugins stored in [`workspace/test-subjects/`](file:///root/Desktop/learn/BAse/workspace/test-subjects/).

Methodology comes from [`playbooks/01-reverse-engineer-audit.md`](file:///root/Desktop/learn/BAse/playbooks/01-reverse-engineer-audit.md) and the concrete reproducible strategy in [`APPROACH.md`](APPROACH.md). Every subject is treated as read-only; nothing in `test-subjects/` is modified. All findings land here.

## Subjects

| Subject | Type | Version | Vendor | License | Docs |
| :--- | :--- | :--- | :--- | :--- | :--- |
| [Astra](themes/astra/) | Classic + builder theme | 4.14.3 | Brainstorm Force | GPL-2.0+ | 🟢 Mapped |
| [Premium Addons for Elementor](plugins/premium-addons-for-elementor/) | Elementor addon plugin | 4.11.121 | Leap13 | GPL-3.0 | 🟢 Mapped · 2/37 widgets deep-dived |

## Per-subject doc layout

Every subject folder contains the same shape:

```
documentation/<type>/<slug>/
├── 01-overview.md          # Header, versioning, license, dependencies, scope
├── 02-architecture.md      # Bootstrap flow, autoloader, directory map, lifecycle hooks
├── 03-hooks-filters.md     # Public API surface — what third parties can hook into
├── 04-feature-index.md     # Every feature / widget / module with file paths & entry points
├── 05-extraction-guide.md  # How to lift a specific feature into our own companion plugin
└── widgets/                # (plugins only) One page per widget
    └── <widget-slug>.md
```

`04-feature-index.md` is the **extraction map**. For any feature you need later, look it up there to find:
- the feature's name and purpose
- every file involved (PHP, CSS, JS, templates)
- its hooks, controls, and data dependencies
- its third-party library dependencies
- a one-line pointer to the matching extraction recipe in `05-extraction-guide.md`

## How to extract a feature later

1. Open the subject's `04-feature-index.md`, find the feature row.
2. Follow the pointer into `05-extraction-guide.md` for the step-by-step recipe.
3. Apply the canonical pipeline from [`playbooks/02-extract-isolate-merge.md`](file:///root/Desktop/learn/BAse/playbooks/02-extract-isolate-merge.md): decouple vendor frameworks, re-prefix namespaces/CSS/text-domain, dedup assets, modernize PHP.
4. If it's an Elementor widget, apply [`playbooks/03-section-to-elementor-widget.md`](file:///root/Desktop/learn/BAse/playbooks/03-section-to-elementor-widget.md) (DOM Parity Rule, map controls, re-wire JS to handler).
5. Verify against [`playbooks/04-build-unified-theme.md`](file:///root/Desktop/learn/BAse/playbooks/04-build-unified-theme.md)'s preflight gate.

## Authorization

See [`workspace/test-subjects/OWNER_AUTHORIZATION_AND_LICENSING.md`](file:///root/Desktop/learn/BAse/workspace/test-subjects/OWNER_AUTHORIZATION_AND_LICENSING.md). Static analysis, extraction, modernization, and commercial reuse are explicitly authorized by the workspace owner across all test subjects.
