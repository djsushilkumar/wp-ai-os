<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Knowledge\Contracts;

/**
 * Interface RankerInterface
 */
interface RankerInterface
{
    public function rank(array $results, string $query): array;
}
