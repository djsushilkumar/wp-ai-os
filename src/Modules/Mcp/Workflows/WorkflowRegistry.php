<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Mcp\Workflows;

use Exception;

/**
 * Workflow Registry storing workflow step chains.
 */
class WorkflowRegistry
{
    /**
     * @var array<string, WorkflowStepInterface[]>
     */
    private array $workflows = [];

    /**
     * Register a workflow chain.
     *
     * @param string $name
     * @param WorkflowStepInterface[] $steps
     * @return void
     */
    public function register(string $name, array $steps): void
    {
        $this->workflows[$name] = $steps;
    }

    /**
     * Get workflow steps by name.
     *
     * @param string $name
     * @return WorkflowStepInterface[]
     * @throws Exception
     */
    public function get(string $name): array
    {
        if (!isset($this->workflows[$name])) {
            throw new Exception(sprintf('Workflow [%s] is not registered.', $name));
        }

        return $this->workflows[$name];
    }

    public function has(string $name): bool
    {
        return isset($this->workflows[$name]);
    }

    /**
     * @return array<string, WorkflowStepInterface[]>
     */
    public function all(): array
    {
        return $this->workflows;
    }
}
