<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\Contracts;

/**
 * Interface ThemeAdapterInterface
 */
interface ThemeAdapterInterface
{
    public function getThemeName(): string;

    public function isBlockTheme(): bool;

    public function getGlobalStyles(): array;

    public function getTemplateParts(): array;

    public function getMenus(): array;
}
