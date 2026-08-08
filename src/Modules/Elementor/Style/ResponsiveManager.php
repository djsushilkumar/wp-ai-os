<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Elementor\Style;

/**
 * Responsive Manager — generating device-specific Elementor responsive settings.
 */
class ResponsiveManager
{
    /**
     * Supported device breakpoints.
     *
     * @var string[]
     */
    public const DEVICES = ['desktop', 'tablet', 'tablet_extra', 'mobile_extra', 'mobile'];

    /**
     * Wrap a settings key with a device suffix (Elementor responsive convention).
     *
     * @param string $key    Base setting key, e.g. 'font_size'
     * @param string $device Device suffix, e.g. 'tablet', 'mobile'
     * @return string e.g. 'font_size_tablet'
     */
    public function deviceKey(string $key, string $device): string
    {
        return $device === 'desktop' ? $key : "{$key}_{$device}";
    }

    /**
     * Build responsive settings array for multiple devices.
     *
     * @param string $key Base control key.
     * @param array<string, mixed> $values Device => value map.
     * @return array<string, mixed>
     */
    public function responsive(string $key, array $values): array
    {
        $settings = [];

        foreach ($values as $device => $value) {
            $settings[$this->deviceKey($key, $device)] = $value;
        }

        return $settings;
    }

    /**
     * Build responsive font size settings.
     *
     * @param int $desktop
     * @param int $tablet
     * @param int $mobile
     * @param string $unit 'px' | 'em' | 'rem' | 'vw'
     * @param string $prefix Elementor control prefix
     * @return array<string, mixed>
     */
    public function responsiveFontSize(int $desktop, int $tablet, int $mobile, string $unit = 'px', string $prefix = 'typography'): array
    {
        return [
            "{$prefix}_font_size" => ['size' => $desktop, 'unit' => $unit],
            "{$prefix}_font_size_tablet" => ['size' => $tablet, 'unit' => $unit],
            "{$prefix}_font_size_mobile" => ['size' => $mobile, 'unit' => $unit],
        ];
    }

    /**
     * Build responsive padding settings.
     *
     * @param array<string, int[]> $values Device => [top, right, bottom, left]
     * @param string $unit
     * @return array<string, mixed>
     */
    public function responsivePadding(array $values, string $unit = 'px'): array
    {
        $settings = [];

        foreach ($values as $device => $sides) {
            [$top, $right, $bottom, $left] = array_pad($sides, 4, 0);
            $key = $device === 'desktop' ? 'padding' : "padding_{$device}";
            $settings[$key] = [
                'top' => (string) $top,
                'right' => (string) $right,
                'bottom' => (string) $bottom,
                'left' => (string) $left,
                'unit' => $unit,
                'isLinked' => ($top === $right && $right === $bottom && $bottom === $left),
            ];
        }

        return $settings;
    }
}
