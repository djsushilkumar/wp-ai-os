<?php

declare(strict_types=1);

namespace WPAIOS\Services;

/**
 * Plugin Activator Service — creates custom database tables and migration version tracking via dbDelta().
 */
class PluginActivator
{
    public function activate(): void
    {
        $this->createCustomTables();
        $this->setInitialOptions();
    }

    private function createCustomTables(): void
    {
        global $wpdb;

        if (!function_exists('dbDelta')) {
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        }

        $charsetCollate = $wpdb->get_charset_collate();

        // 1. Audit Log Table
        $tableAuditLog = $wpdb->prefix . 'wp_ai_os_audit_log';
        $sqlAuditLog = "CREATE TABLE {$tableAuditLog} (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            event_name varchar(100) NOT NULL,
            user_id bigint(20) unsigned NOT NULL DEFAULT 0,
            payload longtext NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id),
            KEY event_name (event_name),
            KEY user_id (user_id)
        ) {$charsetCollate};";

        // 2. Workflow Queue Table (Replaces wp_options)
        $tableQueue = $wpdb->prefix . 'wp_ai_os_workflow_queue';
        $sqlQueue = "CREATE TABLE {$tableQueue} (
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
        ) {$charsetCollate};";

        // 3. Checkpoints Table (Replaces wp_options)
        $tableCheckpoints = $wpdb->prefix . 'wp_ai_os_checkpoints';
        $sqlCheckpoints = "CREATE TABLE {$tableCheckpoints} (
            run_id varchar(64) NOT NULL,
            workflow_id varchar(100) NOT NULL,
            label varchar(100) NOT NULL,
            saved_at bigint(20) unsigned NOT NULL,
            snapshot_data longtext NOT NULL,
            PRIMARY KEY  (run_id)
        ) {$charsetCollate};";

        dbDelta($sqlAuditLog);
        dbDelta($sqlQueue);
        dbDelta($sqlCheckpoints);
    }

    private function setInitialOptions(): void
    {
        if (function_exists('update_option')) {
            update_option('wp_ai_os_db_version', '1.1.0');
        }
    }
}
