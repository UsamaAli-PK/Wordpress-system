# Test Subject: Header-Footer-Elementor Plugin

- **Category:** Elementor Addon / Extension Plugin
- **Slug:** `header-footer-elementor`
- **Source Archive:** [`header-footer-elementor.zip`](file:///root/Desktop/learn/BAse/workspace/test-subjects/plugins/header-footer-elementor/header-footer-elementor.zip)
- **Extracted Directory:** [`header-footer-elementor/`](file:///root/Desktop/learn/BAse/workspace/test-subjects/plugins/header-footer-elementor/header-footer-elementor)
- **Authorization & Rights:** [OWNER_AUTHORIZATION_AND_LICENSING.md](file:///root/Desktop/learn/BAse/workspace/test-subjects/OWNER_AUTHORIZATION_AND_LICENSING.md)

---

## 1. Overview & Architecture

Lightweight Elementor Theme Locations implementation for third-party classic themes without Pro.

### Key Entry Points & Structure:
- Plugin Directory: [`header-footer-elementor/`](file:///root/Desktop/learn/BAse/workspace/test-subjects/plugins/header-footer-elementor/header-footer-elementor)
- Inspect main bootstrap PHP file in root for `elementor/widgets/register` or `elementor/elements/categories_registered`.

---

## 2. Reverse-Engineering & Extraction Value

1. **Widget Structure Analysis**: Inspect how widget classes extend `\Elementor\Widget_Base` using [`playbooks/03-section-to-elementor-widget.md`](file:///root/Desktop/learn/BAse/playbooks/03-section-to-elementor-widget.md).
2. **Asset Dependency Mapping**: Check `get_script_depends()` and `get_style_depends()` to isolate scoped widget CSS/JS.
3. **Frontend JS Handlers**: Check how frontend interactions hook into `elementorModules.frontend.handlers.Base`.
