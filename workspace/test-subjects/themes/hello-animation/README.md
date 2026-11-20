# Test Subject: Hello-Animation Theme

- **Category:** Classic WordPress Theme
- **Slug:** `hello-animation`
- **Source Archive:** [`hello-animation.zip`](file:///root/Desktop/learn/BAse/workspace/test-subjects/themes/hello-animation/hello-animation.zip)
- **Extracted Directory:** [`hello-animation/`](file:///root/Desktop/learn/BAse/workspace/test-subjects/themes/hello-animation/hello-animation)
- **Authorization & Rights:** [OWNER_AUTHORIZATION_AND_LICENSING.md](file:///root/Desktop/learn/BAse/workspace/test-subjects/OWNER_AUTHORIZATION_AND_LICENSING.md)

---

## 1. Overview & Architecture

Animation-specialized Hello derivative. Contains motion utility classes and responsive container presets.

### Key Entry Points:
- Style Definition: [`style.css`](file:///root/Desktop/learn/BAse/workspace/test-subjects/themes/hello-animation/hello-animation/style.css)
- Theme Bootstrap: [`functions.php`](file:///root/Desktop/learn/BAse/workspace/test-subjects/themes/hello-animation/hello-animation/functions.php)

---

## 2. Reverse-Engineering & Extraction Value

1. **Theme Locations Audit**: Inspect how `hello-animation` handles `header.php`, `footer.php`, and `single.php` when Elementor Theme Builder is active vs inactive.
2. **Layout Decoupling**: Follow [`playbooks/02-extract-isolate-merge.md`](file:///root/Desktop/learn/BAse/playbooks/02-extract-isolate-merge.md) to isolate reusable header/footer layouts or CSS tokens.
3. **Asset Deduplication**: Check enqueued assets in `functions.php` or `inc/` to identify reusable CSS reset and utility rules.
