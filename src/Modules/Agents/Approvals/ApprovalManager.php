<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Agents\Approvals;

/**
 * Class ApprovalManager
 * Human Approval Manager enforcing explicit human sign-off for CRITICAL actions.
 */
class ApprovalManager
{
    private array $pendingApprovals = [];
    private array $approvalHistory  = [];

    public function createApprovalRequest(string $taskId, string $agentId, string $action, string $riskLevel): array
    {
        $id      = 'app_' . uniqid();
        $request = [
            'id'         => $id,
            'task_id'    => $taskId,
            'agent_id'   => $agentId,
            'action'     => $action,
            'risk_level' => $riskLevel,
            'status'     => 'pending',
            'created_at' => gmdate('Y-m-d H:i:s'),
        ];

        $this->pendingApprovals[ $id ] = $request;
        return $request;
    }

    public function approve(string $approvalId, string $approverIdentity, string $reason = ''): bool
    {
        if (! isset($this->pendingApprovals[ $approvalId ])) {
            return false;
        }

        $request                = $this->pendingApprovals[ $approvalId ];
        $request['status']      = 'approved';
        $request['approver']    = $approverIdentity;
        $request['reason']      = $reason;
        $request['approved_at'] = gmdate('Y-m-d H:i:s');

        unset($this->pendingApprovals[ $approvalId ]);
        $this->approvalHistory[ $approvalId ] = $request;
        return true;
    }

    public function reject(string $approvalId, string $approverIdentity, string $reason = ''): bool
    {
        if (! isset($this->pendingApprovals[ $approvalId ])) {
            return false;
        }

        $request             = $this->pendingApprovals[ $approvalId ];
        $request['status']   = 'rejected';
        $request['approver'] = $approverIdentity;
        $request['reason']   = $reason;

        unset($this->pendingApprovals[ $approvalId ]);
        $this->approvalHistory[ $approvalId ] = $request;
        return true;
    }

    public function getPendingApprovals(): array
    {
        return array_values($this->pendingApprovals);
    }

    public function getApprovalHistory(): array
    {
        return array_values($this->approvalHistory);
    }
}
