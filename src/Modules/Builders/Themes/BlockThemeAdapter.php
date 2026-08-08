<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\Themes;

use WPAIOS\Modules\Builders\Contracts\ThemeAdapterInterface;

/**
 * Class BlockThemeAdapter
 */
class BlockThemeAdapter implements ThemeAdapterInterface
{
    public function getThemeName(): string
    {
        return function_exists('wp_get_theme') ? wp_get_theme()->get('Name') : 'Twenty Twenty-Four';
    }

    public function isBlockTheme(): bool
    {
        return true;
    }

    public function getGlobalStyles(): array
    {
        if (function_exists('wp_get_global_settings')) {
            return wp_get_global_settings();
        }
        return ['color' => ['palette' => []], 'typography' => []];
    }

    public function getTemplateParts(): array
    {
        return ['header', 'footer', 'sidebar'];
    }

    public function getMenus(): array
    {
        return function_exists('wp_get_nav_menus') ? wp_get_nav_menus() : [];
    }
}
