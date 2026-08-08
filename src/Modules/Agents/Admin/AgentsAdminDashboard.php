<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Agents\Admin;

use WPAIOS\Modules\Agents\AgentsManager;

/**
 * Class AgentsAdminDashboard
 * Admin dashboard for Multi-Agent Orchestration & Human Approvals.
 */
class AgentsAdminDashboard
{
    public function __construct(private AgentsManager $manager)
    {
    }

    public function registerMenu(): void
    {
        if (function_exists('add_submenu_page')) {
            add_submenu_page(
                'wp-ai-os-status',
                __('Agent Orchestration', 'wp-ai-os'),
                __('Agent System', 'wp-ai-os'),
                'manage_options',
                'wp-ai-os-agents',
                [ $this, 'renderPage' ]
            );
        }
    }

    public function renderPage(): void
    {
        if (function_exists('current_user_can') && ! current_user_can('manage_options')) {
            wp_die(esc_html__('Security Check: Permission denied.', 'wp-ai-os'));
        }

        $agents  = $this->manager->getRegistry()->listSummary();
        $pending = $this->manager->getApprovalManager()->getPendingApprovals();

        echo '<div class="wrap">';
        echo '<h1>' . esc_html__('WP AI OS — Multi-Agent Orchestration', 'wp-ai-os') . '</h1>';
        echo '<p>' . esc_html__('Policy-gated multi-agent execution & human approval queue.', 'wp-ai-os') . '</p>';

        if (! empty($pending)) {
            echo '<div class="notice notice-warning"><p><strong>' . count($pending) . ' CRITICAL task(s) awaiting human approval!</strong></p></div>';
        }

        echo '<h2>Registered Agents (' . count($agents) . ')</h2>';
        echo '<table class="widefat fixed striped" style="max-width:900px;">';
        echo '<thead><tr><th>Agent</th><th>Role</th><th>Risk Level</th><th>Description</th></tr></thead>';
        echo '<tbody>';

        foreach ($agents as $a) {
            echo '<tr>';
            echo '<td><strong>' . esc_html($a['name']) . '</strong> (' . esc_html($a['id']) . ')</td>';
            echo '<td>' . esc_html($a['role']) . '</td>';
            echo '<td><span class="badge badge-' . esc_attr(strtolower($a['risk_level'])) . '">' . esc_html($a['risk_level']) . '</span></td>';
            echo '<td>' . esc_html($a['description']) . '</td>';
            echo '</tr>';
        }

        echo '</tbody></table>';
        echo '</div>';
    }
}
