<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Forms\Adapters;

/**
 * Class NinjaFormsAdapter
 * Adapter for Ninja Forms plugin.
 */
class NinjaFormsAdapter extends AbstractFormAdapter
{
    public function getSlug(): string
    {
        return 'ninja_forms';
    }

    public function getName(): string
    {
        return 'Ninja Forms';
    }

    public function isAvailable(): bool
    {
        return class_exists('Ninja_Forms') || function_exists('Ninja_Forms');
    }

    public function getVersion(): ?string
    {
        return defined('Ninja_Forms::VERSION') ? \Ninja_Forms::VERSION : null;
    }
}
