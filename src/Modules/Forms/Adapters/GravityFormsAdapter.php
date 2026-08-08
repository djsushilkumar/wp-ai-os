<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Forms\Adapters;

/**
 * Class GravityFormsAdapter
 * Adapter for Gravity Forms plugin.
 */
class GravityFormsAdapter extends AbstractFormAdapter
{
    public function getSlug(): string
    {
        return 'gravityforms';
    }

    public function getName(): string
    {
        return 'Gravity Forms';
    }

    public function isAvailable(): bool
    {
        return class_exists('GFAPI') || class_exists('RGForms');
    }

    public function getVersion(): ?string
    {
        return class_exists('GFCommon') && isset(\GFCommon::$version) ? \GFCommon::$version : null;
    }
}
