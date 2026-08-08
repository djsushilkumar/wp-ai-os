<?php

declare(strict_types=1);

namespace WPAIOS\Services;

use WPAIOS\Contracts\DeactivatorInterface;

/**
 * Plugin Deactivator Service executing deactivation tasks.
 */
class PluginDeactivator implements DeactivatorInterface
{
    /**
     * Run plugin deactivation routines (clear crons, flush rewrite rules).
     *
     * @return void
     */
    public function deactivate(): void
    {
        if (function_exists('wp_clear_scheduled_hook')) {
            wp_clear_scheduled_hook('wp_ai_os_cron_maintenance');
        }

        if (function_exists('flush_rewrite_rules')) {
            flush_rewrite_rules();
        }
    }
}
