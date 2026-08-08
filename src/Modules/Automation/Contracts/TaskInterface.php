<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Automation\Contracts;

use WPAIOS\Modules\Automation\Models\TaskResult;
use WPAIOS\Modules\Automation\Models\WorkflowContext;

/**
 * Task Interface — contract for a single atomic workflow task.
 */
interface TaskInterface
{
    public function id(): string;
    public function name(): string;
    public function description(): string;

    /**
     * IDs of tasks that must complete before this task can run.
     *
     * @return string[]
     */
    public function dependencies(): array;

    /**
     * Maximum number of automatic retry attempts.
     *
     * @return int
     */
    public function maxRetries(): int;

    /**
     * Whether this task can be rolled back on failure.
     *
     * @return bool
     */
    public function isRollbackable(): bool;

    /**
     * Conditionally determine if this task should run.
     *
     * @param WorkflowContext $context
     * @return bool
     */
    public function shouldExecute(WorkflowContext $context): bool;

    /**
     * Execute the task and return its result.
     *
     * @param WorkflowContext $context
     * @return TaskResult
     */
    public function run(WorkflowContext $context): TaskResult;

    /**
     * Undo this task's effects (called during rollback).
     *
     * @param WorkflowContext $context
     * @return bool
     */
    public function rollback(WorkflowContext $context): bool;
}
