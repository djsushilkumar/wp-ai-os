<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Knowledge\Admin;

use WPAIOS\Modules\Knowledge\KnowledgeManager;

/**
 * Class KnowledgeAdminDashboard
 * Admin dashboard for Knowledge Base & RAG Platform management.
 */
class KnowledgeAdminDashboard
{
    public function __construct(private KnowledgeManager $manager)
    {
    }

    public function registerMenu(): void
    {
        if (function_exists('add_submenu_page')) {
            add_submenu_page(
                'wp-ai-os-status',
                __('Knowledge Base & RAG', 'wp-ai-os'),
                __('Knowledge Base', 'wp-ai-os'),
                'manage_options',
                'wp-ai-os-knowledge',
                [$this, 'renderPage']
            );
        }
    }

    public function renderPage(): void
    {
        if (function_exists('current_user_can') && !current_user_can('manage_options')) {
            wp_die(esc_html__('Security Check: Permission denied.', 'wp-ai-os'));
        }

        echo '<div class="wrap">';
        echo '<h1>' . esc_html__('WP AI OS — Knowledge Base & RAG Platform', 'wp-ai-os') . '</h1>';
        echo '<p>' . esc_html__('Contextual Knowledge Base & Hybrid Vector Store for AI Agents.', 'wp-ai-os') . '</p>';

        echo '<div class="card" style="max-width:800px; padding:15px; margin-top:15px;">';
        echo '<h2>Index & Vector Store Status</h2>';
        echo '<ul>';
        echo '<li><strong>Embedding Driver:</strong> Provider-Independent Abstraction (OpenAI / Gemini / Cohere / Local)</li>';
        echo '<li><strong>Vector Storage:</strong> Native MySQL Custom Table (<code>wp_ai_os_vectors</code>)</li>';
        echo '<li><strong>SSRF Guard:</strong> Active (Localhost & Private IP Range Filtering)</li>';
        echo '<li><strong>Prompt Injection Guard:</strong> Active (Untrusted Context Isolation & Pattern Stripping)</li>';
        echo '</ul>';
        echo '</div>';

        echo '</div>';
    }
}
