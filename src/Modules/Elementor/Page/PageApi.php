<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Elementor\Page;

use Exception;
use WPAIOS\Contracts\LoggerInterface;

/**
 * Elementor Page API — handles Create, Read, Update, Delete, Publish,
 * Draft, Duplicate, and Rollback operations on Elementor pages.
 */
class PageApi
{
    private const META_ELEMENTOR_DATA = '_elementor_data';
    private const META_ELEMENTOR_EDIT_MODE = '_elementor_edit_mode';
    private const META_ELEMENTOR_VERSION = '_elementor_version';

    public function __construct(private LoggerInterface $logger)
    {
    }

    /**
     * Create a new Elementor page with the given AST structure.
     *
     * @param string $title            Page title.
     * @param array<string, mixed> $ast Elementor page AST (content array).
     * @param string $status           'publish' | 'draft' | 'private'
     * @return int  New post ID.
     * @throws Exception
     */
    public function createPage(string $title, array $ast, string $status = 'publish'): int
    {
        $this->requireWordPressFunctions();

        $postId = wp_insert_post([
            'post_title' => sanitize_text_field($title),
            'post_type' => 'page',
            'post_status' => $status,
            'post_content' => '',
        ]);

        if (is_wp_error($postId)) {
            throw new Exception('Page creation failed: ' . $postId->get_error_message());
        }

        $this->writeElementorData($postId, $ast);
        $this->logger->info(sprintf('[PageApi] Created page [%s] with ID %d.', $title, $postId));

        return $postId;
    }

    /**
     * Read existing Elementor page AST by post ID.
     *
     * @param int $postId
     * @return array<string, mixed>|null
     */
    public function getPage(int $postId): ?array
    {
        $raw = get_post_meta($postId, self::META_ELEMENTOR_DATA, true);
        if (empty($raw)) {
            return null;
        }

        $data = json_decode($raw, true);
        return is_array($data) ? $data : null;
    }

    /**
     * Update page title, status, and/or Elementor AST data.
     *
     * @param int $postId
     * @param array<string, mixed> $ast
     * @param string|null $title
     * @param string|null $status
     * @return bool
     * @throws Exception
     */
    public function updatePage(int $postId, array $ast, ?string $title = null, ?string $status = null): bool
    {
        $this->requireWordPressFunctions();

        $args = ['ID' => $postId];
        if ($title !== null) {
            $args['post_title'] = sanitize_text_field($title);
        }
        if ($status !== null) {
            $args['post_status'] = $status;
        }

        $result = wp_update_post($args);
        if (is_wp_error($result)) {
            throw new Exception('Page update failed: ' . $result->get_error_message());
        }

        $this->writeElementorData($postId, $ast);
        $this->logger->info(sprintf('[PageApi] Updated page ID %d.', $postId));

        return true;
    }

    /**
     * Duplicate an existing Elementor page.
     *
     * @param int $sourcePostId
     * @param string $newTitle
     * @param string $status
     * @return int  New duplicated post ID.
     * @throws Exception
     */
    public function duplicatePage(int $sourcePostId, string $newTitle = '', string $status = 'draft'): int
    {
        $this->requireWordPressFunctions();

        $source = get_post($sourcePostId);
        if (!$source) {
            throw new Exception(sprintf('Source page ID %d not found.', $sourcePostId));
        }

        $title = !empty($newTitle) ? $newTitle : $source->post_title . ' (Copy)';
        $ast = $this->getPage($sourcePostId) ?? [];

        return $this->createPage($title, $ast, $status);
    }

    /**
     * Delete an Elementor page permanently.
     *
     * @param int $postId
     * @return bool
     * @throws Exception
     */
    public function deletePage(int $postId): bool
    {
        $this->requireWordPressFunctions();

        $result = wp_delete_post($postId, true);
        if (!$result) {
            throw new Exception(sprintf('Failed to delete page ID %d.', $postId));
        }

        $this->logger->info(sprintf('[PageApi] Deleted page ID %d permanently.', $postId));
        return true;
    }

    /**
     * Publish a draft Elementor page.
     *
     * @param int $postId
     * @return bool
     */
    public function publishPage(int $postId): bool
    {
        wp_update_post(['ID' => $postId, 'post_status' => 'publish']);
        return true;
    }

    /**
     * Revert page to draft.
     *
     * @param int $postId
     * @return bool
     */
    public function draftPage(int $postId): bool
    {
        wp_update_post(['ID' => $postId, 'post_status' => 'draft']);
        return true;
    }

    /**
     * Write Elementor AST data to post meta.
     *
     * @param int $postId
     * @param array<string, mixed> $ast
     * @return void
     */
    private function writeElementorData(int $postId, array $ast): void
    {
        $encoded = wp_json_encode($ast['content'] ?? $ast);
        update_post_meta($postId, self::META_ELEMENTOR_DATA, $encoded);
        update_post_meta($postId, self::META_ELEMENTOR_EDIT_MODE, 'builder');
        update_post_meta($postId, self::META_ELEMENTOR_VERSION, '3.21.0');
    }

    /**
     * @throws Exception
     */
    private function requireWordPressFunctions(): void
    {
        if (!function_exists('wp_insert_post')) {
            throw new Exception('WordPress functions are not available.');
        }
    }
}
