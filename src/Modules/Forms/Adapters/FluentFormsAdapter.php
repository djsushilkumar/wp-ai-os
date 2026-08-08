<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Forms\Adapters;

/**
 * Class FluentFormsAdapter
 * Adapter for Fluent Forms plugin.
 */
class FluentFormsAdapter extends AbstractFormAdapter
{
    public function getSlug(): string
    {
        return 'fluentform';
    }

    public function getName(): string
    {
        return 'Fluent Forms';
    }

    public function isAvailable(): bool
    {
        return defined('FLUENTFORM') || function_exists('wpFluent');
    }

    public function getVersion(): ?string
    {
        return defined('FLUENTFORM_VERSION') ? FLUENTFORM_VERSION : null;
    }
}
