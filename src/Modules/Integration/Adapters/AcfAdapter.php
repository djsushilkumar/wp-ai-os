<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Integration\Adapters;

/**
 * Advanced Custom Fields (ACF) Adapter.
 */
class AcfAdapter extends AbstractPluginAdapter
{
    public function id(): string
    {
        return 'acf';
    }

    public function name(): string
    {
        return 'Advanced Custom Fields (ACF)';
    }

    public function detect(): bool
    {
        return class_exists('ACF') || function_exists('get_field');
    }
}
