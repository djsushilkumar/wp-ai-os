<?php

declare(strict_types=1);

namespace WPAIOS\Services;

use Exception;
use WPAIOS\Contracts\ConfigInterface;
use WPAIOS\Contracts\LoggerInterface;

/**
 * Upgrade Manager Service orchestrating version migration scripts.
 */
class UpgradeManager
{
    /**
     * @param VersionManager $versionManager
     * @param ConfigInterface $config
     * @param LoggerInterface $logger
     */
    public function __construct(
        private VersionManager $versionManager,
        private ConfigInterface $config,
        private LoggerInterface $logger
    ) {
    }

    /**
     * Check if upgrade is needed and execute migration routines.
     *
     * @return void
     */
    public function checkAndUpgrade(): void
    {
        if (!$this->versionManager->needsUpgrade()) {
            return;
        }

        $fromVersion = $this->versionManager->getInstalledVersion();
        $toVersion = $this->versionManager->getVersion();

        $this->logger->info(sprintf('Starting upgrade migration from version [%s] to [%s]...', $fromVersion, $toVersion));

        try {
            $this->runMigrations($fromVersion, $toVersion);
            $this->versionManager->setInstalledVersion($toVersion);
            $this->logger->info(sprintf('Upgrade migration to [%s] completed successfully.', $toVersion));
        } catch (Exception $e) {
            $this->logger->error(sprintf('Upgrade migration failed: %s', $e->getMessage()));
        }
    }

    /**
     * Execute step-by-step version migration routines.
     *
     * @param string $fromVersion
     * @param string $toVersion
     * @return void
     */
    private function runMigrations(string $fromVersion, string $toVersion): void
    {
        // Version-specific migration triggers (e.g., 1.0.0 DB schema adjustments)
    }
}
