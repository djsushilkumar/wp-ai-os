<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Abilities\Contracts;

/**
 * Category Interface contract for grouping abilities.
 */
interface CategoryInterface
{
    public function id(): string;
    public function name(): string;
    public function description(): string;
}
