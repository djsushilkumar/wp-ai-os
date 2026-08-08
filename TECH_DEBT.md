# WP AI OS — Technical Debt & Refactoring Inventory

## Technical Debt Inventory

| Item ID | Component | Description | Impact | Priority |
| :--- | :--- | :--- | :--- | :--- |
| **TD-01** | `PostManagerAbility` | Line 69/70 uses `undefined` constant instead of `null`. | PHP 8.2 Warning/Notice | **HIGH** |
| **TD-02** | `WorkflowQueue` | Stores queue items in `wp_options` array without limit capping. | `wp_options` DB bloat | **HIGH** |
| **TD-03** | `KeyEncryptor` | Falls back to static string if `AUTH_KEY` is undefined. | Insecure Encryption Salt | **CRITICAL**|
| **TD-04** | `TaskExecutor` | Synchronous `usleep()` blocking during retry delays. | PHP-FPM thread holding | **MEDIUM** |
| **TD-05** | `McpStatusDashboard`| Dashboard rendering lacks explicit CSRF nonce check. | Minor CSRF risk | **MEDIUM** |

---

## 🧹 Code Smell & Duplicate Logic Analysis

1. **Duplicate Option Key Pre-fixes**:
   - `wp_ai_os_api_key_*`
   - `wp_ai_os_workflow_queue`
   - `wp_ai_os_checkpoint_*`
   *Refactoring*: Move option key string definitions into a centralized `WPAIOS\Support\Constants` class.

2. **WordPress Function Availability Boilerplate**:
   - `PageApi`, `CronAbility`, `PluginInfoAbility`, `SiteInfoAbility` all independently check `if (function_exists(...))`.
   *Refactoring*: Create a unified `WPAIOS\Support\WordPressBridge` service to encapsulate WordPress core function availability checks across all modules cleanly.
