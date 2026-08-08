<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Agents\Abilities;

use WPAIOS\Modules\Agents\AgentsManager;
use WPAIOS\Modules\Mcp\Abilities\AbstractAbility;

/**
 * Ability: agents/status
 */
class AgentsStatusAbility extends AbstractAbility
{
    public function __construct(private AgentsManager $manager)
    {
    }

    public function getName(): string
    {
        return 'wp_ai_os_agents_status';
    }

    public function getDescription(): string
    {
        return 'Get overall agent system health and status report.';
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
        return [
            'success'           => true,
            'status'            => 'operational',
            'agent_count'       => count($this->manager->getRegistry()->all()),
            'pending_approvals' => count($this->manager->getApprovalManager()->getPendingApprovals()),
            'handoffs_count'    => count($this->manager->getHandoffManager()->getHandoffLog()),
        ];
    }
}
