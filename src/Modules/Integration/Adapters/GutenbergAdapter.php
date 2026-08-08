<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Integration\Adapters;

/**
 * Gutenberg Block Editor Adapter.
 */
class GutenbergAdapter extends AbstractPluginAdapter
{
    public function id(): string
    {
        return 'gutenberg';
    }

    public function name(): string
    {
        return 'Gutenberg Block Editor';
    }

    public function detect(): bool
    {
        return function_exists('register_block_type');
    }
}
