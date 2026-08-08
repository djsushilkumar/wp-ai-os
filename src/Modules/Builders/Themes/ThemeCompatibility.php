<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\Themes;

/**
 * Class ThemeCompatibility
 */
class ThemeCompatibility
{
    public function check(): array
    {
        $isBlock = function_exists('wp_is_block_theme') && wp_is_block_theme();
        return [
            'theme_name' => function_exists('wp_get_theme') ? wp_get_theme()->get('Name') : 'Default Theme',
            'is_block_theme' => $isBlock,
            'supports_global_styles' => $isBlock,
        ];
    }
}
