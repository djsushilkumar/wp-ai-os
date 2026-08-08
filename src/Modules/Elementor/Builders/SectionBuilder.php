<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Elementor\Builders;

/**
 * Section + Column Legacy Layout Builder (Elementor < 3.6 compatibility).
 */
class SectionBuilder
{
    /**
     * Build a Section node wrapping column(s) with child elements.
     *
     * @param array<array<string, mixed>> $columns
     * @param array<string, mixed> $extraSettings
     * @param string|null $id
     * @return array<string, mixed>
     */
    public function createSection(array $columns = [], array $extraSettings = [], ?string $id = null): array
    {
        $elementId = $id ?? substr(md5(uniqid('sec_', true)), 0, 7);

        $settings = array_merge([
            'structure' => implode('', array_fill(0, count($columns), '1')),
        ], $extraSettings);

        return [
            'id' => $elementId,
            'elType' => 'section',
            'settings' => $settings,
            'elements' => $columns,
            'isInner' => false,
        ];
    }

    /**
     * Build a Column node.
     *
     * @param array<array<string, mixed>> $elements Widget nodes inside column.
     * @param int $size Column size out of 10 (e.g. 5 = 50%)
     * @param array<string, mixed> $extraSettings
     * @param string|null $id
     * @return array<string, mixed>
     */
    public function createColumn(array $elements = [], int $size = 10, array $extraSettings = [], ?string $id = null): array
    {
        $elementId = $id ?? substr(md5(uniqid('col_', true)), 0, 7);

        $settings = array_merge([
            '_column_size' => $size * 10,
        ], $extraSettings);

        return [
            'id' => $elementId,
            'elType' => 'column',
            'settings' => $settings,
            'elements' => $elements,
            'isInner' => false,
        ];
    }
}
