<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Abilities\Types\System;

use WPAIOS\Modules\Abilities\AbstractAbility;

/**
 * Site Information Ability.
 */
class SiteInfoAbility extends AbstractAbility
{
    protected string $category = 'System';

    public function id(): string
    {
        return 'wp_ai_os_site_info';
    }

    public function name(): string
    {
        return 'Site Information';
    }

    public function description(): string
    {
        return 'Returns core WordPress site parameters, URL, title, and language settings.';
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
            'title'       => function_exists('get_bloginfo') ? get_bloginfo('name') : 'WordPress Site',
            'url'         => function_exists('site_url') ? site_url() : '/',
            'admin_email' => function_exists('get_bloginfo') ? get_bloginfo('admin_email') : 'admin@example.com',
            'language'    => function_exists('get_bloginfo') ? get_bloginfo('language') : 'en-US',
            'multisite'   => function_exists('is_multisite') ? is_multisite() : false,
        ];
    }
}
