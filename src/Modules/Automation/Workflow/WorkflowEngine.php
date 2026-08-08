<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Automation\Workflow;

use Exception;
use WPAIOS\Contracts\EventDispatcherInterface;
use WPAIOS\Contracts\LoggerInterface;
use WPAIOS\Modules\Automation\Contracts\TaskInterface;
use WPAIOS\Modules\Automation\Contracts\WorkflowInterface;
use WPAIOS\Modules\Automation\Executor\TaskExecutor;
use WPAIOS\Modules\Automation\Memory\CheckpointMemory;
use WPAIOS\Modules\Automation\Models\WorkflowContext;
use WPAIOS\Modules\Automation\Models\WorkflowResult;
use WPAIOS\Modules\Automation\Models\WorkflowStatus;
use WPAIOS\Modules\Automation\Planner\TaskPlanner;
use WPAIOS\Modules\Automation\Queue\WorkflowQueue;
use WPAIOS\Modules\Automation\Rollback\RollbackManager;

/**
 * Core Autonomous Workflow Engine orchestrating planning, execution, checkpointing, rollback, and event notifications.
 */
class WorkflowEngine
{
    /** @var array<string, WorkflowInterface> */
    private array $registeredWorkflows = [];

    public function __construct(
        private TaskPlanner $planner,
        private TaskExecutor $executor,
        private RollbackManager $rollbackManager,
        private CheckpointMemory $checkpointMemory,
        private WorkflowQueue $queue,
        private LoggerInterface $logger,
        private ?EventDispatcherInterface $eventDispatcher = null
    ) {
    }

    public function registerWorkflow(WorkflowInterface $workflow): void
    {
        $this->registeredWorkflows[$workflow->id()] = $workflow;
    }

    public function getWorkflow(string $id): WorkflowInterface
    {
        if (!isset($this->registeredWorkflows[$id])) {
            throw new Exception(sprintf('Workflow [%s] is not registered.', $id));
        }

        return $this->registeredWorkflows[$id];
    }

    /**
     * Run a workflow by ID or instance.
     *
     * @param string|WorkflowInterface $workflow
     * @param array<string, mixed> $input
     * @return WorkflowResult
     */
    public function run(string|WorkflowInterface $workflow, array $input = []): WorkflowResult
    {
        $instance = is_string($workflow) ? $this->getWorkflow($workflow) : $workflow;
        $workflowId = $instance->id();
        $context = new WorkflowContext($workflowId, $input);

        $startTime = microtime(true);
        $context->status = WorkflowStatus::RUNNING;

        $this->logger->info(sprintf('[WorkflowEngine] Starting workflow [%s] (Run ID: %s)', $workflowId, $context->runId));
        $this->eventDispatcher?->dispatch('workflow.started', $workflowId, $context->runId, $input);

        // 1. Planning Phase
        $plan = $this->planner->plan($instance->tasks(), $context);
        /** @var TaskInterface[] $tasks */
        $tasks = $plan['ordered_tasks'];

        $completedTasks = [];
        $rollbackLog = [];

        // 2. Execution Phase
        foreach ($tasks as $task) {
            $taskResult = $this->executor->execute($task, $context);

            if ($taskResult->success) {
                $completedTasks[] = $task;
                $this->checkpointMemory->save($context, 'task_' . $task->id());
            } else {
                $this->logger->error(sprintf('[WorkflowEngine] Task [%s] failed. Initiating rollback sequence...', $task->id()));
                $context->status = WorkflowStatus::ROLLING_BACK;
                $this->eventDispatcher?->dispatch('workflow.rollback_started', $workflowId, $task->id());

                $rollbackLog = $this->rollbackManager->rollback($completedTasks, $context);
                $context->status = WorkflowStatus::FAILED;

                $durationMs = (microtime(true) - $startTime) * 1000;
                $result = WorkflowResult::failure(
                    $workflowId,
                    $context->runId,
                    $taskResult->error ?? 'Task execution failed',
                    $context->getAllTaskResults(),
                    $durationMs,
                    $rollbackLog
                );

                $this->eventDispatcher?->dispatch('workflow.failed', $workflowId, $result);
                return $result;
            }
        }

        // 3. Completion Phase
        $context->status = WorkflowStatus::COMPLETED;
        $this->checkpointMemory->clear($context->runId);
        $durationMs = (microtime(true) - $startTime) * 1000;

        $result = WorkflowResult::success($workflowId, $context->runId, $context->getAllTaskResults(), $durationMs);

        $this->logger->info(sprintf('[WorkflowEngine] Workflow [%s] completed successfully in %.2fms.', $workflowId, $durationMs));
        $this->eventDispatcher?->dispatch('workflow.completed', $workflowId, $result);

        return $result;
    }

    /**
     * @return array<string, WorkflowInterface>
     */
    public function allWorkflows(): array
    {
        return $this->registeredWorkflows;
    }
}
