<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\Themes;

use WPAIOS\Modules\Builders\Contracts\ThemeAdapterInterface;

/**
 * Class ThemeDiscovery
 * Discovers current theme environment (Block Theme vs Classic Theme).
 */
class ThemeDiscovery
{
    public function getActiveThemeAdapter(): ThemeAdapterInterface
    {
        $isBlock = function_exists('wp_is_block_theme') && wp_is_block_theme();

        if ($isBlock) {
            return new BlockThemeAdapter();
        }

        return new ClassicThemeAdapter();
    }
}
