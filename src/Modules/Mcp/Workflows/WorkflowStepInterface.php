<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Mcp\Workflows;

/**
 * Workflow Step Interface contract.
 */
interface WorkflowStepInterface
{
    public function name(): string;
    public function execute(array $context): array;
    public function rollback(array $context): void;
    public function shouldExecute(array $context): bool;
}
