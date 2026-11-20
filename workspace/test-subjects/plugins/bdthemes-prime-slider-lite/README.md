# Test Subject: Bdthemes-Prime-Slider-Lite Plugin

- **Category:** Elementor Addon / Extension Plugin
- **Slug:** `bdthemes-prime-slider-lite`
- **Source Archive:** [`bdthemes-prime-slider-lite.zip`](file:///root/Desktop/learn/BAse/workspace/test-subjects/plugins/bdthemes-prime-slider-lite/bdthemes-prime-slider-lite.zip)
- **Extracted Directory:** [`bdthemes-prime-slider-lite/`](file:///root/Desktop/learn/BAse/workspace/test-subjects/plugins/bdthemes-prime-slider-lite/bdthemes-prime-slider-lite)
- **Authorization & Rights:** [OWNER_AUTHORIZATION_AND_LICENSING.md](file:///root/Desktop/learn/BAse/workspace/test-subjects/OWNER_AUTHORIZATION_AND_LICENSING.md)

---

## 1. Overview & Architecture

Specialized hero slider, carousel, and post slider addon for Elementor with custom Swiper implementations.

### Key Entry Points & Structure:
- Plugin Directory: [`bdthemes-prime-slider-lite/`](file:///root/Desktop/learn/BAse/workspace/test-subjects/plugins/bdthemes-prime-slider-lite/bdthemes-prime-slider-lite)
- Inspect main bootstrap PHP file in root for `elementor/widgets/register` or `elementor/elements/categories_registered`.

---

## 2. Reverse-Engineering & Extraction Value

1. **Widget Structure Analysis**: Inspect how widget classes extend `\Elementor\Widget_Base` using [`playbooks/03-section-to-elementor-widget.md`](file:///root/Desktop/learn/BAse/playbooks/03-section-to-elementor-widget.md).
2. **Asset Dependency Mapping**: Check `get_script_depends()` and `get_style_depends()` to isolate scoped widget CSS/JS.
3. **Frontend JS Handlers**: Check how frontend interactions hook into `elementorModules.frontend.handlers.Base`.
