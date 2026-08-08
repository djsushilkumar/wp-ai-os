<?php

declare(strict_types=1);

namespace WPAIOS\Modules\AI\Models;

/**
 * Standardized AI Provider Completion Response Value Object.
 */
class Response
{
    /**
     * @param string $content
     * @param ToolCall[] $toolCalls
     * @param string $model
     * @param Usage $usage
     * @param string $finishReason
     * @param array<string, mixed> $rawResponse
     */
    public function __construct(
        public readonly string $content = '',
        public readonly array $toolCalls = [],
        public readonly string $model = '',
        public readonly Usage $usage = new Usage(),
        public readonly string $finishReason = 'stop',
        public readonly array $rawResponse = []
    ) {
    }

    public function hasToolCalls(): bool
    {
        return !empty($this->toolCalls);
    }
}
