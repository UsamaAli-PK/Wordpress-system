# Test Subject: Blocksy Theme

- **Category:** Classic WordPress Theme
- **Slug:** `blocksy`
- **Source Archive:** [`blocksy.zip`](file:///root/Desktop/learn/BAse/workspace/test-subjects/themes/blocksy/blocksy.zip)
- **Extracted Directory:** [`blocksy/`](file:///root/Desktop/learn/BAse/workspace/test-subjects/themes/blocksy/blocksy)
- **Authorization & Rights:** [OWNER_AUTHORIZATION_AND_LICENSING.md](file:///root/Desktop/learn/BAse/workspace/test-subjects/OWNER_AUTHORIZATION_AND_LICENSING.md)

---

## 1. Overview & Architecture

Ultra-modern classic/hybrid architecture with fast PHP rendering, CSS variable system, and deep Elementor container compatibility.

### Key Entry Points:
- Style Definition: [`style.css`](file:///root/Desktop/learn/BAse/workspace/test-subjects/themes/blocksy/blocksy/style.css)
- Theme Bootstrap: [`functions.php`](file:///root/Desktop/learn/BAse/workspace/test-subjects/themes/blocksy/blocksy/functions.php)

---

## 2. Reverse-Engineering & Extraction Value

1. **Theme Locations Audit**: Inspect how `blocksy` handles `header.php`, `footer.php`, and `single.php` when Elementor Theme Builder is active vs inactive.
2. **Layout Decoupling**: Follow [`playbooks/02-extract-isolate-merge.md`](file:///root/Desktop/learn/BAse/playbooks/02-extract-isolate-merge.md) to isolate reusable header/footer layouts or CSS tokens.
3. **Asset Deduplication**: Check enqueued assets in `functions.php` or `inc/` to identify reusable CSS reset and utility rules.
