# Elementor Widget Starter Boilerplate

**Location:** `boilerplates/widget-template/`  
**Stack:** PHP 8.1+ Widget extending `\Elementor\Widget_Base` with ES6 Frontend Handler and Backbone `content_template()`.

---

## 1. Components Included

- **`class-sample-widget.php`**:
  - Extends `\Elementor\Widget_Base`
  - Registers under custom category `agency-elements`
  - Content controls: Text (`card_title`), Textarea (`card_description`), URL (`card_link`), and `\Elementor\Repeater` (`features_list`)
  - Style controls: Color, Dimensions padding, Group Border, Group Box Shadow, and Group Typography
  - Strict escaping in `render()` and matching Backbone live preview template in `content_template()`
- **`sample-widget.js`**:
  - Extends `elementorModules.frontend.handlers.Base`
  - Automatically mounts via `elementor/frontend/init`
  - Re-initializes on `onElementChange()` when edited in the Elementor panel
- **`sample-widget.css`**:
  - Scoped BEM styles with CSS custom properties
