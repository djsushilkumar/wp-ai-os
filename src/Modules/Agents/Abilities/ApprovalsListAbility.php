<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Agents\Abilities;

use WPAIOS\Modules\Agents\AgentsManager;
use WPAIOS\Modules\Mcp\Abilities\AbstractAbility;

/**
 * Ability: agents/approvals/list
 */
class ApprovalsListAbility extends AbstractAbility
{
    public function __construct(private AgentsManager $manager)
    {
    }

    public function getName(): string
    {
        return 'wp_ai_os_agents_approvals_list';
    }

    public function getDescription(): string
    {
        return 'List all pending human approval requests for CRITICAL risk actions.';
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
        $pending = $this->manager->getApprovalManager()->getPendingApprovals();
        return [
            'success'           => true,
            'count'             => count($pending),
            'pending_approvals' => $pending,
        ];
    }
}
