# Test Subject: Hello-Elementor Theme

- **Category:** Classic WordPress Theme
- **Slug:** `hello-elementor`
- **Source Archive:** [`hello-elementor.zip`](file:///root/Desktop/learn/BAse/workspace/test-subjects/themes/hello-elementor/hello-elementor.zip)
- **Extracted Directory:** [`hello-elementor/`](file:///root/Desktop/learn/BAse/workspace/test-subjects/themes/hello-elementor/hello-elementor)
- **Authorization & Rights:** [OWNER_AUTHORIZATION_AND_LICENSING.md](file:///root/Desktop/learn/BAse/workspace/test-subjects/OWNER_AUTHORIZATION_AND_LICENSING.md)

---

## 1. Overview & Architecture

Canonical minimal theme created by Elementor. Zero bloat, purest reference for Theme Locations API (header, footer, single, archive) and reset styles.

### Key Entry Points:
- Style Definition: [`style.css`](file:///root/Desktop/learn/BAse/workspace/test-subjects/themes/hello-elementor/hello-elementor/style.css)
- Theme Bootstrap: [`functions.php`](file:///root/Desktop/learn/BAse/workspace/test-subjects/themes/hello-elementor/hello-elementor/functions.php)

---

## 2. Reverse-Engineering & Extraction Value

1. **Theme Locations Audit**: Inspect how `hello-elementor` handles `header.php`, `footer.php`, and `single.php` when Elementor Theme Builder is active vs inactive.
2. **Layout Decoupling**: Follow [`playbooks/02-extract-isolate-merge.md`](file:///root/Desktop/learn/BAse/playbooks/02-extract-isolate-merge.md) to isolate reusable header/footer layouts or CSS tokens.
3. **Asset Deduplication**: Check enqueued assets in `functions.php` or `inc/` to identify reusable CSS reset and utility rules.
