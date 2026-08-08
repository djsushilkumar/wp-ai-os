<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Abilities\Types\System;

use WPAIOS\Modules\Abilities\AbstractAbility;

/**
 * Plugin Information Ability.
 */
class PluginInfoAbility extends AbstractAbility
{
    protected string $category = 'System';

    public function id(): string
    {
        return 'wp_ai_os_plugin_info';
    }

    public function name(): string
    {
        return 'Plugin Information';
    }

    public function description(): string
    {
        return 'Returns active and installed plugin inventory.';
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
        if (function_exists('get_plugins')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
            $plugins = get_plugins();
            $active  = get_option('active_plugins', []);

            return [
                'total'        => count($plugins),
                'active_count' => count($active),
                'installed'    => array_keys($plugins),
            ];
        }

        return [
            'total'        => 0,
            'active_count' => 0,
            'installed'    => [],
        ];
    }
}
