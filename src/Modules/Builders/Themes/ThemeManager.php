<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\Themes;

use WPAIOS\Modules\Builders\Contracts\ThemeAdapterInterface;

/**
 * Class ThemeManager
 * Central facade for Theme Abstraction Layer.
 */
class ThemeManager
{
    public function __construct(
        private ThemeDiscovery $discovery,
        private ThemeCompatibility $compatibility
    ) {
    }

    public function getActiveThemeAdapter(): ThemeAdapterInterface
    {
        return $this->discovery->getActiveThemeAdapter();
    }

    public function getDiscovery(): ThemeDiscovery
    {
        return $this->discovery;
    }

    public function getCompatibility(): ThemeCompatibility
    {
        return $this->compatibility;
    }
}
