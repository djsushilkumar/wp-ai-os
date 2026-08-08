<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Elementor\Style;

/**
 * Global Style Manager — reads and writes Elementor Kit (Global Colors & Typography).
 */
class GlobalStyleManager
{
    private const KIT_OPTION = 'elementor_active_kit';

    /**
     * Retrieve global colors from active Elementor Kit.
     *
     * @return array<string, mixed>
     */
    public function getGlobalColors(): array
    {
        if (!function_exists('get_option')) {
            return [];
        }

        $kitId = (int) get_option(self::KIT_OPTION, 0);
        if ($kitId === 0) {
            return [];
        }

        $kitMeta = get_post_meta($kitId, '_elementor_page_settings', true);
        if (!is_array($kitMeta)) {
            return [];
        }

        return $kitMeta['system_colors'] ?? $kitMeta['custom_colors'] ?? [];
    }

    /**
     * Retrieve global typography settings from active Elementor Kit.
     *
     * @return array<string, mixed>
     */
    public function getGlobalTypography(): array
    {
        if (!function_exists('get_option')) {
            return [];
        }

        $kitId = (int) get_option(self::KIT_OPTION, 0);
        if ($kitId === 0) {
            return [];
        }

        $kitMeta = get_post_meta($kitId, '_elementor_page_settings', true);
        if (!is_array($kitMeta)) {
            return [];
        }

        return $kitMeta['system_typography'] ?? $kitMeta['custom_typography'] ?? [];
    }

    /**
     * Resolve a named global color CSS token.
     *
     * @param string $colorId e.g. 'primary', 'secondary', 'accent', 'text'
     * @return string  CSS variable: var(--e-global-color-primary)
     */
    public function resolveColorToken(string $colorId): string
    {
        return "var(--e-global-color-{$colorId})";
    }

    /**
     * Resolve a named global typography CSS token.
     *
     * @param string $typographyId e.g. 'primary', 'secondary', 'text', 'accent'
     * @return string  CSS variable: var(--e-global-typography-primary-font-family)
     */
    public function resolveTypographyToken(string $typographyId): string
    {
        return "var(--e-global-typography-{$typographyId}-font-family)";
    }
}
