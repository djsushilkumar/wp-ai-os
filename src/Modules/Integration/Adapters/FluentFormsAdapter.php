<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Integration\Adapters;

/**
 * Fluent Forms Adapter.
 */
class FluentFormsAdapter extends AbstractPluginAdapter
{
    public function id(): string
    {
        return 'fluent_forms';
    }

    public function name(): string
    {
        return 'Fluent Forms';
    }

    public function detect(): bool
    {
        return defined('FLUENTFORM_VERSION') || class_exists('FluentForm\App\Services\FormBuilder\FormBuilder');
    }
}
