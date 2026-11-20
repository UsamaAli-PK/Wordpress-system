# Test Subject: Essential-Addons-For-Elementor-Lite Plugin

- **Category:** Elementor Addon / Extension Plugin
- **Slug:** `essential-addons-for-elementor-lite`
- **Source Archive:** [`essential-addons-for-elementor-lite.zip`](file:///root/Desktop/learn/BAse/workspace/test-subjects/plugins/essential-addons-for-elementor-lite/essential-addons-for-elementor-lite.zip)
- **Extracted Directory:** [`essential-addons-for-elementor-lite/`](file:///root/Desktop/learn/BAse/workspace/test-subjects/plugins/essential-addons-for-elementor-lite/essential-addons-for-elementor-lite)
- **Authorization & Rights:** [OWNER_AUTHORIZATION_AND_LICENSING.md](file:///root/Desktop/learn/BAse/workspace/test-subjects/OWNER_AUTHORIZATION_AND_LICENSING.md)

---

## 1. Overview & Architecture

The gold-standard reference for modular widget asset loading, dynamic query widgets, and post grids.

### Key Entry Points & Structure:
- Plugin Directory: [`essential-addons-for-elementor-lite/`](file:///root/Desktop/learn/BAse/workspace/test-subjects/plugins/essential-addons-for-elementor-lite/essential-addons-for-elementor-lite)
- Inspect main bootstrap PHP file in root for `elementor/widgets/register` or `elementor/elements/categories_registered`.

---

## 2. Reverse-Engineering & Extraction Value

1. **Widget Structure Analysis**: Inspect how widget classes extend `\Elementor\Widget_Base` using [`playbooks/03-section-to-elementor-widget.md`](file:///root/Desktop/learn/BAse/playbooks/03-section-to-elementor-widget.md).
2. **Asset Dependency Mapping**: Check `get_script_depends()` and `get_style_depends()` to isolate scoped widget CSS/JS.
3. **Frontend JS Handlers**: Check how frontend interactions hook into `elementorModules.frontend.handlers.Base`.
