# Enterprise Media Platform — WP AI OS

## Overview

The **Enterprise Media Platform** in WP AI OS provides secure, provider-independent media management, safe file uploads, MIME type validation, and attachment metadata handling for WordPress.

```
       +------------------------------------+
       |  WP AI OS Core & Agent Abilities   |
       +------------------------------------+
                         |
           +-------------+-------------+
           |     Media Platform Layer   |
           +-------------+-------------+
                         |
      +------------------+------------------+
      |                  |                  |
      v                  v                  v
 UploadManager    MetadataManager    MediaRepository
```

---

## Features

1. **Safe File Uploads**: Validates MIME types against strict allowlist before moving files into `wp-upload-dir`.
2. **Metadata Management**: Updates ALT text (`_wp_attachment_image_alt`), captions, titles, and descriptions.
3. **MCP Agent Abilities**: `wp_ai_os_media_upload` and `wp_ai_os_media_metadata` tools allow AI agents to manage media assets safely.
