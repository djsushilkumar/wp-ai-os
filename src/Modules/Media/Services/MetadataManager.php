<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Media\Services;

use Exception;
use WPAIOS\Contracts\LoggerInterface;
use WPAIOS\Modules\Media\Contracts\MediaRepositoryInterface;
use WPAIOS\Modules\Media\Models\MediaItemModel;

/**
 * Metadata Manager Service — updates ALT text, captions, titles, and descriptions for media attachments.
 */
class MetadataManager
{
    public function __construct(
        private MediaRepositoryInterface $repository,
        private LoggerInterface $logger
    ) {
    }

    /**
     * Update attachment metadata fields.
     *
     * @param int $attachmentId
     * @param array<string, string> $metadata  ['title', 'alt_text', 'caption', 'description']
     * @return MediaItemModel
     * @throws Exception
     */
    public function updateMetadata(int $attachmentId, array $metadata): MediaItemModel
    {
        $item = $this->repository->find($attachmentId);
        if (!$item) {
            throw new Exception(sprintf('Attachment ID %d not found.', $attachmentId));
        }

        $postArgs = ['ID' => $attachmentId];
        if (isset($metadata['title'])) {
            $postArgs['post_title'] = sanitize_text_field($metadata['title']);
        }
        if (isset($metadata['caption'])) {
            $postArgs['post_excerpt'] = sanitize_text_field($metadata['caption']);
        }
        if (isset($metadata['description'])) {
            $postArgs['post_content'] = wp_kses_post($metadata['description']);
        }

        wp_update_post($postArgs);

        if (isset($metadata['alt_text'])) {
            update_post_meta($attachmentId, '_wp_attachment_image_alt', sanitize_text_field($metadata['alt_text']));
        }

        $this->logger->info(sprintf('[MetadataManager] Updated metadata for attachment ID %d.', $attachmentId));

        return $this->repository->find($attachmentId);
    }
}
