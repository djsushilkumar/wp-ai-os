# Elementor Automation Engine — WP AI OS

## Architecture

WP AI OS interacts with Elementor pages exclusively through **structured JSON AST** objects. The AI generates JSON definitions, the **PageBuilder** converts them into Elementor-native `_elementor_data` structures, and the **PageApi** writes them to the database.

```
AI JSON Definition
       ↓
   PageBuilder              (JSON → Elementor AST)
       ↓
   ElementorValidator       (Schema validation)
       ↓
   RevisionManager          (Auto-snapshot before mutation)
       ↓
   PageApi                  (_elementor_data post meta write)
       ↓
   Elementor renders page
```

---

## Page AST JSON Format

The AI generates a **page definition** JSON structure with a `sections` array:

```json
{
    "title": "Landing Page",
    "sections": [
        {
            "type": "container",
            "flex_direction": "column",
            "content_width": "boxed",
            "settings": {},
            "children": [
                {
                    "type": "widget",
                    "widget_type": "heading",
                    "settings": {
                        "title": "Welcome to WP AI OS",
                        "header_size": "h1",
                        "align": "center"
                    }
                },
                {
                    "type": "widget",
                    "widget_type": "text-editor",
                    "settings": {
                        "editor": "<p>Build beautiful WordPress sites with AI automation.</p>"
                    }
                },
                {
                    "type": "widget",
                    "widget_type": "button",
                    "settings": {
                        "text": "Get Started",
                        "link": { "url": "/get-started" },
                        "align": "center"
                    }
                }
            ]
        }
    ]
}
```

---

## Supported Element Types

| Type | Description |
| :--- | :--- |
| `container` | Elementor Flexbox Container (v3.6+) |
| `section` + `column` | Legacy Section/Column layout |
| `widget` | Any registered Elementor widget |

---

## Supported Widget Types

| Widget | `widget_type` |
| :--- | :--- |
| Heading | `heading` |
| Text Editor | `text-editor` |
| Button | `button` |
| Image | `image` |

Additional widgets can be registered via **WidgetRegistry::register()**.
