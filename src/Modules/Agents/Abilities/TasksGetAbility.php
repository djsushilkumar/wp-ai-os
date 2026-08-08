<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Agents\Abilities;

use WPAIOS\Modules\Agents\AgentsManager;
use WPAIOS\Modules\Mcp\Abilities\AbstractAbility;

/**
 * Ability: agents/tasks/get
 */
class TasksGetAbility extends AbstractAbility
{
    public function __construct(private AgentsManager $manager)
    {
    }

    public function getName(): string
    {
        return 'wp_ai_os_agents_tasks_get';
    }

    public function getDescription(): string
    {
        return 'Get details and audit trail for a specific task.';
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
        $id   = $params['task_id'];
        $logs = array_filter($this->manager->getAuditLogger()->getLogs(), fn ($l) => $l['task_id'] === $id);

        return [
            'success'       => true,
            'task_id'       => $id,
            'audit_entries' => array_values($logs),
        ];
    }
}
