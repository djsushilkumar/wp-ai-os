<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Automation\Models;

/**
 * Immutable Task Execution Result.
 */
class TaskResult
{
    public function __construct(
        public readonly string $taskId,
        public readonly bool $success,
        public readonly mixed $output = null,
        public readonly ?string $error = null,
        public readonly float $durationMs = 0.0,
        public readonly int $attempts = 1,
        public readonly array $metadata = []
    ) {
    }

    public static function success(string $taskId, mixed $output = null, float $durationMs = 0.0, int $attempts = 1): self
    {
        return new self(
            taskId: $taskId,
            success: true,
            output: $output,
            durationMs: $durationMs,
            attempts: $attempts
        );
    }

    public static function failure(string $taskId, string $error, float $durationMs = 0.0, int $attempts = 1): self
    {
        return new self(
            taskId: $taskId,
            success: false,
            error: $error,
            durationMs: $durationMs,
            attempts: $attempts
        );
    }

    public static function skipped(string $taskId): self
    {
        return new self(taskId: $taskId, success: true, output: null, metadata: ['skipped' => true]);
    }
}
