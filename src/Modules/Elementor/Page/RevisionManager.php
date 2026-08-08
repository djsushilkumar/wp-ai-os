<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Elementor\Page;

use Exception;
use WPAIOS\Contracts\LoggerInterface;

/**
 * Revision Manager — creates and restores Elementor page revision snapshots.
 */
class RevisionManager
{
    private const META_REVISION_SNAPSHOT = '_wp_ai_os_elementor_snapshot';

    public function __construct(private LoggerInterface $logger)
    {
    }

    /**
     * Snapshot current Elementor data before a mutation (safe restore point).
     *
     * @param int $postId
     * @return string  Snapshot key for later rollback.
     */
    public function snapshot(int $postId): string
    {
        $currentData = get_post_meta($postId, '_elementor_data', true);
        $snapshotKey = 'snap_' . time() . '_' . $postId;
        $postMeta = get_post_meta($postId, self::META_REVISION_SNAPSHOT, true);
        $history = is_array($postMeta) ? $postMeta : [];

        // Keep only last 10 snapshots per page
        if (count($history) >= 10) {
            array_shift($history);
        }

        $history[$snapshotKey] = [
            'timestamp' => time(),
            'data' => $currentData,
        ];

        update_post_meta($postId, self::META_REVISION_SNAPSHOT, $history);
        $this->logger->info(sprintf('[RevisionManager] Snapshot [%s] created for page %d.', $snapshotKey, $postId));

        return $snapshotKey;
    }

    /**
     * Rollback page to a prior snapshot.
     *
     * @param int $postId
     * @param string $snapshotKey
     * @return bool
     * @throws Exception
     */
    public function rollback(int $postId, string $snapshotKey): bool
    {
        $history = get_post_meta($postId, self::META_REVISION_SNAPSHOT, true);
        if (!is_array($history) || !isset($history[$snapshotKey])) {
            throw new Exception(sprintf('Snapshot [%s] not found for page ID %d.', $snapshotKey, $postId));
        }

        $snapshotData = $history[$snapshotKey]['data'] ?? '';
        update_post_meta($postId, '_elementor_data', $snapshotData);

        $this->logger->info(sprintf('[RevisionManager] Rolled back page %d to snapshot [%s].', $postId, $snapshotKey));
        return true;
    }

    /**
     * List all available snapshots for a page.
     *
     * @param int $postId
     * @return array<string, mixed>
     */
    public function listSnapshots(int $postId): array
    {
        $history = get_post_meta($postId, self::META_REVISION_SNAPSHOT, true);
        if (!is_array($history)) {
            return [];
        }

        $summaries = [];
        foreach ($history as $key => $snap) {
            $summaries[] = [
                'key' => $key,
                'timestamp' => $snap['timestamp'],
                'date' => date('Y-m-d H:i:s', $snap['timestamp']),
            ];
        }

        return $summaries;
    }
}
