# Test Subject: Responsive-Addons-For-Elementor Plugin

- **Category:** Elementor Addon / Extension Plugin
- **Slug:** `responsive-addons-for-elementor`
- **Source Archive:** [`responsive-addons-for-elementor.zip`](file:///root/Desktop/learn/BAse/workspace/test-subjects/plugins/responsive-addons-for-elementor/responsive-addons-for-elementor.zip)
- **Extracted Directory:** [`responsive-addons-for-elementor/`](file:///root/Desktop/learn/BAse/workspace/test-subjects/plugins/responsive-addons-for-elementor/responsive-addons-for-elementor)
- **Authorization & Rights:** [OWNER_AUTHORIZATION_AND_LICENSING.md](file:///root/Desktop/learn/BAse/workspace/test-subjects/OWNER_AUTHORIZATION_AND_LICENSING.md)

---

## 1. Overview & Architecture

Contains 90+ modern Elementor widgets with deep mobile responsiveness controls.

### Key Entry Points & Structure:
- Plugin Directory: [`responsive-addons-for-elementor/`](file:///root/Desktop/learn/BAse/workspace/test-subjects/plugins/responsive-addons-for-elementor/responsive-addons-for-elementor)
- Inspect main bootstrap PHP file in root for `elementor/widgets/register` or `elementor/elements/categories_registered`.

---

## 2. Reverse-Engineering & Extraction Value

1. **Widget Structure Analysis**: Inspect how widget classes extend `\Elementor\Widget_Base` using [`playbooks/03-section-to-elementor-widget.md`](file:///root/Desktop/learn/BAse/playbooks/03-section-to-elementor-widget.md).
2. **Asset Dependency Mapping**: Check `get_script_depends()` and `get_style_depends()` to isolate scoped widget CSS/JS.
3. **Frontend JS Handlers**: Check how frontend interactions hook into `elementorModules.frontend.handlers.Base`.
