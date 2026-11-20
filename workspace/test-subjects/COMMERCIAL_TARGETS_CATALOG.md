# Commercial Themes & Plugins Target Catalog

**File Location:** `workspace/test-subjects/COMMERCIAL_TARGETS_CATALOG.md`  
**Purpose:** Architecture reference, live demo links, and DOM inspection strategies for commercial Elementor themes and premium addons.

---

## 1. Commercial Target Inventory

| Target Name | Vendor / Developer | Type | Official / Landing URL | Marketplace / Demo Link | Architectural Equivalent in Local Test Subjects |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **JupiterX** | Artbees | Theme / Platform | [jupiterx.com](https://jupiterx.com/) | [Pricing & Demos](https://jupiterx.com/pricing) | Modular Elementor theme; pairs with Elementor Pro or Raven plugin. |
| **The7** | Dream-Theme | Theme | [the7.io](https://the7.io/) | [ThemeForest Item 5556590](https://themeforest.net/item/the7-responsive-multipurpose-wordpress-theme/5556590) | Deep Elementor container builder with custom WPBakery/Elementor dual engine. |
| **Phlox Pro** | Averta | Theme | [phlox.pro](https://phlox.pro/) | [ThemeForest Item 19429587](https://themeforest.net/item/phlox-pro-elementor-multi-purpose-wordpress-theme/19429587) | Built on **Auxin Framework**. Core engine is already stored locally in [`themes/phlox/`](file:///root/Desktop/learn/BAse/workspace/test-subjects/themes/phlox/). |
| **Bridge** | Qode Interactive | Theme | [bridge.qodeinteractive.com](https://bridge.qodeinteractive.com/) | [ThemeForest Item 7315054](https://themeforest.net/item/bridge-creative-multipurpose-wordpress-theme/7315054) | Qode Framework. Free Elementor engine is stored locally in [`plugins/qi-addons-for-elementor/`](file:///root/Desktop/learn/BAse/workspace/test-subjects/plugins/qi-addons-for-elementor/). |
| **Brisk** | UiCore | Theme | [uicore.co/brisk](https://themeforest.net/item/brisk-multipurpose-elementor-wordpress-theme/39184595) | [ThemeForest Item 39184595](https://themeforest.net/item/brisk-multipurpose-elementor-wordpress-theme/39184595) | UiCore Framework. Known for high-end micro-interactions, portfolio grids, and smooth scroll. |
| **Avas** | Theme-4U | Theme | [avas.theme-4u.com](https://themeforest.net/item/avas-responsive-multipurpose-wordpress-theme/22051748) | [ThemeForest Item 22051748](https://themeforest.net/item/avas-responsive-multipurpose-wordpress-theme/22051748) | Multipurpose Elementor layout templates. |
| **Asli** | Artem Semkin | Theme | [asli.art-theme.com](https://themeforest.net/search/asli) | Creative portfolio theme | Uses custom smooth scroll engine, stored locally in [`plugins/smooth-scrolling-for-elementor/`](file:///root/Desktop/learn/BAse/workspace/test-subjects/plugins/smooth-scrolling-for-elementor/). |
| **Aurem** | Artem Semkin | Theme | [aurem.art-theme.com](https://themeforest.net/search/aurem) | Luxury portfolio theme | Smooth scroll, WebGL transitions, and GSAP timeline animations. |
| **Willows** | Artem Semkin | Theme | [willows.art-theme.com](https://themeforest.net/search/willows) | Architecture & studio theme | Minimalist typography, pinned horizontal scroll sections, and Lenis smooth scroll. |
| **Brandberry** | Themesflat | Theme | [themesflat.co](https://themeforest.net/search/brandberry) | Digital agency theme | Elementor header/footer builder, responsive showcase grids. |
| **Slider Revolution** | ThemePunch | Plugin | [sliderrevolution.com](https://www.sliderrevolution.com/) | Advanced animation engine | Visual canvas timeline editor. For modern Elementor, WAAPI/GSAP alternatives exist in [`plugins/motionkit/`](file:///root/Desktop/learn/BAse/workspace/test-subjects/plugins/motionkit/). |

---

## 2. Reverse-Engineering Workflow for Commercial Demos

When reverse-engineering sections from commercial themes via their live public preview sites:

1. **DOM & CSS Extraction**:
   - Inspect public demo URLs using browser automation (`agent-browser open <demo-url>`) or DevTools.
   - Extract the component's HTML subtree, scoped CSS classes, and media queries.
2. **Decouple Vendor JavaScript**:
   - Identify whether interactive elements (carousels, tabs, accordions) use standard libraries (Swiper, GSAP, Lenis) or proprietary scripts.
   - Substitute proprietary scripts with standard Swiper or Elementor's native frontend base handler (`elementorModules.frontend.handlers.Base`).
3. **Convert to Elementor Widget or Container**:
   - Follow [`playbooks/03-section-to-elementor-widget.md`](file:///root/Desktop/learn/BAse/playbooks/03-section-to-elementor-widget.md) for custom widgets.
   - Follow [`docs/03-elementor-theme/container-json-schema.md`](file:///root/Desktop/learn/BAse/docs/03-elementor-theme/container-json-schema.md) for native JSON templates.
