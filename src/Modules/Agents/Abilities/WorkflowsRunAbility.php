<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Agents\Abilities;

use WPAIOS\Modules\Agents\AgentsManager;
use WPAIOS\Modules\Agents\Context\AgentContext;
use WPAIOS\Modules\Mcp\Abilities\AbstractAbility;

/**
 * Ability: agents/workflows/run
 */
class WorkflowsRunAbility extends AbstractAbility
{
    public function __construct(private AgentsManager $manager)
    {
    }

    public function getName(): string
    {
        return 'wp_ai_os_agents_workflows_run';
    }

    public function getDescription(): string
    {
        return 'Run a multi-agent workflow for a specified goal.';
    }

    public function getInputSchema(): array
    {
        return [
            'type'       => 'object',
            'required'   => [ 'goal' ],
            'properties' => [
                'goal'         => [
                    'type'        => 'string',
                    'description' => 'Workflow macro goal',
                ],
                'target_agent' => [
                    'type'    => 'string',
                    'default' => 'orchestrator',
                ],
            ],
        ];
    }

    public function execute(array $params): array
    {
        $goal          = $params['goal'];
        $targetAgentId = $params['target_agent'] ?? 'orchestrator';

        $agent = $this->manager->getRegistry()->get($targetAgentId);
        if (! $agent) {
            return [
                'success' => false,
                'error'   => 'Target agent not found',
            ];
        }

        $taskId = 'task_' . uniqid();
        $task   = new class ($taskId, $goal) implements \WPAIOS\Modules\Agents\Contracts\AgentTaskInterface {
            public function __construct(private string $id, private string $goal)
            {
            }
            public function getId(): string
            {
                return $this->id;
            }
            public function getGoal(): string
            {
                return $this->goal;
            }
            public function getStatus(): string
            {
                return 'pending';
            }
            public function getInputs(): array
            {
                return [ 'goal' => $this->goal ];
            }
        };

        $context = new AgentContext($taskId);
        $result  = $this->manager->getOrchestrator()->runTask($agent, $task, $context);

        return [
            'success' => true,
            'task_id' => $taskId,
            'result'  => $result,
        ];
    }
}
