---
topic: Premium Addons — Public Hooks & Filters API
last_synced: 2026-11-20
source_subject: /root/Desktop/learn/BAse/workspace/test-subjects/plugins/premium-addons-for-elementor/premium-addons-for-elementor
---

# Premium Addons — Public Hooks & Filters API

**Counts:** 88 unique `pa_*` action hooks, 31 unique `pa_*` filters exported.

Recreate the catalog:
```bash
cd workspace/test-subjects/plugins/premium-addons-for-elementor/premium-addons-for-elementor
grep -rhoE "do_action\s*\(\s*['\"]pa_[a-z_]+['\"]" --include="*.php" | sort -u > hooks.txt
grep -rhoE "apply_filters\s*\(\s*['\"]pa_[a-z_]+['\"]" --include="*.php" | sort -u > filters.txt
```

---

## 1. Admin surface

| Hook | Purpose |
| :--- | :--- |
| `pa_before_render_admin_tabs` / `pa_after_render_admin_tabs` (actions) | Fire around the admin page tab renderer — inject custom tabs |
| `pa_admin_register_tabs` (filter) | Filter the admin tab list before rendering |

Use to add custom admin pages that live inside PAE's admin shell.

---

## 2. Widget-level extension points (one group per widget)

Most hooks are *widget-specific extension points*. Each extends a particular widget's control schema or render output. Grouped by widget:

### Animated Text (`premium-fancytext`)
- `pa_atext_highlight_controls` — append controls into highlight style section
- `pa_atext_shape_style` — append shape style controls

### Blog (`premium-blog`)
- `pa_blog_after_thumbnail` — inject after post thumbnail
- `pa_before_post_content` / `pa_after_post_content` — wrap the blog card body
- `pa_post_excerpt` (filter) — modify excerpt
- `pa_post_tags` (filter) — modify tag list

### Button (`premium-button`)
- `pa_button_hover_controls` — append custom hover effects

### Carousel (`premium-carousel`)
- `pa_adv_carousel_navigation` — custom nav controls
- `pa_adv_carousel_options` — extra carousel settings

### Countdown — none exported (self-contained)

### Dual Header (`premium-dual-header`) — none exported

### Grid (`premium-grid`)
- `pa_grid_image_alt` (filter) — override grid image alt attribute
- `pa_image_hover_effects` — inject custom hover effects into the grid

### Maps (`premium-maps`)
- `pa_maps_marker_controls` — append marker config controls

### Media Wheel (`premium-media-wheel`) — internal

### Mobile Menu (`premium-mobile-menu`)
- `pa_mobile_menu_badge_controls` / `pa_mobile_menu_badge_style` / `pa_mobile_menu_badge_style_tab`
- `pa_mobile_menu_carousel_controls`
- `pa_mobile_menu_indicator_carousel_style`

### Nav Menu (`premium-nav-menu`)
- `pa_custom_menu_controls`
- `pa_slide_menu_top_template` / `pa_slide_menu_bottom_template` — slide-menu header/footer slots

### Notifications (`premium-notifications`)
- `pa_notification_cats_controls`
- `pa_rn_badges_controls`
- `pa_get_random_badges_settings` (filter)

### Person (`premium-person`)
- `pa_before_member_icon` / `pa_after_member_icon` — wrap social icon row

### Pinterest Feed (`premium-pinterest-feed`)
- `pa_pinterest_board_controls` / `pa_pinterest_board_style`
- `pa_pinterest_profile_controls` / `pa_pinterest_profile_style`
- `pa_pinterest_dots_style`
- `pa_pinterest_render_dots`
- `pa_pinterest_slide_align`
- `pa_pinterest_token_*` (filter) — modify Pinterest OAuth token

### Post Ticker (`premium-post-ticker`)
- `pa_ticker_stock_controls` / `pa_ticker_stock_query` / `pa_ticker_stock_style`
- `pa_stock_api` / `pa_gold_api` / `pa_ticker_reload_*` (filters) — swap stock/gold data source

### Search Form (`premium-search-form`)
- `pa_search_effects_options`
- `pa_search_keyword_button_style`
- `pa_search_select_field_style`
- `pa_search_before_post_content` / `pa_search_after_post_content`

### Shape Divider (addon)
- `pa_divider_custom_svg` — inject custom shape
- `pa_divider_fill_controls`

