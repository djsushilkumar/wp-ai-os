<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Media\Repositories;

use WPAIOS\Modules\Media\Contracts\MediaRepositoryInterface;
use WPAIOS\Modules\Media\Models\MediaItemModel;

/**
 * Media Repository — handles WordPress attachment data access and post meta retrieval.
 */
class MediaRepository implements MediaRepositoryInterface
{
    public function find(int $attachmentId): ?MediaItemModel
    {
        $post = get_post($attachmentId);
        if (!$post || $post->post_type !== 'attachment') {
            return null;
        }

        $altText = (string) get_post_meta($attachmentId, '_wp_attachment_image_alt', true);
        $url = function_exists('wp_get_attachment_url') ? (string) wp_get_attachment_url($attachmentId) : '';
        $filePath = (string) get_attached_file($attachmentId);
        $fileSize = file_exists($filePath) ? filesize($filePath) ?: 0 : 0;

        $meta = wp_get_attachment_metadata($attachmentId);
        $width = isset($meta['width']) ? (int) $meta['width'] : null;
        $height = isset($meta['height']) ? (int) $meta['height'] : null;

        return new MediaItemModel(
            id: $attachmentId,
            title: $post->post_title,
            altText: $altText,
            caption: $post->post_excerpt,
            description: $post->post_content,
            mimeType: $post->post_mime_type,
            url: $url,
            filePath: $filePath,
            fileSize: $fileSize,
            width: $width,
            height: $height
        );
    }

    public function delete(int $attachmentId, bool $force = true): bool
    {
        $res = wp_delete_attachment($attachmentId, $force);
        return (bool) $res;
    }

    public function query(array $query): array
    {
        $posts = get_posts([
            'post_type' => 'attachment',
            'post_status' => 'inherit',
            'numberposts' => $query['limit'] ?? 10,
            'offset' => $query['offset'] ?? 0,
        ]);

        $items = [];
        foreach ($posts as $post) {
            $item = $this->find($post->ID);
            if ($item) {
                $items[] = $item;
            }
        }

        return $items;
    }
}
