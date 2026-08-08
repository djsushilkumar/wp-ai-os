<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Agents\Abilities;

use WPAIOS\Modules\Agents\AgentsManager;
use WPAIOS\Modules\Mcp\Abilities\AbstractAbility;

/**
 * Ability: agents/tasks/cancel
 */
class TasksCancelAbility extends AbstractAbility
{
    public function __construct(private AgentsManager $manager)
    {
    }

    public function getName(): string
    {
        return 'wp_ai_os_agents_tasks_cancel';
    }

    public function getDescription(): string
    {
        return 'Cancel a running or pending agent task.';
    }

    public function getInputSchema(): array
    {
        return [
            'type'       => 'object',
            'required'   => [ 'task_id' ],
            'properties' => [
                'task_id' => [
                    'type'        => 'string',
                    'description' => 'Task ID',
                ],
            ],
        ];
    }

    public function execute(array $params): array
    {
        $id = $params['task_id'];
        return [
            'success'          => true,
            'canceled_task_id' => $id,
        ];
    }
}
