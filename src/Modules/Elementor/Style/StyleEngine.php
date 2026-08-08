<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Elementor\Style;

/**
 * Elementor Style Engine — generates and resolves styling tokens
 * for Typography, Color, Spacing, Border, Shadow, and Background settings.
 */
class StyleEngine
{
    /**
     * Build Elementor-compatible typography settings array.
     *
     * @param string $fontFamily
     * @param string $fontSize      e.g. '16px', '1.2em'
     * @param string $fontWeight    e.g. '400', '700', 'bold'
     * @param string $lineHeight    e.g. '1.5', '24px'
     * @param string $textTransform e.g. 'uppercase', 'none'
     * @param string $prefix        Elementor control prefix, e.g. 'typography'
     * @return array<string, mixed>
     */
    public function typography(
        string $fontFamily = '',
        string $fontSize = '',
        string $fontWeight = '400',
        string $lineHeight = '1.5',
        string $textTransform = 'none',
        string $prefix = 'typography'
    ): array {
        $result = [];

        if (!empty($fontFamily)) {
            $result[$prefix . '_font_family'] = $fontFamily;
        }
        if (!empty($fontSize)) {
            $result[$prefix . '_font_size'] = ['size' => (int) filter_var($fontSize, FILTER_SANITIZE_NUMBER_INT), 'unit' => 'px'];
        }

        $result[$prefix . '_font_weight'] = $fontWeight;
        $result[$prefix . '_line_height'] = ['size' => (float) $lineHeight, 'unit' => 'em'];
        $result[$prefix . '_text_transform'] = $textTransform;

        return $result;
    }

    /**
     * Build a spacing settings array (padding or margin).
     *
     * @param int $top
     * @param int $right
     * @param int $bottom
     * @param int $left
     * @param string $unit 'px' | 'em' | '%' | 'vw'
     * @param string $type 'padding' | 'margin'
     * @return array<string, mixed>
     */
    public function spacing(int $top = 0, int $right = 0, int $bottom = 0, int $left = 0, string $unit = 'px', string $type = 'padding'): array
    {
        return [
            $type => [
                'top' => (string) $top,
                'right' => (string) $right,
                'bottom' => (string) $bottom,
                'left' => (string) $left,
                'unit' => $unit,
                'isLinked' => ($top === $right && $right === $bottom && $bottom === $left),
            ],
        ];
    }

    /**
     * Resolve Elementor global color token by name.
     *
     * @param string $colorName  e.g. 'primary', 'secondary', 'accent', 'text'
     * @return string  var(--e-global-color-{id})
     */
    public function globalColor(string $colorName): string
    {
        return "var(--e-global-color-{$colorName})";
    }

    /**
     * Build a solid background color settings array.
     *
     * @param string $color  Hex, rgb, or Elementor global color variable.
     * @return array<string, mixed>
     */
    public function backgroundColor(string $color): array
    {
        return [
            'background_background' => 'classic',
            'background_color' => $color,
        ];
    }

    /**
     * Build a gradient background settings array.
     *
     * @param string $color1
     * @param string $color2
     * @param string $type 'linear' | 'radial'
     * @param int $angle   Degrees for linear gradients
     * @return array<string, mixed>
     */
    public function gradientBackground(string $color1, string $color2, string $type = 'linear', int $angle = 135): array
    {
        return [
            'background_background' => 'gradient',
            'background_color' => $color1,
            'background_color_b' => $color2,
            'background_gradient_type' => $type,
            'background_gradient_angle' => ['size' => $angle, 'unit' => 'deg'],
        ];
    }

    /**
     * Build border radius settings.
     *
     * @param int $radius  px value
     * @return array<string, mixed>
     */
    public function borderRadius(int $radius): array
    {
        return [
            'border_radius' => [
                'top' => (string) $radius,
                'right' => (string) $radius,
                'bottom' => (string) $radius,
                'left' => (string) $radius,
                'unit' => 'px',
                'isLinked' => true,
            ],
        ];
    }

    /**
     * Build box shadow settings.
     *
     * @param int $hOffset
     * @param int $vOffset
     * @param int $blur
     * @param int $spread
     * @param string $color
     * @return array<string, mixed>
     */
    public function boxShadow(int $hOffset = 0, int $vOffset = 4, int $blur = 20, int $spread = 0, string $color = 'rgba(0,0,0,0.1)'): array
    {
        return [
            'box_shadow_box_shadow_type' => 'yes',
            'box_shadow_box_shadow' => [
                'horizontal' => $hOffset,
                'vertical' => $vOffset,
                'blur' => $blur,
                'spread' => $spread,
                'color' => $color,
            ],
        ];
    }
}
