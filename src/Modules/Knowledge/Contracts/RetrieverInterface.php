<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Knowledge\Contracts;

/**
 * Interface RetrieverInterface
 */
interface RetrieverInterface
{
    public function search(string $query, int $topK = 5, array $filters = []): array;
}
