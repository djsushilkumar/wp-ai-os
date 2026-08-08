# Multi-Builder & Theme Abstraction Module — WP AI OS

The **Multi-Builder and Theme Abstraction Module** for **WP AI OS** provides a unified, builder-agnostic page synthesis framework.

## Overview
WP AI OS decouples layout generation from individual page builder implementations:
- **Elementor**: Delegates directly to existing `WPAIOS\Modules\Elementor` services.
- **Gutenberg (Block Editor)**: Native block editor integration via WP core block APIs (`parse_blocks`, `serialize_blocks`).
- **Bricks & Divi**: Verified API capability detection adapters with safe fallback stubs.
- **Theme Abstraction**: Supports both Block Themes (`theme.json`, Block Templates) and Classic Themes.
