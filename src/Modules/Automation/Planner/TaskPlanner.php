<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Automation\Planner;

use WPAIOS\Modules\Automation\Contracts\TaskInterface;
use WPAIOS\Modules\Automation\Models\WorkflowContext;

/**
 * Task Planner — coordinates planning phase: dependency resolution, risk analysis, and execution plan.
 */
class TaskPlanner
{
    public function __construct(
        private DependencyPlanner $dependencyPlanner,
        private RiskAnalyzer $riskAnalyzer
    ) {
    }

    /**
     * Produce an execution plan for a workflow.
     *
     * @param TaskInterface[] $tasks
     * @param WorkflowContext $context
     * @return array{ordered_tasks: TaskInterface[], risk: array<string, mixed>, estimated_steps: int}
     */
    public function plan(array $tasks, WorkflowContext $context): array
    {
        // 1. Resolve dependency order
        $ordered = $this->dependencyPlanner->resolve($tasks);

        // 2. Filter tasks based on their shouldExecute condition
        $filtered = array_filter($ordered, fn (TaskInterface $t) => $t->shouldExecute($context));
        $filtered = array_values($filtered);

        // 3. Analyze risk
        $risk = $this->riskAnalyzer->analyze($filtered);

        return [
            'ordered_tasks' => $filtered,
            'risk' => $risk,
            'estimated_steps' => count($filtered),
        ];
    }
}
