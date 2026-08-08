<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Automation\Rollback;

use WPAIOS\Contracts\LoggerInterface;
use WPAIOS\Modules\Automation\Contracts\TaskInterface;
use WPAIOS\Modules\Automation\Models\WorkflowContext;

/**
 * Rollback Manager — reverses completed tasks on workflow failure.
 *
 * Executes rollback handlers in LIFO order (last completed = first rolled back).
 */
class RollbackManager
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    /**
     * Roll back all completed rollbackable tasks.
     *
     * @param TaskInterface[] $completedTasks    In execution order.
     * @param WorkflowContext $context
     * @return array<string, bool>  Task ID => rollback success map.
     */
    public function rollback(array $completedTasks, WorkflowContext $context): array
    {
        $results = [];

        // Reverse order — last completed first
        $reversed = array_reverse($completedTasks);

        foreach ($reversed as $task) {
            if (!$task->isRollbackable()) {
                $this->logger->info(sprintf('[Rollback] Task [%s] is not rollbackable — skipping.', $task->id()));
                $results[$task->id()] = true;
                continue;
            }

            try {
                $success = $task->rollback($context);
                $results[$task->id()] = $success;

                $this->logger->info(sprintf(
                    '[Rollback] Task [%s] rollback %s.',
                    $task->id(),
                    $success ? 'SUCCEEDED' : 'FAILED'
                ));
            } catch (\Throwable $e) {
                $results[$task->id()] = false;
                $this->logger->error(sprintf('[Rollback] Task [%s] threw exception: %s', $task->id(), $e->getMessage()));
            }
        }

        return $results;
    }
}
