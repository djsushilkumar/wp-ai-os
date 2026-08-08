<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Agents\BuiltIn;

use WPAIOS\Modules\Agents\AbstractAgent;
use WPAIOS\Modules\Agents\Profiles\AgentProfile;

class FormsAgent extends AbstractAgent
{
    public function __construct()
    {
        parent::__construct(
            new AgentProfile(
                'forms',
                'Forms Agent',
                'Discovers, creates, updates, and manages forms across WordPress form providers.',
                '1.0.0',
                'forms_management',
                'MEDIUM',
                [ 'wp_ai_os_forms_list', 'wp_ai_os_forms_create', 'wp_ai_os_forms_submissions_list' ]
            )
        );
    }
}
