<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Knowledge\Permissions;

use WPAIOS\Modules\Knowledge\Models\KnowledgeChunkModel;

/**
 * Class PermissionFilter
 * Enforces WordPress object capabilities, post visibility, and multisite network/site isolation.
 */
class PermissionFilter
{
    /**
     * @param KnowledgeChunkModel[] $chunks
     * @return KnowledgeChunkModel[]
     */
    public function filter(array $chunks, ?int $userId = null, ?int $siteId = null): array
    {
        $filtered = [];
        $currentSiteId = $siteId ?? (function_exists('get_current_blog_id') ? get_current_blog_id() : 1);

        foreach ($chunks as $chunk) {
            $meta = $chunk->getMetadata();

            // Multisite Isolation Check
            if (isset($meta['site_id']) && (int) $meta['site_id'] !== $currentSiteId) {
                continue; // Block cross-site data leak
            }

            // Private/Draft Content Check
            if (isset($meta['status']) && 'publish' !== $meta['status']) {
                if (null === $userId || (function_exists('user_can') && !user_can($userId, 'read_private_posts'))) {
                    continue; // Block private post leak
                }
            }

            $filtered[] = $chunk;
        }

        return $filtered;
    }
}
