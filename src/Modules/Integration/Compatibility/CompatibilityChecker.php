<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Integration\Compatibility;

use WPAIOS\Modules\Integration\Contracts\PluginAdapterInterface;

/**
 * Compatibility Checker — validates PHP runtime version, WordPress core version, and plugin minimum requirements.
 */
class CompatibilityChecker
{
    /**
     * Check compatibility for a specific adapter.
     *
     * @param PluginAdapterInterface $adapter
     * @return array{compatible: bool, reasons: string[]}
     */
    public function check(PluginAdapterInterface $adapter): array
    {
        $reasons = [];

        if (PHP_VERSION_ID < 80200) {
            $reasons[] = 'PHP 8.2 or higher is required.';
        }

        if (!$adapter->detect()) {
            $reasons[] = sprintf('Target plugin for [%s] is not active.', $adapter->name());
        }

        return [
            'compatible' => empty($reasons),
            'reasons' => $reasons,
        ];
    }
}
