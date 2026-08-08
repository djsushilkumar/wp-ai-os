<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\Contracts;

use WPAIOS\Modules\Builders\Models\BuilderCapabilitiesModel;

/**
 * Interface BuilderInterface
 * Basic contract for page builder information and availability.
 */
interface BuilderInterface
{
    public function getSlug(): string;

    public function getName(): string;

    public function isInstalled(): bool;

    public function isActive(): bool;

    public function getVersion(): ?string;

    public function getCapabilities(): BuilderCapabilitiesModel;
}
