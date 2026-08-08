<?php

declare(strict_types=1);

namespace WPAIOS\Core\Lifecycle;

use Exception;
use WPAIOS\Contracts\LoggerInterface;
use WPAIOS\Contracts\MigrationInterface;

/**
 * Migration Runner Service executing database migrations and rollbacks.
 */
class MigrationRunner
{
    /**
     * @var MigrationInterface[]
     */
    private array $migrations = [];

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(private LoggerInterface $logger)
    {
    }

    /**
     * Register a database migration instance.
     *
     * @param MigrationInterface $migration
     * @return void
     */
    public function addMigration(MigrationInterface $migration): void
    {
        $this->migrations[ $migration->getVersion() ] = $migration;
    }

    /**
     * Run all pending forward migrations up to target version.
     *
     * @param string $lastMigratedVersion
     * @return string New last migrated version.
     */
    public function migrateUp(string $lastMigratedVersion = '0.0.0'): string
    {
        ksort($this->migrations);
        $executedVersion = $lastMigratedVersion;

        foreach ($this->migrations as $version => $migration) {
            if (version_compare($version, $lastMigratedVersion, '>')) {
                try {
                    $this->logger->info(sprintf('Executing migration [%s]...', $version));
                    $migration->up();
                    $executedVersion = $version;
                } catch (Exception $e) {
                    $this->logger->error(sprintf('Migration [%s] failed: %s', $version, $e->getMessage()));
                    break;
                }
            }
        }

        return $executedVersion;
    }

    /**
     * Rollback migrations back down to target version.
     *
     * @param string $targetVersion
     * @param string $currentVersion
     * @return string New rolled back version.
     */
    public function migrateDown(string $targetVersion, string $currentVersion): string
    {
        krsort($this->migrations);
        $rolledBackVersion = $currentVersion;

        foreach ($this->migrations as $version => $migration) {
            if (version_compare($version, $targetVersion, '>') && version_compare($version, $currentVersion, '<=')) {
                try {
                    $this->logger->info(sprintf('Rolling back migration [%s]...', $version));
                    $migration->down();
                    $rolledBackVersion = $version;
                } catch (Exception $e) {
                    $this->logger->error(sprintf('Rollback of migration [%s] failed: %s', $version, $e->getMessage()));
                    break;
                }
            }
        }

        return $rolledBackVersion;
    }
}
