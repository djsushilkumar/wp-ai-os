<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\Admin;

use WPAIOS\Modules\Builders\BuildersManager;

/**
 * Class BuildersAdminDashboard
 * Admin screen for Multi-Builder Abstraction status.
 */
class BuildersAdminDashboard
{
    public function __construct(private BuildersManager $manager)
    {
    }

    public function registerMenu(): void
    {
        if (function_exists('add_submenu_page')) {
            add_submenu_page(
                'wp-ai-os-status',
                __('Multi-Builder Matrix', 'wp-ai-os'),
                __('Multi-Builder', 'wp-ai-os'),
                'manage_options',
                'wp-ai-os-builders',
                [$this, 'renderPage']
            );
        }
    }

    public function renderPage(): void
    {
        if (function_exists('current_user_can') && !current_user_can('manage_options')) {
            wp_die(esc_html__('Security Check: Permission denied.', 'wp-ai-os'));
        }

        $report = $this->manager->getRegistry()->detect();

        echo '<div class="wrap">';
        echo '<h1>' . esc_html__('WP AI OS — Multi-Builder & Theme Matrix', 'wp-ai-os') . '</h1>';
        echo '<p>' . esc_html__('Unified Builder & Theme Abstraction status dashboard.', 'wp-ai-os') . '</p>';

        echo '<table class="widefat fixed striped" style="max-width:900px; margin-top:20px;">';
        echo '<thead><tr><th>Builder Engine</th><th>Status</th><th>Version</th><th>Key Capabilities</th></tr></thead>';
        echo '<tbody>';

        foreach ($report as $b) {
            $statusStr = $b['active'] ? 'Active' : ($b['installed'] ? 'Installed' : 'Unavailable (Stub)');
            echo '<tr>';
            echo '<td><strong>' . esc_html($b['name']) . '</strong> (' . esc_html($b['slug']) . ')</td>';
            echo '<td><span class="dashicons dashicons-' . ($b['active'] ? 'yes-alt' : 'minus') . '"></span> ' . esc_html($statusStr) . '</td>';
            echo '<td>' . esc_html($b['version'] ?? 'N/A') . '</td>';
            echo '<td>' . esc_html($b['active'] ? 'Full Abstraction' : 'Detection Only') . '</td>';
            echo '</tr>';
        }

        echo '</tbody></table>';
        echo '</div>';
    }
}
