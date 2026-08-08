<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Agents\Abilities;

use WPAIOS\Modules\Agents\AgentsManager;
use WPAIOS\Modules\Mcp\Abilities\AbstractAbility;

/**
 * Ability: agents/tasks/list
 */
class TasksListAbility extends AbstractAbility
{
    public function __construct(private AgentsManager $manager)
    {
    }

    public function getName(): string
    {
        return 'wp_ai_os_agents_tasks_list';
    }

    public function getDescription(): string
    {
        return 'List active and completed agent task executions.';
    }

    public function getInputSchema(): array
    {
        return [
            'type'       => 'object',
            'properties' => [],
        ];
    }

    public function execute(array $params): array
    {
        $logs = $this->manager->getAuditLogger()->getLogs();
        return [
            'success' => true,
            'count'   => count($logs),
            'tasks'   => $logs,
        ];
    }
}
