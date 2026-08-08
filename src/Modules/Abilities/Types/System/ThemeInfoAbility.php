<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Abilities\Types\System;

use WPAIOS\Modules\Abilities\AbstractAbility;

/**
 * Theme Information Ability.
 */
class ThemeInfoAbility extends AbstractAbility
{
    protected string $category = 'System';

    public function id(): string
    {
        return 'wp_ai_os_theme_info';
    }

    public function name(): string
    {
        return 'Theme Information';
    }

    public function description(): string
    {
        return 'Returns active theme name, version, parent theme, and installed themes list.';
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
        if (function_exists('wp_get_theme')) {
            $theme = wp_get_theme();
            return [
                'name'     => $theme->get('Name'),
                'version'  => $theme->get('Version'),
                'author'   => $theme->get('Author'),
                'template' => $theme->get_template(),
                'is_child' => $theme->parent() ? true : false,
            ];
        }

        return [
            'name'    => 'Unknown',
            'version' => '1.0.0',
        ];
    }
}
