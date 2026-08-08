<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Agents\BuiltIn;

use WPAIOS\Modules\Agents\AbstractAgent;
use WPAIOS\Modules\Agents\Profiles\AgentProfile;

class SecurityAgent extends AbstractAgent
{
    public function __construct()
    {
        parent::__construct(
            new AgentProfile(
                'security',
                'Security Agent',
                'Performs capability checks, input/output sanitization audits, and security validations.',
                '1.0.0',
                'security_audit',
                'LOW',
                [ 'agents/status', 'agents/approvals/list' ]
            )
        );
    }
}
