<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Elementor\IO;

use Exception;
use WPAIOS\Contracts\LoggerInterface;
use WPAIOS\Modules\Elementor\Validation\ElementorValidator;

/**
 * Import Manager — deserializes Elementor JSON into live WordPress pages.
 */
class ImportManager
{
    public function __construct(
        private ElementorValidator $validator,
        private LoggerInterface $logger
    ) {
    }

    /**
     * Import a JSON string as a new Elementor page.
     *
     * @param string $json
     * @param string $status 'publish' | 'draft'
     * @return int  New post ID.
     * @throws Exception
     */
    public function importFromJson(string $json, string $status = 'draft'): int
    {
        $data = json_decode($json, true);
        if (!is_array($data)) {
            throw new Exception('Invalid JSON payload for Elementor import.');
        }

        $content = $data['content'] ?? [];
        $title = $data['title'] ?? 'Imported Page';
        $pageSettings = $data['page_settings'] ?? [];

        // Validate structure
        $errors = $this->validator->validateContent($content);
        if (!empty($errors)) {
            throw new Exception('Import validation failed: ' . implode(', ', $errors));
        }

        $postId = wp_insert_post([
            'post_title' => sanitize_text_field($title),
            'post_type' => 'page',
            'post_status' => $status,
            'post_content' => '',
        ]);

        if (is_wp_error($postId)) {
            throw new Exception('Failed to create page during import: ' . $postId->get_error_message());
        }

        update_post_meta($postId, '_elementor_data', wp_json_encode($content));
        update_post_meta($postId, '_elementor_edit_mode', 'builder');
        update_post_meta($postId, '_elementor_version', '3.21.0');
        update_post_meta($postId, '_elementor_page_settings', $pageSettings);

        $this->logger->info(sprintf('[ImportManager] Imported Elementor page [%s] as post ID %d.', $title, $postId));

        return $postId;
    }
}
