<?php

declare(strict_types=1);

namespace WPAIOS\Providers\Models;

/**
 * Standardized AI Completion Request Value Object.
 */
class Request
{
    /**
     * @param array<array{role: string, content: string|array<mixed>}> $messages
     * @param string|null $model
     * @param float $temperature
     * @param int $maxTokens
     * @param array<array<string, mixed>> $tools
     * @param array<string, mixed> $extraOptions
     */
    public function __construct(
        public readonly array $messages,
        public readonly ?string $model = null,
        public readonly float $temperature = 0.7,
        public readonly int $maxTokens = 2048,
        public readonly array $tools = [],
        public readonly array $extraOptions = []
    ) {
    }
}
