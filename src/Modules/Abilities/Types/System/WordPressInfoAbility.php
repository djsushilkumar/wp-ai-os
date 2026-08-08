<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Abilities\Types\System;

use WPAIOS\Modules\Abilities\AbstractAbility;

/**
 * WordPress Core Information Ability.
 */
class WordPressInfoAbility extends AbstractAbility
{
    protected string $category = 'System';

    public function id(): string
    {
        return 'wp_ai_os_wordpress_info';
    }

    public function name(): string
    {
        return 'WordPress Information';
    }

    public function description(): string
    {
        return 'Returns WordPress core version, DB version, environment type, and debug state.';
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
            'wp_version'  => function_exists('get_bloginfo') ? get_bloginfo('version') : '6.4.0',
            'environment' => function_exists('wp_get_environment_type') ? wp_get_environment_type() : 'production',
            'debug_mode'  => defined('WP_DEBUG') ? WP_DEBUG : false,
            'db_prefix'   => isset($GLOBALS['wpdb']) ? $GLOBALS['wpdb']->prefix : 'wp_',
        ];
    }
}
