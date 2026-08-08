<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\Models;

use WPAIOS\Modules\Builders\Contracts\BuilderStyleInterface;

/**
 * Class BuilderStyle
 */
class BuilderStyle implements BuilderStyleInterface
{
    public function __construct(
        private array $rules = [],
        private array $responsive = []
    ) {
    }

    public function getRules(): array
    {
        return $this->rules;
    }

    public function getResponsive(): array
    {
        return $this->responsive;
    }

    public function toCssString(): string
    {
        $css = '';
        foreach ($this->rules as $prop => $val) {
            $css .= sprintf('%s: %s; ', $prop, $val);
        }
        return trim($css);
    }

    public function toArray(): array
    {
        return [
            'rules' => $this->rules,
            'responsive' => $this->responsive,
        ];
    }
}
