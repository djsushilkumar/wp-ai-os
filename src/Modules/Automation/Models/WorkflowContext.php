<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Automation\Models;

/**
 * Mutable Workflow Execution Context.
 *
 * Shared between all tasks in a workflow run. Tasks read
 * input from and write output results back to this context.
 */
class WorkflowContext
{
    /** @var array<string, mixed> */
    private array $data = [];

    /** @var array<string, TaskResult> Keyed by task ID */
    private array $taskResults = [];

    /** @var string[] Completed task IDs */
    private array $completedTaskIds = [];

    /** @var string[] Failed task IDs */
    private array $failedTaskIds = [];

    public string $workflowId;
    public string $runId;
    public string $status = WorkflowStatus::PENDING;
    public int $startedAt;

    public function __construct(string $workflowId, array $input = [])
    {
        $this->workflowId = $workflowId;
        $this->runId = uniqid('run_', true);
        $this->startedAt = time();
        $this->data = $input;
    }

    public function set(string $key, mixed $value): void
    {
        $this->data[$key] = $value;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->data[$key] ?? $default;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->data);
    }

    public function all(): array
    {
        return $this->data;
    }

    public function setTaskResult(string $taskId, TaskResult $result): void
    {
        $this->taskResults[$taskId] = $result;
    }

    public function getTaskResult(string $taskId): ?TaskResult
    {
        return $this->taskResults[$taskId] ?? null;
    }

    public function markTaskCompleted(string $taskId): void
    {
        if (!in_array($taskId, $this->completedTaskIds, true)) {
            $this->completedTaskIds[] = $taskId;
        }
    }

    public function markTaskFailed(string $taskId): void
    {
        if (!in_array($taskId, $this->failedTaskIds, true)) {
            $this->failedTaskIds[] = $taskId;
        }
    }

    public function isTaskCompleted(string $taskId): bool
    {
        return in_array($taskId, $this->completedTaskIds, true);
    }

    public function getCompletedTaskIds(): array
    {
        return $this->completedTaskIds;
    }

    public function getFailedTaskIds(): array
    {
        return $this->failedTaskIds;
    }

    /** @return array<string, TaskResult> */
    public function getAllTaskResults(): array
    {
        return $this->taskResults;
    }

    public function elapsedSeconds(): int
    {
        return time() - $this->startedAt;
    }
}
