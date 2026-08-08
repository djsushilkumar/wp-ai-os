<?php

declare(strict_types=1);

namespace WPAIOS\Contracts;

/**
 * Database Migration Interface contract supporting up and down (rollback) operations.
 */
interface MigrationInterface
{
    /**
     * Get unique migration identifier / version string (e.g., '2026_08_06_100000_create_audit_log_table').
     *
     * @return string
     */
    public function getVersion(): string;

    /**
     * Run the forward database migration.
     *
     * @return void
     */
    public function up(): void;

    /**
     * Rollback the database migration.
     *
     * @return void
     */
    public function down(): void;
}
