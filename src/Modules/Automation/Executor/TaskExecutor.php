<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Automation\Executor;

use Throwable;
use WPAIOS\Contracts\LoggerInterface;
use WPAIOS\Modules\Automation\Contracts\TaskInterface;
use WPAIOS\Modules\Automation\Models\TaskResult;
use WPAIOS\Modules\Automation\Models\WorkflowContext;

/**
 * Task Executor executing individual tasks with automatic retries and latency tracking.
 */
class TaskExecutor
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    /**
     * Execute a task with automatic retries.
     *
     * @param TaskInterface $task
     * @param WorkflowContext $context
     * @return TaskResult
     */
    public function execute(TaskInterface $task, WorkflowContext $context): TaskResult
    {
        $maxRetries = max(1, $task->maxRetries() + 1);
        $taskId = $task->id();
        $startTime = microtime(true);
        $attempts = 0;
        $lastError = null;

        while ($attempts < $maxRetries) {
            $attempts++;
            try {
                $this->logger->info(sprintf('[TaskExecutor] Executing task [%s] (Attempt %d/%d)...', $taskId, $attempts, $maxRetries));

                $result = $task->run($context);

                if ($result->success) {
                    $context->markTaskCompleted($taskId);
                    $context->setTaskResult($taskId, $result);
                    return $result;
                }

                $lastError = $result->error ?? 'Task returned failure status.';
            } catch (Throwable $e) {
                $lastError = $e->getMessage();
                $this->logger->warning(sprintf('[TaskExecutor] Task [%s] attempt %d failed: %s', $taskId, $attempts, $lastError));
            }

            if ($attempts < $maxRetries) {
                // Exponential backoff delay (100ms, 200ms, 400ms...)
                usleep((int) (100000 * pow(2, $attempts - 1)));
            }
        }

        $durationMs = (microtime(true) - $startTime) * 1000;
        $failureResult = TaskResult::failure($taskId, $lastError ?? 'Task execution failed.', $durationMs, $attempts);

        $context->markTaskFailed($taskId);
        $context->setTaskResult($taskId, $failureResult);

        return $failureResult;
    }
}
