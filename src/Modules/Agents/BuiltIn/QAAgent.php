<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Agents\BuiltIn;

use WPAIOS\Modules\Agents\AbstractAgent;
use WPAIOS\Modules\Agents\Profiles\AgentProfile;

class QAAgent extends AbstractAgent
{
    public function __construct()
    {
        parent::__construct(
            new AgentProfile(
                'qa',
                'QA Agent',
                'Validates generated layout ASTs, schema definitions, and content links.',
                '1.0.0',
                'quality_assurance',
                'LOW',
                [ 'builder/pages/validate', 'wp_ai_os_seo_metadata' ]
            )
        );
    }
}
