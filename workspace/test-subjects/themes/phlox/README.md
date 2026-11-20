# Test Subject: Phlox Theme

- **Category:** Classic WordPress Theme
- **Slug:** `phlox`
- **Source Archive:** [`phlox.zip`](file:///root/Desktop/learn/BAse/workspace/test-subjects/themes/phlox/phlox.zip)
- **Extracted Directory:** [`phlox/`](file:///root/Desktop/learn/BAse/workspace/test-subjects/themes/phlox/phlox)
- **Authorization & Rights:** [OWNER_AUTHORIZATION_AND_LICENSING.md](file:///root/Desktop/learn/BAse/workspace/test-subjects/OWNER_AUTHORIZATION_AND_LICENSING.md)

---

## 1. Overview & Architecture

The open-source foundation of Phlox Pro. Built on the Auxin framework, rich in custom element classes, portfolio templates, and advanced styling assets.

### Key Entry Points:
- Style Definition: [`style.css`](file:///root/Desktop/learn/BAse/workspace/test-subjects/themes/phlox/phlox/style.css)
- Theme Bootstrap: [`functions.php`](file:///root/Desktop/learn/BAse/workspace/test-subjects/themes/phlox/phlox/functions.php)

---

## 2. Reverse-Engineering & Extraction Value

1. **Theme Locations Audit**: Inspect how `phlox` handles `header.php`, `footer.php`, and `single.php` when Elementor Theme Builder is active vs inactive.
2. **Layout Decoupling**: Follow [`playbooks/02-extract-isolate-merge.md`](file:///root/Desktop/learn/BAse/playbooks/02-extract-isolate-merge.md) to isolate reusable header/footer layouts or CSS tokens.
3. **Asset Deduplication**: Check enqueued assets in `functions.php` or `inc/` to identify reusable CSS reset and utility rules.
