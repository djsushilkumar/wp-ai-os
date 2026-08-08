<?php

declare(strict_types=1);

namespace WPAIOS\Providers\Models;

/**
 * Normalized Tool Call value object.
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

    /**
     * Convert to array representation.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'arguments' => $this->arguments,
        ];
    }
}
