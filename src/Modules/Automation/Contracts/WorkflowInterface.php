<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Automation\Contracts;

use WPAIOS\Modules\Automation\Models\WorkflowContext;
use WPAIOS\Modules\Automation\Models\WorkflowResult;

/**
 * Workflow Interface — contract for all workflow definitions.
 */
interface WorkflowInterface
{
    public function id(): string;
    public function name(): string;
    public function description(): string;
    public function version(): string;

    /**
     * @return TaskInterface[]
     */
    public function tasks(): array;

    public function execute(WorkflowContext $context): WorkflowResult;
}
