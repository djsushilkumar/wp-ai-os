# REAL-USER-TESTING.md — Persona & User Journey Testing Logs

**Audit Date**: August 10, 2026  
**Target Codebase**: `WP AI OS v1.0.0`  

---

## Persona 1: Normal WordPress Administrator (`devadmin`)

### Test Journey: Fresh Installation & Admin Dashboard
- **Steps**:
  1. Upload `WP-AI-OS-v1.0.0.zip` via WordPress Admin or extract to `wp-content/plugins/wp-ai-os/`.
  2. Click **Activate Plugin**.
  3. Navigate to **WP AI OS** menu item in admin sidebar.
- **Expected Outcome**:
  - Activation succeeds cleanly.
  - Custom database tables are generated automatically.
  - Admin dashboard displays system health and MCP connection status.
- **Actual Outcome**:
  - **Status: PASS**
  - Database tables `wp_wp_ai_os_audit_log`, `wp_wp_ai_os_workflow_queue`, and `wp_wp_ai_os_checkpoints` created.
  - Option `wp_ai_os_db_version` set to `1.1.0`.
  - Admin page displays status notice: `"WordPress Agent Abilities plugin missing. WP AI OS is running safely in standalone mode."`

---

## Persona 2: Website Builder / Freelancer (`devadmin`)

### Test Journey: MCP Connection & Ability Discovery
- **Steps**:
  1. Authenticate REST API request to `/wp-abilities/v1/abilities`.
  2. Inspect available abilities.
  3. Execute `core/get-site-info` via GET request.
- **Expected Outcome**:
  - Unauthenticated requests return `401 Unauthorized`.
  - Authenticated requests return list of registered abilities.
  - Executing `core/get-site-info` returns site metadata.
- **Actual Outcome**:
  - **Status: PASS**
  - Unauthenticated request: `401 Unauthorized`.
  - Authenticated request: `200 OK`, returning `core/get-site-info` and `core/get-environment-info`.
  - Ability execution output:
    ```json
    {
      "name": "Celestial Contractor & Engineer Pvt. Ltd.",
      "url": "http://localhost",
      "version": "7.0.3"
    }
    ```

---

## Persona 3: WooCommerce Store Owner (`devadmin`)

### Test Journey: E-Commerce Capability Fallback
- **Steps**:
  1. Run plugin without WooCommerce active.
  2. Check for PHP errors or broken administration UI.
- **Expected Outcome**:
  - Plugin operates safely in standalone fallback mode without fatal errors.
- **Actual Outcome**:
  - **Status: PASS**
  - No missing class exceptions or undefined function calls when WooCommerce is absent.

---

## Persona 4: Content Manager (`uat_editor`)

### Test Journey: Content Access & Media Integration
- **Steps**:
  1. Log in as `uat_editor`.
  2. Access `/wp-abilities/v1/abilities`.
  3. Attempt to run system diagnostics ability `core/get-environment-info`.
- **Expected Outcome**:
  - Editor can view abilities allowed for their capability level.
  - Restricted administrative system abilities return `403 Forbidden`.
- **Actual Outcome**:
  - **Status: PASS**
  - `core/get-environment-info` returned `403 Forbidden` for Editor user.

---

## Persona 5: SEO Manager (`devadmin`)

### Test Journey: Technical SEO Metadata & Semantic HTML
- **Steps**:
  1. Inspect generated markup and meta tags.
  2. Verify title tags and meta descriptions.
- **Expected Outcome**:
  - Semantic HTML5 structures present.
  - Title tags correctly rendered.
- **Actual Outcome**:
  - **Status: PASS**
  - Header tags and semantic elements conform to WordPress coding standards.

---

## Persona 6: Limited User / Subscriber (`uat_subscriber`)

### Test Journey: Security Boundary & Privilege Escalation Defense
- **Steps**:
  1. Log in as `uat_subscriber`.
  2. Attempt to open Admin Status page (`wp-ai-os-status`).
  3. Attempt to run system administrative abilities.
- **Expected Outcome**:
  - Admin menu access denied.
  - System abilities return `403 Forbidden`.
- **Actual Outcome**:
  - **Status: PASS**
  - `current_user_can('manage_options')` denied access to admin menu.
  - REST ability execution returned `403 Forbidden`.
