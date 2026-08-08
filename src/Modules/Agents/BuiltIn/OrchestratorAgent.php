<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Agents\BuiltIn;

use WPAIOS\Modules\Agents\AbstractAgent;
use WPAIOS\Modules\Agents\Profiles\AgentProfile;

class OrchestratorAgent extends AbstractAgent
{
    public function __construct()
    {
        parent::__construct(
            new AgentProfile(
                'orchestrator',
                'Orchestrator Agent',
                'Coordinates agent handoffs, workflow execution, and multi-agent tasks.',
                '1.0.0',
                'orchestration',
                'LOW',
                [ 'agents/list', 'agents/status', 'agents/workflows/run' ]
            )
        );
    }
}
