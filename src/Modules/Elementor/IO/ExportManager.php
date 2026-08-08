<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Elementor\IO;

use Exception;

/**
 * Export Manager — serializes Elementor page AST to JSON for template exchange.
 */
class ExportManager
{
    /**
     * Export an Elementor page to a JSON string.
     *
     * @param int $postId  Page post ID.
     * @return string  JSON string ready for download or storage.
     * @throws Exception
     */
    public function exportPage(int $postId): string
    {
        $post = get_post($postId);
        if (!$post) {
            throw new Exception(sprintf('Post ID %d not found.', $postId));
        }

        $elementorData = get_post_meta($postId, '_elementor_data', true);
        $pageSettings = get_post_meta($postId, '_elementor_page_settings', true);

        $export = [
            'version' => '0.4',
            'title' => $post->post_title,
            'type' => $post->post_type,
            'exported_at' => gmdate('Y-m-d\TH:i:s\Z'),
            'source' => 'WP AI OS',
            'content' => json_decode($elementorData ?: '[]', true),
            'page_settings' => is_array($pageSettings) ? $pageSettings : [],
        ];

        $json = wp_json_encode($export, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if (!$json) {
            throw new Exception('Failed to encode Elementor page to JSON.');
        }

        return $json;
    }

    /**
     * Export an Elementor template (header, footer, popup) to JSON.
     *
     * @param int $templateId  Elementor template post ID.
     * @return string  JSON export string.
     * @throws Exception
     */
    public function exportTemplate(int $templateId): string
    {
        return $this->exportPage($templateId);
    }
}
