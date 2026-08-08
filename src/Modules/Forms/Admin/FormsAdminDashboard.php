<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Forms\Admin;

use WPAIOS\Modules\Forms\FormsManager;

/**
 * Class FormsAdminDashboard
 * Admin menu dashboard for Forms platform status.
 */
class FormsAdminDashboard
{
    public function __construct(private FormsManager $manager)
    {
    }

    public function registerMenu(): void
    {
        if (function_exists('add_submenu_page')) {
            add_submenu_page(
                'wp-ai-os-status',
                __('Forms Subsystem', 'wp-ai-os'),
                __('Forms Platform', 'wp-ai-os'),
                'manage_options',
                'wp-ai-os-forms',
                [$this, 'renderPage']
            );
        }
    }

    public function renderPage(): void
    {
        if (function_exists('current_user_can') && !current_user_can('manage_options')) {
            wp_die(esc_html__('Security Check: Permission denied.', 'wp-ai-os'));
        }

        $providers = $this->manager->getDiscovery()->discoverProviders();

        echo '<div class="wrap">';
        echo '<h1>' . esc_html__('WP AI OS — Enterprise Forms Platform', 'wp-ai-os') . '</h1>';
        echo '<p>' . esc_html__('Unified provider-independent form engine and discovery.', 'wp-ai-os') . '</p>';

        echo '<table class="widefat fixed striped" style="max-width:900px; margin-top:20px;">';
        echo '<thead><tr><th>Provider</th><th>Status</th><th>Version</th><th>Capabilities</th></tr></thead>';
        echo '<tbody>';

        foreach ($providers as $p) {
            echo '<tr>';
            echo '<td><strong>' . esc_html($p['name']) . '</strong> (' . esc_html($p['slug']) . ')</td>';
            echo '<td><span class="dashicons dashicons-' . ('active' === $p['status'] ? 'yes-alt' : 'minus') . '"></span> ' . esc_html(strtoupper($p['status'])) . '</td>';
            echo '<td>' . esc_html($p['version'] ?? 'N/A') . '</td>';
            echo '<td>' . esc_html(implode(', ', array_keys(array_filter($p['capabilities'])))) . '</td>';
            echo '</tr>';
        }

        echo '</tbody></table>';
        echo '</div>';
    }
}
