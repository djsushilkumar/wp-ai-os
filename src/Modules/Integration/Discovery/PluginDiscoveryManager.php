<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Integration\Discovery;

use WPAIOS\Contracts\LoggerInterface;
use WPAIOS\Modules\Integration\Registry\IntegrationRegistry;

/**
 * Plugin Discovery Manager — scans WordPress environment to identify active plugins and active integration adapters.
 */
class PluginDiscoveryManager
{
    public function __construct(
        private IntegrationRegistry $registry,
        private ?LoggerInterface $logger = null
    ) {
    }

    /**
     * Scan active plugins and return an inventory report.
     *
     * @return array{total_registered: int, active_count: int, active_adapters: array<string, array<string, mixed>>}
     */
    public function discover(): array
    {
        $activeAdapters = [];
        foreach ($this->registry->all() as $adapter) {
            $isDetected = $adapter->detect();
            if ($isDetected) {
                $adapter->boot();
                $activeAdapters[$adapter->id()] = [
                    'name' => $adapter->name(),
                    'health' => $adapter->health(),
                    'permissions' => $adapter->permissions(),
                ];
            }
        }

        $this->logger?->info(sprintf('[PluginDiscovery] Scanned adapters. Detected %d active plugin integrations.', count($activeAdapters)));

        return [
            'total_registered' => count($this->registry->all()),
            'active_count' => count($activeAdapters),
            'active_adapters' => $activeAdapters,
        ];
    }
}
