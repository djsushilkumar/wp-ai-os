<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Automation\Queue;

/**
 * Workflow Queue Item.
 */
class QueueItem
{
    public string $id;
    public string $workflowId;
    public array $input;
    public int $priority;
    public string $status;
    public int $attempts;
    public int $maxAttempts;
    public ?int $runAfter;
    public int $createdAt;
    public ?string $error;

    public function __construct(
        string $workflowId,
        array $input = [],
        int $priority = 10,
        int $maxAttempts = 3,
        ?int $runAfter = null
    ) {
        $this->id = uniqid('qi_', true);
        $this->workflowId = $workflowId;
        $this->input = $input;
        $this->priority = $priority;
        $this->status = 'pending';
        $this->attempts = 0;
        $this->maxAttempts = $maxAttempts;
        $this->runAfter = $runAfter;
        $this->createdAt = time();
        $this->error = null;
    }

    public function isReady(): bool
    {
        return $this->runAfter === null || time() >= $this->runAfter;
    }

    public function canRetry(): bool
    {
        return $this->attempts < $this->maxAttempts;
    }
}
