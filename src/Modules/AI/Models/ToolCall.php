<?php

declare(strict_types=1);

namespace WPAIOS\Modules\AI\Models;

/**
 * Normalized Tool Call Value Object.
 */
class ToolCall
{
    /**
     * @param string $id
     * @param string $name
     * @param array<string, mixed> $arguments
     */
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly array $arguments = []
    ) {
    }
}
