# Test Subject: Royal-Elementor-Addons Plugin

- **Category:** Elementor Addon / Extension Plugin
- **Slug:** `royal-elementor-addons`
- **Source Archive:** [`royal-elementor-addons.zip`](file:///root/Desktop/learn/BAse/workspace/test-subjects/plugins/royal-elementor-addons/royal-elementor-addons.zip)
- **Extracted Directory:** [`royal-elementor-addons/`](file:///root/Desktop/learn/BAse/workspace/test-subjects/plugins/royal-elementor-addons/royal-elementor-addons)
- **Authorization & Rights:** [OWNER_AUTHORIZATION_AND_LICENSING.md](file:///root/Desktop/learn/BAse/workspace/test-subjects/OWNER_AUTHORIZATION_AND_LICENSING.md)

---

## 1. Overview & Architecture

Comprehensive addon suite featuring 80+ widgets, grid builders, popup builder, and custom post grid skins.

### Key Entry Points & Structure:
- Plugin Directory: [`royal-elementor-addons/`](file:///root/Desktop/learn/BAse/workspace/test-subjects/plugins/royal-elementor-addons/royal-elementor-addons)
- Inspect main bootstrap PHP file in root for `elementor/widgets/register` or `elementor/elements/categories_registered`.

---

## 2. Reverse-Engineering & Extraction Value

1. **Widget Structure Analysis**: Inspect how widget classes extend `\Elementor\Widget_Base` using [`playbooks/03-section-to-elementor-widget.md`](file:///root/Desktop/learn/BAse/playbooks/03-section-to-elementor-widget.md).
2. **Asset Dependency Mapping**: Check `get_script_depends()` and `get_style_depends()` to isolate scoped widget CSS/JS.
3. **Frontend JS Handlers**: Check how frontend interactions hook into `elementorModules.frontend.handlers.Base`.
