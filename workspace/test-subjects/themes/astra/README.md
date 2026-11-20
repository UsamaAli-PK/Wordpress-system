# Test Subject: Astra Theme

- **Category:** Classic WordPress Theme
- **Slug:** `astra`
- **Source Archive:** [`astra.4.14.3.zip`](file:///root/Desktop/learn/BAse/workspace/test-subjects/themes/astra/astra.4.14.3.zip)
- **Extracted Directory:** [`astra/`](file:///root/Desktop/learn/BAse/workspace/test-subjects/themes/astra/astra)
- **Authorization & Rights:** [OWNER_AUTHORIZATION_AND_LICENSING.md](file:///root/Desktop/learn/BAse/workspace/test-subjects/OWNER_AUTHORIZATION_AND_LICENSING.md)

---

## 1. Overview & Architecture

Lightweight, performance-focused classic base theme. Excellent reference for Elementor Theme Locations integration, customizer settings, and clean header/footer template fallbacks.

### Key Entry Points:
- Style Definition: [`style.css`](file:///root/Desktop/learn/BAse/workspace/test-subjects/themes/astra/astra/style.css)
- Theme Bootstrap: [`functions.php`](file:///root/Desktop/learn/BAse/workspace/test-subjects/themes/astra/astra/functions.php)

---

## 2. Reverse-Engineering & Extraction Value

1. **Theme Locations Audit**: Inspect how `astra` handles `header.php`, `footer.php`, and `single.php` when Elementor Theme Builder is active vs inactive.
2. **Layout Decoupling**: Follow [`playbooks/02-extract-isolate-merge.md`](file:///root/Desktop/learn/BAse/playbooks/02-extract-isolate-merge.md) to isolate reusable header/footer layouts or CSS tokens.
3. **Asset Deduplication**: Check enqueued assets in `functions.php` or `inc/` to identify reusable CSS reset and utility rules.
