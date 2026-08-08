<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Knowledge\Contracts;

use WPAIOS\Modules\Knowledge\Models\KnowledgeChunkModel;

/**
 * Interface VectorStoreInterface
 */
interface VectorStoreInterface
{
    public function upsertChunk(KnowledgeChunkModel $chunk, array $vector): bool;

    public function searchVector(array $vector, int $topK = 5, array $filters = []): array;

    public function deleteBySource(string $sourceId): bool;

    public function clear(): bool;
}
