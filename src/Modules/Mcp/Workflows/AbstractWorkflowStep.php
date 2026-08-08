<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Mcp\Workflows;

/**
 * Abstract Workflow Step base class.
 */
abstract class AbstractWorkflowStep implements WorkflowStepInterface
{
    public function shouldExecute(array $context): bool
    {
        return true;
    }

    public function rollback(array $context): void
    {
        // Default no-op for steps with no state mutation
    }
}
