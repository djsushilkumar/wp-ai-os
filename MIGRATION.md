# WP AI OS — Database Migration & Schema Specification

## Schema Overview (v1.1.0)

All custom database tables are created via `dbDelta()` in `PluginActivator.php`.

---

## Table 1: Audit Log (`{$wpdb->prefix}wp_ai_os_audit_log`)

```sql
CREATE TABLE {$wpdb->prefix}wp_ai_os_audit_log (
    id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    event_name varchar(100) NOT NULL,
    user_id bigint(20) unsigned NOT NULL DEFAULT 0,
    payload longtext NOT NULL,
    created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
    PRIMARY KEY  (id),
    KEY event_name (event_name),
    KEY user_id (user_id)
) {$charsetCollate};
```

---

## Table 2: Workflow Queue (`{$wpdb->prefix}wp_ai_os_workflow_queue`)

```sql
CREATE TABLE {$wpdb->prefix}wp_ai_os_workflow_queue (
    id varchar(64) NOT NULL,
    workflow_id varchar(100) NOT NULL,
    input longtext NOT NULL,
    priority int(11) NOT NULL DEFAULT 10,
    status varchar(30) NOT NULL DEFAULT 'pending',
    attempts int(11) NOT NULL DEFAULT 0,
    max_attempts int(11) NOT NULL DEFAULT 3,
    run_after bigint(20) unsigned DEFAULT NULL,
    created_at bigint(20) unsigned NOT NULL,
    error text DEFAULT NULL,
    PRIMARY KEY  (id),
    KEY status_priority (status, priority, created_at)
) {$charsetCollate};
```

---

## Table 3: Checkpoint Memory (`{$wpdb->prefix}wp_ai_os_checkpoints`)

```sql
CREATE TABLE {$wpdb->prefix}wp_ai_os_checkpoints (
    run_id varchar(64) NOT NULL,
    workflow_id varchar(100) NOT NULL,
    label varchar(100) NOT NULL,
    saved_at bigint(20) unsigned NOT NULL,
    snapshot_data longtext NOT NULL,
    PRIMARY KEY  (run_id)
) {$charsetCollate};
```

---

## Rollback & Migration Safety

On deactivation (`PluginDeactivator.php`), tables remain intact to preserve execution logs. On uninstall, options `wp_ai_os_db_version` and custom tables are removed if requested.
