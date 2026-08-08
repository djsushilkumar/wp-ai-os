# INSTALLATION.md — WP AI OS Installation Guide

**Version**: `v1.0.0`  

---

## System Requirements

- **PHP**: `8.2` or higher (PHP 8.2 & 8.3 fully supported).
- **WordPress**: `6.4` or higher.
- **Database**: MySQL `8.0+` or MariaDB `10.5+`.
- **PHP Extensions**: `ext-curl`, `ext-json`, `ext-mbstring`, `ext-openssl`, `ext-zip`.

---

## Installation Methods

### Method 1: WordPress Admin Upload (Recommended)

1. Download the release package: `WP-AI-OS-v1.0.0.zip`.
2. Log in to your WordPress Dashboard (`/wp-admin`).
3. Navigate to **Plugins** → **Add New** → **Upload Plugin**.
4. Click **Choose File** and select `WP-AI-OS-v1.0.0.zip`.
5. Click **Install Now**.
6. After installation completes, click **Activate Plugin**.

### Method 2: Manual FTP / SSH Deployment

1. Unzip `WP-AI-OS-v1.0.0.zip` locally to extract the `wp-ai-os` folder.
2. Upload the `wp-ai-os` folder to your WordPress site's `/wp-content/plugins/` directory.
3. Log in to WordPress Admin, go to **Plugins** → **Installed Plugins**.
4. Locate **WP AI OS** and click **Activate**.

---

## Post-Activation & Setup

1. **Verify Installation**: Go to **WP AI OS** in the WP Admin sidebar.
2. **MCP Integration**: Ensure the **WordPress Agent Abilities for MCP** plugin is active for external Agent IDE connectivity. If absent, WP AI OS runs safely in standalone mode.
3. **Configure Providers**: Navigate to **WP AI OS** → **Settings** to add API keys for AI Providers (OpenAI, Anthropic Gemini/Claude, OpenRouter, etc.). API keys are securely encrypted using AES-256-GCM.
