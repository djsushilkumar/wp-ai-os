<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Agents\Orchestrator;

use WPAIOS\Modules\Agents\Approvals\ApprovalManager;
use WPAIOS\Modules\Agents\Audit\AgentAuditLogger;
use WPAIOS\Modules\Agents\Contracts\AgentContextInterface;
use WPAIOS\Modules\Agents\Contracts\AgentInterface;
use WPAIOS\Modules\Agents\Contracts\AgentTaskInterface;
use WPAIOS\Modules\Agents\Handoffs\HandoffManager;
use WPAIOS\Modules\Agents\Planner\AgentPlanner;
use WPAIOS\Modules\Agents\Registry\AgentRegistry;
use WPAIOS\Modules\Agents\Safety\LoopProtector;

/**
 * Class AgentOrchestrator
 * Central orchestrator coordinating agent tasks, handoffs, loop protection, and human approvals.
 */
class AgentOrchestrator
{
    public function __construct(
        private AgentRegistry $registry,
        private AgentPlanner $planner,
        private LoopProtector $loopProtector,
        private ApprovalManager $approvalManager,
        private HandoffManager $handoffManager,
        private AgentAuditLogger $auditLogger
    ) {
    }

    public function runTask(AgentInterface $agent, AgentTaskInterface $task, AgentContextInterface $context): array
    {
        $startTime = microtime(true);
        $this->loopProtector->recordStep();

        // Check if agent requires CRITICAL human approval
        if ('CRITICAL' === $agent->getRiskLevel()) {
            $req = $this->approvalManager->createApprovalRequest(
                $task->getId(),
                $agent->getId(),
                $task->getGoal(),
                'CRITICAL'
            );

            $this->auditLogger->log(
                $agent->getId(),
                $task->getId(),
                'workflow/run',
                $task->getInputs(),
                'pending_approval',
                microtime(true) - $startTime,
                'Task paused: Requires explicit human approval.'
            );

            return [
                'status' => 'paused_pending_approval',
                'approval_id' => $req['id'],
                'message' => 'Critical risk task paused waiting for human sign-off.',
            ];
        }

        // Execute task
        $result = $agent->executeTask($task, $context);
        $execTime = microtime(true) - $startTime;

        $this->auditLogger->log(
            $agent->getId(),
            $task->getId(),
            'workflow/run',
            $task->getInputs(),
            $result['status'] ?? 'completed',
            $execTime
        );

        return $result;
    }
}
