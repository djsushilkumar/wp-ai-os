<?php

declare(strict_types=1);

namespace WPAIOS\Core\Lifecycle;

use WPAIOS\Contracts\LoggerInterface;

/**
 * Rollback Manager orchestrating safe version rollbacks.
 */
class RollbackManager
{
    public function __construct(
        private MigrationRunner $migrationRunner,
        private LoggerInterface $logger
    ) {
    }

    /**
     * Rollback platform database and configuration to a previous version.
     *
     * @param string $targetVersion
     * @param string $currentVersion
     * @return bool
     */
    public function rollbackTo(string $targetVersion, string $currentVersion): bool
    {
        $this->logger->warning(sprintf('Initiating platform rollback from [%s] to [%s]...', $currentVersion, $targetVersion));

        $newVersion = $this->migrationRunner->migrateDown($targetVersion, $currentVersion);

        if (function_exists('update_option')) {
            update_option('wp_ai_os_version', $newVersion);
        }

        $this->logger->info(sprintf('Platform successfully rolled back to version [%s].', $newVersion));
        return true;
    }
}
