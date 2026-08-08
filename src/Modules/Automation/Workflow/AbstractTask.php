<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Automation\Workflow;

use WPAIOS\Modules\Automation\Contracts\TaskInterface;
use WPAIOS\Modules\Automation\Models\TaskResult;
use WPAIOS\Modules\Automation\Models\WorkflowContext;

/**
 * Abstract base class for all workflow tasks.
 */
abstract class AbstractTask implements TaskInterface
{
    protected int $maxRetriesCount = 0;
    protected bool $rollbackable = false;

    public function dependencies(): array
    {
        return [];
    }

    public function maxRetries(): int
    {
        return $this->maxRetriesCount;
    }

    public function isRollbackable(): bool
    {
        return $this->rollbackable;
    }

    public function shouldExecute(WorkflowContext $context): bool
    {
        return true;
    }

    public function rollback(WorkflowContext $context): bool
    {
        return false; // Default: no rollback implemented
    }

    /**
     * Helper to produce a successful task result.
     */
    protected function success(mixed $output, float $durationMs = 0.0, int $attempts = 1): TaskResult
    {
        return TaskResult::success($this->id(), $output, $durationMs, $attempts);
    }

    /**
     * Helper to produce a failed task result.
     */
    protected function failure(string $error, float $durationMs = 0.0, int $attempts = 1): TaskResult
    {
        return TaskResult::failure($this->id(), $error, $durationMs, $attempts);
    }
}