### SVG Drawer (`premium-svg-drawer`)
- `pa_showcase_svg` / `pa_showcase_svg_hover`

### Tag Cloud (`premium-tcloud`)
- `pa_tcloud_shape_controls`
- `pa_tcloud_sphere_controls`
- `pa_tcloud_throwable_controls`

### Testimonials (`premium-testimonials`)
- `pa_testimonials_skins_options`

### TikTok Feed (`premium-tiktok-feed`)
- `pa_tiktok_load_more_options`
- `pa_tiktok_loadmore_style`
- `pa_tiktok_profile_controls` / `pa_tiktok_profile_style`

### Tooltips (addon)
- `pa_tooltips_type_controls`
- `pa_tooltips_dynamic` (filter)

### Weather (`premium-weather`)
- `pa_weather_source_controls`
- `pa_weather_daily_forecast_controls`
- `pa_weather_custom_icons_controls`
- `pa_weather_api` (filter) — swap weather data source

### World Clock (`premium-world-clock`) — self-contained

### Floating Effects (addon)
- `pa_floating_blur_controls`
- `pa_floating_brightness_controls`
- `pa_floating_contrast_controls`
- `pa_floating_gs_controls` (grayscale)
- `pa_floating_hue_controls`
- `pa_floating_saturation_controls`

### Contact Form (`premium-contactform`)
- `pa_cf_effects_options`
- `pa_cf_presets_options`

### WooCommerce widgets (module)
- `pa_woo_cats_carousel`, `pa_woo_cats_carousel_style`, `pa_woo_cats_individual_style`
- `pa_woo_cta_notice_controls`, `pa_woo_cta_text_controls`
- `pa_woo_mini_cart_window_controls`, `pa_woo_mini_cart_window_style_controls`
- `pa_woo_product_after_cat` / `_cta` / `_desc` / `_price` / `_rating` / `_title` / `_aftr_title`
- `pa_woo_product_after_details_wrap_start` / `_end`
- Filters: `pa_mini_cart_tax_display`, `pa_woo_cat_product_string`, `pa_woo_cat_products_string`, `pa_woo_main_query_args`, `pa_woo_mc_empty_cart_btn_message`, `pa_woo_mc_empty_cart_image`, `pa_woo_mc_empty_cart_message`, `pa_woo_product_gallery_image`, `pa_woo_product_parent_category`, `pa_woo_product_swap_image`

---

## 3. Display Conditions

| Filter | Purpose |
| :--- | :--- |
| `pa_display_conditions` | Register / modify the full list of available conditions |
| `pa_wordpress_locales` | Modify available locale choices |

---

## 4. MCP / Abilities API

| Hook | Purpose |
| :--- | :--- |
| `pa_abilities_register_handlers` (action) | Register custom ability handlers with the MCP server |
| `pa_elementor_mcp_tools` (filter) | Add/modify MCP tool definitions |
| `pa_oauth_gc` (scheduled action) | Daily cron that clears expired OAuth tokens — fires `OAuth\Store::gc()` |
| `pa_oauth_client_ip` (filter) | Override how PAE resolves the client IP for OAuth challenges |
| `pa_temp_id` (filter) | Transient ID used by ability storage |

---

## 5. Cross-site transfer

| Filter | Purpose |
| :--- | :--- |
| `pa_transfer_template_depth` | Max nested depth when exporting templates |
| `pa_transfer_ttl` | How long the export payload remains valid |

---

## 6. Pro upsell

| Filter | Purpose |
| :--- | :--- |
| `pa_pro_label` | Change the "Pro" badge text — strip on extraction |

---

## 7. Image handling (perf)

| Filter | Purpose |
| :--- | :--- |
| `pa_disable_image_lazyload` | Turn off lazy-loading on PA widget images |
| `pa_media_alt` | Default alt text when none set |

---

## 8. How to use this file

- Extending a specific widget's controls? Open the widget's section above; the hooks name the control-section slots you can append to. Then open `widgets/<slug>.php` to see where each `do_action` fires.
- Changing PA's admin UI? §1.
- Adding or filtering display conditions? §3.
- Hooking the MCP server? §4.
- Changing API data sources (stock, weather, Pinterest token)? Filter in §2 / §7.
- Customising WooCommerce widgets? §2 → "WooCommerce widgets".

For hook-to-source resolution, grep the hook name in the source tree; PAE widgets are one file each, so the fire location is immediate.
