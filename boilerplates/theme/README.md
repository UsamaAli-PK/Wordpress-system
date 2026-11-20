# Base Classic Theme Boilerplate

**Location:** `boilerplates/theme/`  
**License:** GPL-2.0+  
**Stack:** Classic PHP Theme + Elementor Theme Locations API (`elementor/theme/register_locations`)

---

## 1. Architectural Highlights

1. **Theme Locations Primacy**: Fully wraps `header.php`, `footer.php`, `single.php`, and `archive.php` with `elementor_theme_do_location()`. When an Elementor Theme Builder template is published, Elementor takes over completely.
2. **Zero Degradation Fallback**: If Elementor is inactive, disabled, or uninstalled, the theme cleanly falls back to semantic HTML5 templates and navigation menus with zero fatal errors or broken tags.
3. **Design Token Bridge**: `assets/css/theme-variables.css` maps theme tokens (`--base-primary-color`, `--base-container-width`) to Elementor Global Kit tokens (`--e-global-color-primary`, `--e-global-container-width`).
4. **PHP 8.1+ Strict**: Uses `declare(strict_types=1);` and namespaced functions.
