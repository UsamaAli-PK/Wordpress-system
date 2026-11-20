---
topic: Astra Public Hooks & Filters API
last_synced: 2026-11-20
source_subject: /root/Desktop/learn/BAse/workspace/test-subjects/themes/astra/astra
---

# Astra Public Hooks & Filters API

**Scope:** every hook / filter the theme *exports* (via `do_action` / `apply_filters`). These are the extension points child themes, companion plugins, and compatibility layers plug into. Listed by domain, not alphabetical — grep the source for exact `file:line`.

**Counts:** 243 unique `astra_*` action hooks, 404 unique `astra_*` filters (both include a handful of `bsf_*` and `astra_addon_*` neighbours).

Recreate the catalog at any time with:
```bash
cd workspace/test-subjects/themes/astra/astra
grep -rhoE "do_action\s*\(\s*['\"]astra_[a-z_]+['\"]" --include="*.php" | sort -u > hooks.txt
grep -rhoE "apply_filters\s*\(\s*['\"]astra_[a-z_]+['\"]" --include="*.php" | sort -u > filters.txt
```

---

## 1. Page skeleton hooks (where to inject layout)

Fire before/after major regions of the page. Use these to inject bars, announcements, promo strips, slide-ins.

| Hook | Where it fires |
| :--- | :--- |
| `astra_html_before` / `astra_html_after` | Outer `<html>` wrappers |
| `astra_body_top` / `astra_body_bottom` | Immediately after `<body>` / before `</body>` |
| `astra_above_header` / `astra_below_header` | Before and after the entire header region |
| `astra_header_before` / `astra_header_after` | Inside the header region |
| `astra_main_header_bar_top` / `astra_main_header_bar_bottom` | Inside the primary header bar |
| `astra_masthead_top` / `astra_masthead_bottom` | Around the masthead |
| `astra_content_before` / `astra_content_after` | Around `#primary` main content |
| `astra_content_top` / `astra_content_bottom` | Inside the main content wrapper |
| `astra_content_loop` / `astra_content_page_loop` | Blog loop vs page content |
| `astra_above_footer` / `astra_below_footer` | Around the entire footer region |
| `astra_footer_before` / `astra_footer_after` | Immediately around `<footer>` |
| `astra_footer_content` / `astra_footer_content_top` / `astra_footer_content_bottom` | Inside footer |
| `astra_footer_copyright` | Bottom-footer copyright row |

**Dynamic (templated) hooks for Header/Footer Builder rows:**
- `astra_header_{row}_container_before` / `_after`
- `astra_footer_{row}_container_before` / `_after`
- Row slugs: `above`, `primary`, `below`.

---

## 2. Loop / entry hooks (single posts, pages, archives)

| Hook | Fires |
| :--- | :--- |
| `astra_entry_before` / `astra_entry_after` | Around each entry article tag |
| `astra_entry_top` / `astra_entry_bottom` | Inside `<article>` |
| `astra_entry_header_before` / `astra_entry_header_after` | Around entry header |
| `astra_entry_content_before` / `astra_entry_content_after` | Around post content |
| `astra_entry_content_blog` / `astra_entry_content_single` / `astra_entry_content_single_page` | Content-variant entry points |
| `astra_archive_header` | Rendering archive title/description block |
| `astra_archive_entry_header_before` / `_after` | Archive entry header |
| `astra_archive_post_meta_before` / `_after` | Archive meta row |
| `astra_archive_post_title_before` / `_after` | Archive post title |
| `astra_single_header_before` / `_after` / `_top` / `_bottom` | Single-post header region |
| `astra_single_post_banner_before` / `_after` | Dedicated single-post banner |
| `astra_single_post_banner_breadcrumb_after` | Breadcrumb inside banner |
| `astra_pagination` | Pagination render point |

---

## 3. Dynamic markup contexts (opt-in attribute filter)

Astra routes most HTML attributes through `Astra_Attr::get_attr( $context )`. Two filters per context:

- `astra_attr_{context}` — associative array of attribute → value
- `astra_attr_{context}_output` — final serialised attribute string

Meaning a child theme can inject classes, `data-*`, `aria-*` on any wrapper by filtering `astra_attr_site-header`, `astra_attr_site-content`, `astra_attr_primary-navigation`, etc. Contexts are declared throughout `inc/markup-extras.php`.

---

## 4. Markup open/close bookends

Every structural element is wrapped by:
```php
astra_markup_open( $context );
// … actual markup …
astra_markup_close( $context );
```
Which fire the filters:
- `astra_markup_{context}_open`
- `astra_markup_{context}_close`

Use these to replace entire wrappers without touching templates.

---

## 5. Options plumbing

All option reads go through `astra_get_option()`; writes/deletes through its paired helpers. The plumbing exposes:

| Filter / Action | Purpose |
| :--- | :--- |
| `astra_get_option_{option}` | Override a single option's return value |
| `astra_get_option_meta_{option_id}` | Override an option sourced via post meta |
| `astra_before_update_option_{option}` / `astra_after_update_option_{option}` | Before / after an option write |
| `astra_before_delete_option_{option}` / `astra_after_delete_option_{option}` | Before / after a delete |
| `astra_customizer_save` | Fired at end of a Customizer save pass |
| `astra_customizer_font_list` | Modify the Customizer font picker list |

---

## 6. Dynamic CSS contribution hooks

