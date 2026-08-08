<?php

declare(strict_types=1);

namespace WPAIOS\Modules\AI\Models;

/**
 * Chat Message Value Object.
 */
class Message
{
    /**
     * @param string $role 'system', 'user', 'assistant', 'developer', 'tool'
     * @param string|array<mixed> $content
     * @param ToolCall[] $toolCalls
     */
    public function __construct(
        public readonly string $role,
        public readonly string|array $content,
        public readonly array $toolCalls = []
    ) {
    }

    public function toArray(): array
    {
        return [
            'role' => $this->role,
            'content' => $this->content,
        ];
    }
}
