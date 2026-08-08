<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\Contracts;

/**
 * Interface BuilderStyleInterface
 */
interface BuilderStyleInterface
{
    public function getRules(): array;

    public function toCssString(): string;
}
