---
topic: Elementor Container JSON Schema (v0.4)
source_url: https://developers.elementor.com/docs/data-structure/container-element
last_synced: 2026-11-20
---

# Elementor Container Data Structure & JSON Schema (v0.4)

## 1. Document Root Format

Modern Elementor template documents (version `0.4`) use a standardized JSON wrapper:

```json
{
  "title": "Main Landing Page",
  "type": "page",
  "version": "0.4",
  "page_settings": {},
  "content": []
}
```

- `type`: Document type (`page`, `header`, `footer`, `single-post`, `archive`, `popup`).
- `version`: Always `"0.4"`.
- `page_settings`: Document-level settings (e.g., page title display, custom body classes).
- `content`: Array of root elements forming the layout tree.

---

## 2. Container Element (`elType: "container"`)

Elementor modern layout completely replaces legacy Sections and Columns with Flexbox/CSS Grid **Containers**.

```json
{
  "id": "c1a234ef",
  "elType": "container",
  "isInner": false,
  "settings": {
    "content_width": "boxed",
    "width": { "unit": "px", "size": 1200, "sizes": [] },
    "flex_direction": "row",
    "flex_justify_content": "space-between",
    "flex_align_items": "center",
    "flex_gap": { "unit": "px", "size": 24, "sizes": [] },
    "padding": {
      "unit": "px",
      "top": "40",
      "right": "20",
      "bottom": "40",
      "left": "20",
      "isLinked": false
    }
  },
  "elements": []
}
```

---

## 3. Widget Element (`elType: "widget"`)

Widgets live inside container `elements` arrays.

```json
{
  "id": "w8765432",
  "elType": "widget",
  "widgetType": "heading",
  "isInner": false,
  "settings": {
    "title": "Welcome to Modern WordPress",
    "header_size": "h2",
    "align": "left",
    "title_color": "#111827"
  },
  "elements": []
}
```

### Widget with Repeater Data (e.g., Feature List)

```json
{
  "id": "w9988776",
  "elType": "widget",
  "widgetType": "icon-list",
  "isInner": false,
  "settings": {
    "icon_list": [
      {
        "_id": "rep_101",
        "text": "100% PHP 8.2+ Typed",
        "selected_icon": { "value": "fas fa-check", "library": "fa-solid" }
      },
      {
        "_id": "rep_102",
        "text": "Modern Flexbox Containers",
        "selected_icon": { "value": "fas fa-check", "library": "fa-solid" }
      }
    ]
  },
  "elements": []
}
```

---

## 4. Responsive Properties

Elementor stores responsive variations using breakpoint suffixes appended to control keys:
- Desktop: `control_name`
- Tablet: `control_name_tablet`
- Mobile: `control_name_mobile`

```json
{
  "settings": {
    "flex_direction": "row",
    "flex_direction_tablet": "column",
    "flex_direction_mobile": "column",
    "padding": { "unit": "px", "top": "60", "right": "30", "bottom": "60", "left": "30" },
    "padding_mobile": { "unit": "px", "top": "24", "right": "16", "bottom": "24", "left": "16" }
  }
}
```

---

## 5. Storage in WordPress Database (`wp_postmeta`)

Elementor pages and templates are WordPress posts (post type `page`, `post`, or `elementor_library`). The layout is stored as custom fields:

| Meta Key | Value Format | Purpose |
| :--- | :--- | :--- |
| `_elementor_data` | JSON-encoded string | The full hierarchical element tree (array of container objects) |
| `_elementor_edit_mode` | String (`"builder"`) | Flags whether page was authored in Elementor |
| `_elementor_template_type` | String | Type slug (`page`, `header`, `footer`, `kit`) |
| `_elementor_version` | String (e.g. `"3.25.0"`) | Version of Elementor that saved the post |
| `_elementor_page_settings` | Serialized PHP array | Document-level configuration settings |

### Programmatic Reading in PHP

```php
declare(strict_types=1);

namespace Agency\Core\Elementor;

function get_elementor_layout( int $post_id ): ?array {
    $raw_data = get_post_meta( $post_id, '_elementor_data', true );

    if ( empty( $raw_data ) || ! is_string( $raw_data ) ) {
        return null;
    }

    $decoded = json_decode( $raw_data, true );
    return is_array( $decoded ) ? $decoded : null;
}
```
