<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\Themes;

/**
 * Class ThemeCapabilityRegistry
 */
class ThemeCapabilityRegistry
{
    public function getCapabilities(bool $isBlockTheme): array
    {
        return [
            'block_templates' => $isBlockTheme,
            'template_parts' => $isBlockTheme,
            'theme_json' => $isBlockTheme,
            'classic_widgets' => !$isBlockTheme,
            'customizer_menus' => true,
            'site_identity' => true,
        ];
    }
}
