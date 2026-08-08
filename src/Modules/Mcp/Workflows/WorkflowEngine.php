<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Mcp\Workflows;

use Exception;
use WPAIOS\Contracts\LoggerInterface;

/**
 * Enterprise Workflow Engine supporting sequential steps, conditional execution, retries, rollbacks, and logging.
 */
class WorkflowEngine
{
    /**
     * @param LoggerInterface $logger
     */
    public function __construct(private LoggerInterface $logger)
    {
    }

    /**
     * Run a workflow sequence of steps safely with retries and rollback protection.
     *
     * @param string $workflowName
     * @param WorkflowStepInterface[] $steps
     * @param array<string, mixed> $initialContext
     * @param int $maxRetries
     * @return array<string, mixed> Final pipeline context.
     * @throws Exception
     */
    public function run(string $workflowName, array $steps, array $initialContext = [], int $maxRetries = 2): array
    {
        $this->logger->info(sprintf('Starting workflow [%s] with [%d] steps...', $workflowName, count($steps)));
        $context = $initialContext;
        $executedSteps = [];

        foreach ($steps as $step) {
            $stepName = $step->name();

            if (!$step->shouldExecute($context)) {
                $this->logger->info(sprintf('Skipping step [%s] (condition evaluated false).', $stepName));
                continue;
            }

            $attempt = 0;
            $success = false;
            $lastException = null;

            while ($attempt <= $maxRetries && !$success) {
                $attempt++;
                try {
                    $this->logger->info(sprintf('Executing step [%s] (Attempt %d/%d)...', $stepName, $attempt, $maxRetries + 1));
                    $stepOutput = $step->execute($context);
                    $context = array_merge($context, $stepOutput);
                    $executedSteps[] = $step;
                    $success = true;
                } catch (Exception $e) {
                    $lastException = $e;
                    $this->logger->warning(sprintf('Step [%s] failed on attempt %d: %s', $stepName, $attempt, $e->getMessage()));
                    if ($attempt <= $maxRetries) {
                        usleep(100000 * $attempt); // Exponential backoff delay
                    }
                }
            }

            if (!$success) {
                $this->logger->error(sprintf('Workflow [%s] failed at step [%s]. Initiating rollback...', $workflowName, $stepName));
                $this->rollbackExecutedSteps($executedSteps, $context);
                throw new Exception(sprintf('Workflow [%s] aborted at step [%s]: %s', $workflowName, $stepName, $lastException?->getMessage()));
            }
        }

        $this->logger->info(sprintf('Workflow [%s] completed successfully.', $workflowName));
        return $context;
    }

    /**
     * Rollback previously executed steps in reverse order.
     *
     * @param WorkflowStepInterface[] $executedSteps
     * @param array<string, mixed> $context
     * @return void
     */
    private function rollbackExecutedSteps(array $executedSteps, array $context): void
    {
        $reversed = array_reverse($executedSteps);
        foreach ($reversed as $step) {
            try {
                $this->logger->info(sprintf('Rolling back step [%s]...', $step->name()));
                $step->rollback($context);
            } catch (Exception $e) {
                $this->logger->error(sprintf('Failed to rollback step [%s]: %s', $step->name(), $e->getMessage()));
            }
        }
    }
}
