<?php

declare(strict_types=1);

namespace WPAIOS\Providers\Models;

/**
 * Standardized AI Completion Response Value Object.
 */
class Response
{
    /**
     * @param string $content
     * @param ToolCall[] $toolCalls
     * @param string $model
     * @param array{prompt_tokens: int, completion_tokens: int, total_tokens: int} $usage
     * @param string $finishReason
     * @param array<string, mixed> $rawResponse
     */
    public function __construct(
        public readonly string $content = '',
        public readonly array $toolCalls = [],
        public readonly string $model = '',
        public readonly array $usage = ['prompt_tokens' => 0, 'completion_tokens' => 0, 'total_tokens' => 0],
        public readonly string $finishReason = 'stop',
        public readonly array $rawResponse = []
    ) {
    }

    /**
     * Check if response contains tool calls.
     *
     * @return bool
     */
    public function hasToolCalls(): bool
    {
        return !empty($this->toolCalls);
    }
}
