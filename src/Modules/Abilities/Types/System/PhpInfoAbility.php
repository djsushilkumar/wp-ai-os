<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Abilities\Types\System;

use WPAIOS\Modules\Abilities\AbstractAbility;

/**
 * PHP Information Ability.
 */
class PhpInfoAbility extends AbstractAbility
{
    protected string $category = 'System';

    public function id(): string
    {
        return 'wp_ai_os_php_info';
    }

    public function name(): string
    {
        return 'PHP Environment Information';
    }

    public function description(): string
    {
        return 'Returns PHP runtime version, memory limit, max execution time, and active extensions.';
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
        return [
            'php_version'        => PHP_VERSION,
            'memory_limit'       => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'max_input_vars'     => ini_get('max_input_vars'),
            'curl_enabled'       => extension_loaded('curl'),
            'json_enabled'       => extension_loaded('json'),
            'mbstring_enabled'   => extension_loaded('mbstring'),
        ];
    }
}
