<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Agents\Abilities;

use WPAIOS\Modules\Agents\AgentsManager;
use WPAIOS\Modules\Mcp\Abilities\AbstractAbility;

/**
 * Ability: agents/approvals/reject
 */
class ApprovalsRejectAbility extends AbstractAbility
{
    public function __construct(private AgentsManager $manager)
    {
    }

    public function getName(): string
    {
        return 'wp_ai_os_agents_approvals_reject';
    }

    public function getDescription(): string
    {
        return 'Human approval action to reject execution of a CRITICAL risk task.';
    }

    public function getInputSchema(): array
    {
        return [
            'type'       => 'object',
            'required'   => [ 'approval_id' ],
            'properties' => [
                'approval_id' => [
                    'type'        => 'string',
                    'description' => 'Approval Request ID',
                ],
                'reason'      => [
                    'type'        => 'string',
                    'description' => 'Optional rejection reason',
                ],
            ],
        ];
    }

    public function execute(array $params): array
    {
        $id     = $params['approval_id'];
        $reason = $params['reason'] ?? 'Rejected via MCP Tool';
        $user   = function_exists('wp_get_current_user') ? wp_get_current_user()->user_login : 'mcp_user';

        $ok = $this->manager->getApprovalManager()->reject($id, $user, $reason);

        return [
            'success'     => $ok,
            'approval_id' => $id,
            'status'      => $ok ? 'rejected' : 'failed_or_not_found',
        ];
    }
}
