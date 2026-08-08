<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Mcp\Admin;

use WPAIOS\Modules\Mcp\Services\McpManager;

/**
 * WP-Admin Status Dashboard Page for MCP Integration.
 */
class McpStatusDashboard
{
    public function __construct(private McpManager $mcpManager)
    {
    }

    public function registerHooks(): void
    {
        if (function_exists('add_action')) {
            add_action('admin_menu', [$this, 'addAdminMenu']);
        }
    }

    public function addAdminMenu(): void
    {
        if (function_exists('add_menu_page')) {
            add_menu_page(
                __('WP AI OS Status', 'wp-ai-os'),
                __('WP AI OS', 'wp-ai-os'),
                'manage_options',
                'wp-ai-os-status',
                [$this, 'renderDashboard'],
                'dashicons-superhero',
                30
            );
        }
    }

    public function renderDashboard(): void
    {
        if (function_exists('current_user_can') && !current_user_can('manage_options')) {
            wp_die(esc_html__('Security Check: You do not have sufficient permissions to access this page.', 'wp-ai-os'));
        }

        // Validate nonce if form actions submitted
        if (isset($_POST['wp_ai_os_action'])) {
            if (!function_exists('check_admin_referer') || !check_admin_referer('wp_ai_os_mcp_dashboard_action', '_wpnonce')) {
                wp_die(esc_html__('Security Check: Invalid CSRF nonce.', 'wp-ai-os'));
            }
        }

        $detected = $this->mcpManager->detectMcpPlugin();
        $fallback = $this->mcpManager->isFallbackMode();
        $nonce = function_exists('wp_create_nonce') ? wp_create_nonce('wp_ai_os_mcp_dashboard_action') : '';

        echo '<div class="wrap">';
        echo '<h1>' . esc_html__('WP AI OS — MCP Integration Status', 'wp-ai-os') . '</h1>';

        if ($detected) {
            echo '<div class="notice notice-success"><p>' .
                esc_html__('WordPress Agent Abilities plugin detected! First-class MCP extensions are ACTIVE.', 'wp-ai-os') .
                '</p></div>';
        } else {
            echo '<div class="notice notice-warning"><p>' .
                esc_html__('WordPress Agent Abilities plugin missing. WP AI OS is running safely in standalone mode.', 'wp-ai-os') .
                '</p></div>';
        }

        echo '<table class="widefat fixed striped" style="max-width:800px; margin-top:20px;">';
        echo '<thead><tr><th>' . esc_html__('Component', 'wp-ai-os') . '</th><th>' . esc_html__('Status', 'wp-ai-os') . '</th></tr></thead>';
        echo '<tbody>';
        echo '<tr><td>' . esc_html__('MCP Core Bridge', 'wp-ai-os') . '</td><td>' . esc_html($detected ? 'Connected' : 'Standalone Fallback') . '</td></tr>';
        echo '<tr><td>' . esc_html__('Fallback Mode Active', 'wp-ai-os') . '</td><td>' . esc_html($fallback ? 'Yes (Safe)' : 'No (Connected)') . '</td></tr>';
        echo '<tr><td>' . esc_html__('PHP Runtime Version', 'wp-ai-os') . '</td><td>' . esc_html(PHP_VERSION) . '</td></tr>';
        echo '</tbody></table>';

        echo '<form method="post" style="margin-top:20px;">';
        echo '<input type="hidden" name="_wpnonce" value="' . esc_attr($nonce) . '">';
        echo '<input type="hidden" name="wp_ai_os_action" value="refresh_status">';
        echo '<button type="submit" class="button button-primary">' . esc_html__('Refresh System Status', 'wp-ai-os') . '</button>';
        echo '</form>';

        echo '</div>';
    }
}
