<?php

declare(strict_types=1);

namespace WPAIOS\Modules\AI\Models;

/**
 * Standardized AI Provider Completion Request Value Object.
 */
class Request
{
    /**
     * @param Message[] $messages
     * @param string|null $model
     * @param float $temperature
     * @param float $topP
     * @param int $maxTokens
     * @param array<array<string, mixed>> $tools
     * @param bool $jsonMode
     * @param array<string, mixed> $extraOptions
     */
    public function __construct(
        public readonly array $messages,
        public readonly ?string $model = null,
        public readonly float $temperature = 0.7,
        public readonly float $topP = 1.0,
        public readonly int $maxTokens = 2048,
        public readonly array $tools = [],
        public readonly bool $jsonMode = false,
        public readonly array $extraOptions = []
    ) {
    }
}
