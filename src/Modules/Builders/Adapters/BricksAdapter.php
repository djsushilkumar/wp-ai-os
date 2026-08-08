<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\Adapters;

use WPAIOS\Modules\Builders\Models\BuilderCapabilitiesModel;

/**
 * Class BricksAdapter
 * Adapter for Bricks Builder. Performs dynamic API verification.
 */
class BricksAdapter extends AbstractBuilderAdapter
{
    public function getSlug(): string
    {
        return 'bricks';
    }

    public function getName(): string
    {
        return 'Bricks Builder';
    }

    public function isInstalled(): bool
    {
        return defined('BRICKS_VERSION') || class_exists('\Bricks\Database');
    }

    public function isActive(): bool
    {
        return $this->isInstalled();
    }

    public function getVersion(): ?string
    {
        return defined('BRICKS_VERSION') ? BRICKS_VERSION : null;
    }

    public function getCapabilities(): BuilderCapabilitiesModel
    {
        $active = $this->isActive();
        return new BuilderCapabilitiesModel(
            $active,
            $active,
            $active,
            $active,
            $active,
            $active,
            $active,
            $active,
            $active,
            $active,
            $active,
            $active,
            $active,
            $active,
            $active,
            $active,
            false,
            $active,
            $active,
            $active
        );
    }
}
