<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Media\Services;

use Exception;
use WPAIOS\Contracts\LoggerInterface;
use WPAIOS\Modules\Media\Contracts\MediaRepositoryInterface;
use WPAIOS\Modules\Media\Models\MediaItemModel;

/**
 * Upload Manager Service — safely handles media uploads, MIME type checks, and media library registration.
 */
class UploadManager
{
    private const ALLOWED_MIME_TYPES = [
        'image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/avif', 'image/svg+xml',
        'application/pdf', 'audio/mpeg', 'video/mp4', 'application/zip'
    ];

    public function __construct(
        private MediaRepositoryInterface $repository,
        private LoggerInterface $logger
    ) {
    }

    /**
     * Upload a file into the WordPress Media Library.
     *
     * @param string $filePath Local file path
     * @param string $title
     * @param string $altText
     * @return MediaItemModel
     * @throws Exception
     */
    public function uploadFile(string $filePath, string $title = '', string $altText = ''): MediaItemModel
    {
        if (!file_exists($filePath)) {
            throw new Exception(sprintf('Source file [%s] does not exist.', $filePath));
        }

        $mimeType = mime_content_type($filePath) ?: 'application/octet-stream';
        if (!in_array($mimeType, self::ALLOWED_MIME_TYPES, true)) {
            throw new Exception(sprintf('MIME type [%s] is not permitted for upload.', $mimeType));
        }

        if (!function_exists('wp_insert_attachment')) {
            throw new Exception('WordPress attachment functions unavailable.');
        }

        $filename = basename($filePath);
        $uploadDir = wp_upload_dir();
        $targetPath = $uploadDir['path'] . '/' . $filename;

        copy($filePath, $targetPath);

        $attachment = [
            'post_mime_type' => $mimeType,
            'post_title' => !empty($title) ? sanitize_text_field($title) : sanitize_file_name($filename),
            'post_content' => '',
            'post_status' => 'inherit',
        ];

        $attachId = wp_insert_attachment($attachment, $targetPath);
        if (is_wp_error($attachId) || $attachId === 0) {
            throw new Exception('Failed to insert attachment into WordPress database.');
        }

        require_once ABSPATH . 'wp-admin/includes/image.php';
        $attachData = wp_generate_attachment_metadata($attachId, $targetPath);
        wp_update_attachment_metadata($attachId, $attachData);

        if (!empty($altText)) {
            update_post_meta($attachId, '_wp_attachment_image_alt', sanitize_text_field($altText));
        }

        $this->logger->info(sprintf('[UploadManager] Successfully uploaded attachment ID %d (%s).', $attachId, $filename));

        return $this->repository->find($attachId);
    }
}
