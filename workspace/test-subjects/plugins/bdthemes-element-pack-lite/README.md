# Test Subject: Bdthemes-Element-Pack-Lite Plugin

- **Category:** Elementor Addon / Extension Plugin
- **Slug:** `bdthemes-element-pack-lite`
- **Source Archive:** [`bdthemes-element-pack-lite.zip`](file:///root/Desktop/learn/BAse/workspace/test-subjects/plugins/bdthemes-element-pack-lite/bdthemes-element-pack-lite.zip)
- **Extracted Directory:** [`bdthemes-element-pack-lite/`](file:///root/Desktop/learn/BAse/workspace/test-subjects/plugins/bdthemes-element-pack-lite/bdthemes-element-pack-lite)
- **Authorization & Rights:** [OWNER_AUTHORIZATION_AND_LICENSING.md](file:///root/Desktop/learn/BAse/workspace/test-subjects/OWNER_AUTHORIZATION_AND_LICENSING.md)

---

## 1. Overview & Architecture

Massive suite of core widgets, third-party plugin extensions, and advanced layout query builders.

### Key Entry Points & Structure:
- Plugin Directory: [`bdthemes-element-pack-lite/`](file:///root/Desktop/learn/BAse/workspace/test-subjects/plugins/bdthemes-element-pack-lite/bdthemes-element-pack-lite)
- Inspect main bootstrap PHP file in root for `elementor/widgets/register` or `elementor/elements/categories_registered`.

---

## 2. Reverse-Engineering & Extraction Value

1. **Widget Structure Analysis**: Inspect how widget classes extend `\Elementor\Widget_Base` using [`playbooks/03-section-to-elementor-widget.md`](file:///root/Desktop/learn/BAse/playbooks/03-section-to-elementor-widget.md).
2. **Asset Dependency Mapping**: Check `get_script_depends()` and `get_style_depends()` to isolate scoped widget CSS/JS.
3. **Frontend JS Handlers**: Check how frontend interactions hook into `elementorModules.frontend.handlers.Base`.
