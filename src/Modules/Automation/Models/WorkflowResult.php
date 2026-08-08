<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Automation\Models;

/**
 * Workflow Execution Result — final outcome of a full workflow run.
 */
class WorkflowResult
{
    /** @param TaskResult[] $taskResults */
    public function __construct(
        public readonly string $workflowId,
        public readonly string $runId,
        public readonly bool $success,
        public readonly string $status,
        public readonly array $taskResults = [],
        public readonly ?string $error = null,
        public readonly float $totalDurationMs = 0.0,
        public readonly array $rollbackLog = [],
        public readonly array $metadata = []
    ) {
    }

    public static function success(string $workflowId, string $runId, array $taskResults = [], float $durationMs = 0.0): self
    {
        return new self(
            workflowId: $workflowId,
            runId: $runId,
            success: true,
            status: WorkflowStatus::COMPLETED,
            taskResults: $taskResults,
            totalDurationMs: $durationMs
        );
    }

    public static function failure(string $workflowId, string $runId, string $error, array $taskResults = [], float $durationMs = 0.0, array $rollbackLog = []): self
    {
        return new self(
            workflowId: $workflowId,
            runId: $runId,
            success: false,
            status: WorkflowStatus::FAILED,
            taskResults: $taskResults,
            error: $error,
            totalDurationMs: $durationMs,
            rollbackLog: $rollbackLog
        );
    }

    public function toArray(): array
    {
        return [
            'workflow_id' => $this->workflowId,
            'run_id' => $this->runId,
            'success' => $this->success,
            'status' => $this->status,
            'error' => $this->error,
            'total_duration_ms' => $this->totalDurationMs,
            'tasks_completed' => count(array_filter($this->taskResults, fn ($r) => $r->success)),
            'tasks_failed' => count(array_filter($this->taskResults, fn ($r) => !$r->success)),
            'rollback_log' => $this->rollbackLog,
        ];
    }
}
