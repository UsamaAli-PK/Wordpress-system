# Test Subject: Creative-Portfolio-Lite Theme

- **Category:** Classic WordPress Theme
- **Slug:** `creative-portfolio-lite`
- **Source Archive:** [`creative-portfolio-lite.zip`](file:///root/Desktop/learn/BAse/workspace/test-subjects/themes/creative-portfolio-lite/creative-portfolio-lite.zip)
- **Extracted Directory:** [`creative-portfolio-lite/`](file:///root/Desktop/learn/BAse/workspace/test-subjects/themes/creative-portfolio-lite/creative-portfolio-lite)
- **Authorization & Rights:** [OWNER_AUTHORIZATION_AND_LICENSING.md](file:///root/Desktop/learn/BAse/workspace/test-subjects/OWNER_AUTHORIZATION_AND_LICENSING.md)

---

## 1. Overview & Architecture

Clean showcase theme for creative agencies and freelancers. Great reference for minimal portfolio grids and clean post navigation.

### Key Entry Points:
- Style Definition: [`style.css`](file:///root/Desktop/learn/BAse/workspace/test-subjects/themes/creative-portfolio-lite/creative-portfolio-lite/style.css)
- Theme Bootstrap: [`functions.php`](file:///root/Desktop/learn/BAse/workspace/test-subjects/themes/creative-portfolio-lite/creative-portfolio-lite/functions.php)

---

## 2. Reverse-Engineering & Extraction Value

1. **Theme Locations Audit**: Inspect how `creative-portfolio-lite` handles `header.php`, `footer.php`, and `single.php` when Elementor Theme Builder is active vs inactive.
2. **Layout Decoupling**: Follow [`playbooks/02-extract-isolate-merge.md`](file:///root/Desktop/learn/BAse/playbooks/02-extract-isolate-merge.md) to isolate reusable header/footer layouts or CSS tokens.
3. **Asset Deduplication**: Check enqueued assets in `functions.php` or `inc/` to identify reusable CSS reset and utility rules.
