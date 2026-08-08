<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Forms;

use WPAIOS\Modules\Forms\Services\FormDiscovery;

/**
 * Class FormsBridge
 */
class FormsBridge
{
    public function __construct(private FormDiscovery $discovery)
    {
    }

    public function getActiveProviderSlugs(): array
    {
        return array_keys($this->discovery->getActiveAdapters());
    }
}
