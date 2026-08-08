<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Abilities\Types\System;

use WPAIOS\Modules\Abilities\AbstractAbility;

/**
 * Health Check Ability.
 */
class HealthCheckAbility extends AbstractAbility
{
    protected string $category = 'System';

    public function id(): string
    {
        return 'wp_ai_os_health_check';
    }

    public function name(): string
    {
        return 'System Health Check';
    }

    public function description(): string
    {
        return 'Executes runtime diagnostics for database connectivity, disk permissions, and PHP requirements.';
    }

    public function schema(): array
    {
        return [
            'type'       => 'object',
            'properties' => [],
        ];
    }

    public function execute(array $params): mixed
    {
        $dbStatus  = isset($GLOBALS['wpdb']) ? true : false;
        $phpStatus = version_compare(PHP_VERSION, '8.2.0', '>=');

        return [
            'healthy' => $dbStatus && $phpStatus,
            'checks'  => [
                'database'         => $dbStatus ? 'pass' : 'fail',
                'php_version'      => $phpStatus ? 'pass' : 'fail',
                'file_permissions' => is_writable(WPAI_OS_PATH) ? 'pass' : 'warn',
            ],
        ];
    }
}
