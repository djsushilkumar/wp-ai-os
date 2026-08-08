<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Agents;

use WPAIOS\Modules\Agents\Approvals\ApprovalManager;
use WPAIOS\Modules\Agents\Audit\AgentAuditLogger;
use WPAIOS\Modules\Agents\Handoffs\HandoffManager;
use WPAIOS\Modules\Agents\Orchestrator\AgentOrchestrator;
use WPAIOS\Modules\Agents\Planner\AgentPlanner;
use WPAIOS\Modules\Agents\Registry\AgentRegistry;
use WPAIOS\Modules\Agents\Safety\LoopProtector;

/**
 * Class AgentsManager
 * Central facade for the Agents module.
 */
class AgentsManager
{
    public function __construct(
        private AgentRegistry $registry,
        private AgentOrchestrator $orchestrator,
        private AgentPlanner $planner,
        private ApprovalManager $approvalManager,
        private LoopProtector $loopProtector,
        private HandoffManager $handoffManager,
        private AgentAuditLogger $auditLogger
    ) {
    }

    public function getRegistry(): AgentRegistry
    {
        return $this->registry;
    }

    public function getOrchestrator(): AgentOrchestrator
    {
        return $this->orchestrator;
    }

    public function getPlanner(): AgentPlanner
    {
        return $this->planner;
    }

    public function getApprovalManager(): ApprovalManager
    {
        return $this->approvalManager;
    }

    public function getLoopProtector(): LoopProtector
    {
        return $this->loopProtector;
    }

    public function getHandoffManager(): HandoffManager
    {
        return $this->handoffManager;
    }

    public function getAuditLogger(): AgentAuditLogger
    {
        return $this->auditLogger;
    }
}
