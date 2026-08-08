<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Agents\BuiltIn;

use WPAIOS\Modules\Agents\AbstractAgent;
use WPAIOS\Modules\Agents\Profiles\AgentProfile;

class DeploymentAgent extends AbstractAgent
{
    public function __construct()
    {
        parent::__construct(
            new AgentProfile(
                'deployment',
                'Deployment Agent',
                'Handles site deployment and production synchronization. Requires explicit human approval.',
                '1.0.0',
                'deployment',
                'CRITICAL',
                [ 'agents/workflows/run' ]
            )
        );
    }
}