| Hook | Purpose |
| :--- | :--- |
| `astra_dynamic_css` filter | Append CSS string into Astra's inline `<style>` output |
| `astra_addon_colors_dynamic_css_desktop` / `_tablet` / `_mobile` | Breakpoint-scoped CSS additions |
| `astra_apply_flex_based_css` | Toggle flex-based legacy CSS path |
| `astra_apply_global_color_format_support` | Switch global colour format |
| `ast_dark_palette_css` | Dark-mode palette CSS injection |

---

## 7. WooCommerce / EDD compatibility API

**WooCommerce hooks** (`astra_woo_*`): 36 unique. They wrap WooCommerce's own template hooks with Astra context, enabling theme-level customisation of shop pages, single-product summary, cart, checkout, My Account.

**EDD hooks** (`astra_edd_*`): 25 unique. Equivalent layer for Easy Digital Downloads — archive block wrap, price, category, header cart icons, product short description, add-to-cart.

See `inc/compatibility/woocommerce/` and `inc/compatibility/edd/` for the full event declarations.

---

## 8. Builder (Header/Footer Builder)

| Hook | Purpose |
| :--- | :--- |
| `astra_builder_loaded` | After Builder bootstraps — safe hook point for custom builder elements |
| `astra_render_{builder}_{element}_{slot}` | Render-time pipe for each builder element |
| Filter `astra_{builder}_components` | Modify the list of available builder elements |
| Filter `astra_{builder}_element_default_{element}` | Override element defaults |

Builder elements include: site-identity, menu, mobile-menu, mobile-trigger, off-canvas, button, html, search, social-icon, widget, account, edd-cart, woo-cart, above/primary/below header, above/primary/below footer, copyright.

---

## 9. Notices framework

| Hook | Purpose |
| :--- | :--- |
| `astra_notice_before_markup` / `astra_notice_before_markup_{id}` | Before a notice renders |
| `astra_notice_inside_markup` / `astra_notice_inside_markup_{id}` | Inside notice body |
| `astra_notice_after_markup` / `astra_notice_after_markup_{id}` | After notice renders |
| `bsf_admin_notice_*` triplet | Same lifecycle for BSF-branded notices |

---

## 10. Pro / addon upsell API

Filters the paid Astra Pro / Astra Addon plugin uses to inject features. Extracting from the free theme means **ignoring or removing** these hooks:

- `astra_addon_*` — 20+ filters prefixed this way
- `astra_pro_*` — upsell link hooks
- `astra_addon_shop_cards_buttons_html`, `astra_addon_site_current_language`, `astra_addon_upgrade_fullscreen_search_submit_style`
- Customizer: `inc/customizer/astra-pro/class-astra-pro-upgrade-link-configs.php`

---

## 11. MCP / Abilities API

| Hook | Purpose |
| :--- | :--- |
| `astra_ability_mcp_public` | Allow an Astra ability to be exposed via MCP |
| `astra_ability_show_in_rest` | Allow an Astra ability in REST |
| `astra_ability_settings_updated` | Fires when an ability's settings are persisted |

---

## 12. Toggles worth knowing

Common on/off filters:

| Filter | Default | What it toggles |
| :--- | :--- | :--- |
| `astra_amp_support` | true | Load AMP compat layer |
| `astra_above_header_display` / `astra_above_mobile_header_display` | depends | Show the above-header row |
| `astra_apply_hero_header_banner` | depends | Render the single-post hero |
| `astra_add_to_cart_quantity_btn_enabled` | depends | Show the +/- qty button on WooCommerce |
| `astra_advanced_footer_disable` | false | Disable the advanced footer widgets layout |
| `astra_apply_right_margin_footer_widget_css` | true | Legacy footer widget margin |

---

## 13. Filter domains (top 25 by frequency)

| Domain stem | Count | Examples |
| :--- | :--- | :--- |
| `get` | 30 | `astra_get_option_*`, `astra_get_sidebar_*` |
| `related` | 20 | `astra_related_posts_*` |
| `post` | 15 | `astra_post_meta_*`, `astra_post_title_*` |
| `the` | 13 | `astra_the_content_wrap`, `astra_the_title` |
| `theme` | 11 | `astra_theme_*` |
| `enable` / `disable` | 11 / 10 | per-feature toggles |
| `woo` / `edd` | 10 / 8 | shop compat |
| `local` | 9 | `astra_local_google_fonts_*` (local-host webfonts) |
| `header` / `single` / `banner` | 9 / 5 / 5 | region helpers |
| `addon` | 7 | Pro upsell |
| `remove` | 6 | Drop built-in features |
| `customizer` / `elementor` / `block` | 5 / 5 / 5 | third-party glue |
| `global` | 5 | `astra_global_*` palette/style |

---

## 14. How to use this file

1. Looking for where to hook a region? → §1 (page skeleton) or §2 (loop).
2. Need to alter markup wrappers without touching templates? → §3 (attrs) + §4 (markup bookends).
3. Doing a WooCommerce or EDD customization? → §7, which maps every shop/product position.
4. Hooking into Header/Footer Builder? → §8 — then open the specific element folder under `inc/builder/type/header/<slug>/`.
5. Extracting a dynamic-css module? → §6 + the module file directly.
6. Doing AI-assisted automation? → §11 for ability toggles.

For hook-to-source resolution, re-grep inside the source tree. Astra's filenames are self-explanatory, so you can jump from a hook name to its declaring file directly.
