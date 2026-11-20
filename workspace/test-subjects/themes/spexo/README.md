# Test Subject: Spexo Theme

- **Category:** Classic WordPress Theme
- **Slug:** `spexo`
- **Source Archive:** [`spexo.zip`](file:///root/Desktop/learn/BAse/workspace/test-subjects/themes/spexo/spexo.zip)
- **Extracted Directory:** [`spexo/`](file:///root/Desktop/learn/BAse/workspace/test-subjects/themes/spexo/spexo)
- **Authorization & Rights:** [OWNER_AUTHORIZATION_AND_LICENSING.md](file:///root/Desktop/learn/BAse/workspace/test-subjects/OWNER_AUTHORIZATION_AND_LICENSING.md)

---

## 1. Overview & Architecture

Modern agency and portfolio theme featuring clean responsive layouts, grid systems, and lightweight typography styling.

### Key Entry Points:
- Style Definition: [`style.css`](file:///root/Desktop/learn/BAse/workspace/test-subjects/themes/spexo/spexo/style.css)
- Theme Bootstrap: [`functions.php`](file:///root/Desktop/learn/BAse/workspace/test-subjects/themes/spexo/spexo/functions.php)

---

## 2. Reverse-Engineering & Extraction Value

1. **Theme Locations Audit**: Inspect how `spexo` handles `header.php`, `footer.php`, and `single.php` when Elementor Theme Builder is active vs inactive.
2. **Layout Decoupling**: Follow [`playbooks/02-extract-isolate-merge.md`](file:///root/Desktop/learn/BAse/playbooks/02-extract-isolate-merge.md) to isolate reusable header/footer layouts or CSS tokens.
3. **Asset Deduplication**: Check enqueued assets in `functions.php` or `inc/` to identify reusable CSS reset and utility rules.
