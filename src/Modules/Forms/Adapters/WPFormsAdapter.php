<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Forms\Adapters;

/**
 * Class WPFormsAdapter
 * Adapter for WPForms plugin.
 */
class WPFormsAdapter extends AbstractFormAdapter
{
    public function getSlug(): string
    {
        return 'wpforms';
    }

    public function getName(): string
    {
        return 'WPForms';
    }

    public function isAvailable(): bool
    {
        return class_exists('WPForms\WPForms') || function_exists('wpforms');
    }

    public function getVersion(): ?string
    {
        return defined('WPFORMS_VERSION') ? WPFORMS_VERSION : null;
    }
}
