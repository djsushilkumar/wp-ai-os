<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\Models;

/**
 * Class BuilderResponsiveStyle
 */
class BuilderResponsiveStyle
{
    public function __construct(
        private array $desktop = [],
        private array $tablet = [],
        private array $mobile = []
    ) {
    }

    public function toArray(): array
    {
        return [
            'desktop' => $this->desktop,
            'tablet' => $this->tablet,
            'mobile' => $this->mobile,
        ];
    }
}
