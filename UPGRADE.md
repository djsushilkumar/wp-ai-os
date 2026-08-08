# UPGRADE.md — WP AI OS Upgrade Guide

**Version**: `v1.0.0` (Initial Release)  

---

## Upgrade Overview

**WP AI OS v1.0.0** is the initial major production release. Future minor (`v1.x.x`) and major (`v2.0.0`) upgrades will follow semantic versioning conventions.

---

## Upgrade Procedures

### Automatic Upgrade via WordPress Admin

1. When a new version is released, an update notification will appear under **Plugins** → **Installed Plugins**.
2. Click **Update Now**.
3. WP AI OS automatically executes schema migrations via `UpgradeManager` during activation without data loss.

### Upgrading from Alpha / Beta Pre-Releases

If upgrading from a pre-release version:

1. **Backup Database**: Create a database backup of tables starting with `wp_ai_os_`.
2. **Deactivate Previous Version**: Go to **Plugins** → **Deactivate**.
3. **Upload New Package**: Upload `WP-AI-OS-v1.0.0.zip` via **Plugins** → **Add New** → **Upload Plugin**. Select **Overwrite existing plugin**.
4. **Activate**: Click **Activate Plugin**. `PluginActivator` automatically runs database delta migrations (`dbDelta()`) to ensure schema compatibility.
