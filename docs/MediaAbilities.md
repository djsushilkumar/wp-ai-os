# Media MCP Abilities Specification — WP AI OS

## 1. Media Upload Ability (`wp_ai_os_media_upload`)

### Actions Supported
- `upload`: Upload a local file into the WordPress Media Library (file_path, title, alt_text)
- `get`: Retrieve attachment details by ID
- `delete`: Delete attachment permanently by ID
- `list`: List attachment items with pagination

---

## 2. Media Metadata Ability (`wp_ai_os_media_metadata`)

### Actions Supported
- `get`: Retrieve ALT text, title, caption, description by attachment ID
- `update`: Update ALT text, title, caption, or description
