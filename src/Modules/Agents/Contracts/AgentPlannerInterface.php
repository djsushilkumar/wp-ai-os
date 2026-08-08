<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Agents\Contracts;

/**
 * Interface AgentPlannerInterface
 */
interface AgentPlannerInterface
{
    public function plan(string $goal, AgentContextInterface $context): array;
}
