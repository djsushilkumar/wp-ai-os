<?php

declare(strict_types=1);

namespace WPAIOS\Modules\AI\Models;

/**
 * Token Usage Metrics Value Object.
 */
class Usage
{
    public function __construct(
        public readonly int $promptTokens = 0,
        public readonly int $completionTokens = 0,
        public readonly int $totalTokens = 0
    ) {
    }
}
