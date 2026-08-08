<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\Themes;

use WPAIOS\Modules\Builders\Contracts\ThemeAdapterInterface;

/**
 * Class ClassicThemeAdapter
 */
class ClassicThemeAdapter implements ThemeAdapterInterface
{
    public function getThemeName(): string
    {
        return function_exists('wp_get_theme') ? wp_get_theme()->get('Name') : 'Classic Theme';
    }

    public function isBlockTheme(): bool
    {
        return false;
    }

    public function getGlobalStyles(): array
    {
        return ['color' => ['primary' => '#0073aa'], 'typography' => ['fontFamily' => 'sans-serif']];
    }

    public function getTemplateParts(): array
    {
        return ['header.php', 'footer.php', 'sidebar.php'];
    }

    public function getMenus(): array
    {
        return function_exists('wp_get_nav_menus') ? wp_get_nav_menus() : [];
    }
}
