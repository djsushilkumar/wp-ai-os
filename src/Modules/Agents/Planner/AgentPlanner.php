<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Agents\Planner;

use WPAIOS\Modules\Agents\Contracts\AgentContextInterface;
use WPAIOS\Modules\Agents\Contracts\AgentPlannerInterface;

/**
 * Class AgentPlanner
 * Decomposes complex macro goals into structured agent task sequences.
 */
class AgentPlanner implements AgentPlannerInterface
{
    public function plan(string $goal, AgentContextInterface $context): array
    {
        return [
            'goal' => $goal,
            'task_id' => $context->getTaskId(),
            'steps' => [
                ['step' => 1, 'agent' => 'research', 'goal' => 'Analyze site environment and active capabilities'],
                ['step' => 2, 'agent' => 'website_architect', 'goal' => 'Generate site blueprint and layout schema'],
                ['step' => 3, 'agent' => 'content', 'goal' => 'Create page content and copy'],
                ['step' => 4, 'agent' => 'seo', 'goal' => 'Generate Schema.org JSON-LD and meta tags'],
                ['step' => 5, 'agent' => 'qa', 'goal' => 'Validate schema compliance and links'],
            ],
        ];
    }
}
