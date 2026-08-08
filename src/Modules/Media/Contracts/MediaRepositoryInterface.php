<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Media\Contracts;

use WPAIOS\Modules\Media\Models\MediaItemModel;

/**
 * Media Repository Interface — contract for WordPress attachment operations.
 */
interface MediaRepositoryInterface
{
    public function find(int $attachmentId): ?MediaItemModel;

    public function delete(int $attachmentId, bool $force = true): bool;

    /**
     * @param array<string, mixed> $query
     * @return MediaItemModel[]
     */
    public function query(array $query): array;
}
