<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\Contracts;

/**
 * Interface BuilderCapabilityInterface
 */
interface BuilderCapabilityInterface
{
    public function hasCapability(string $capability): bool;

    public function getCapabilities(): array;
}
