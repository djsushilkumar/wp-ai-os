<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Agents\Safety;

use RuntimeException;

/**
 * Class LoopProtector
 * Prevents infinite agent loops, recursive handoffs, and runaway token/task budget consumption.
 */
class LoopProtector
{
    private int $maxSteps = 25;
    private int $maxHandoffs = 10;
    private int $maxRetries = 3;

    private int $currentSteps = 0;
    private int $currentHandoffs = 0;
    private array $retryCounts = [];

    public function recordStep(): void
    {
        $this->currentSteps++;
        if ($this->currentSteps > $this->maxSteps) {
            throw new RuntimeException(sprintf('LoopProtector: Exceeded maximum allowed execution steps (%d). Terminating runaway workflow.', $this->maxSteps));
        }
    }

    public function recordHandoff(): void
    {
        $this->currentHandoffs++;
        if ($this->currentHandoffs > $this->maxHandoffs) {
            throw new RuntimeException(sprintf('LoopProtector: Exceeded maximum allowed agent handoffs (%d). Terminating loop.', $this->maxHandoffs));
        }
    }

    public function recordRetry(string $taskId): void
    {
        $this->retryCounts[$taskId] = ($this->retryCounts[$taskId] ?? 0) + 1;
        if ($this->retryCounts[$taskId] > $this->maxRetries) {
            throw new RuntimeException(sprintf('LoopProtector: Exceeded maximum allowed retries (%d) for task %s.', $this->maxRetries, $taskId));
        }
    }

    public function reset(): void
    {
        $this->currentSteps = 0;
        $this->currentHandoffs = 0;
        $this->retryCounts = [];
    }
}
