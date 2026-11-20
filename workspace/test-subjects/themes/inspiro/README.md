# Test Subject: Inspiro Theme

- **Category:** Classic WordPress Theme
- **Slug:** `inspiro`
- **Source Archive:** [`inspiro.zip`](file:///root/Desktop/learn/BAse/workspace/test-subjects/themes/inspiro/inspiro.zip)
- **Extracted Directory:** [`inspiro/`](file:///root/Desktop/learn/BAse/workspace/test-subjects/themes/inspiro/inspiro)
- **Authorization & Rights:** [OWNER_AUTHORIZATION_AND_LICENSING.md](file:///root/Desktop/learn/BAse/workspace/test-subjects/OWNER_AUTHORIZATION_AND_LICENSING.md)

---

## 1. Overview & Architecture

Portfolio and video-focused theme. Great reference for background video heroes, responsive media containers, and fullscreen overlays.

### Key Entry Points:
- Style Definition: [`style.css`](file:///root/Desktop/learn/BAse/workspace/test-subjects/themes/inspiro/inspiro/style.css)
- Theme Bootstrap: [`functions.php`](file:///root/Desktop/learn/BAse/workspace/test-subjects/themes/inspiro/inspiro/functions.php)

---

## 2. Reverse-Engineering & Extraction Value

1. **Theme Locations Audit**: Inspect how `inspiro` handles `header.php`, `footer.php`, and `single.php` when Elementor Theme Builder is active vs inactive.
2. **Layout Decoupling**: Follow [`playbooks/02-extract-isolate-merge.md`](file:///root/Desktop/learn/BAse/playbooks/02-extract-isolate-merge.md) to isolate reusable header/footer layouts or CSS tokens.
3. **Asset Deduplication**: Check enqueued assets in `functions.php` or `inc/` to identify reusable CSS reset and utility rules.
