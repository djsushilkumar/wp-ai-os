<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Agents\BuiltIn;

use WPAIOS\Modules\Agents\AbstractAgent;
use WPAIOS\Modules\Agents\Profiles\AgentProfile;

class ResearchAgent extends AbstractAgent
{
    public function __construct()
    {
        parent::__construct(
            new AgentProfile(
                'research',
                'Research Agent',
                'Collects site information, installed plugins, themes, and WP environment diagnostics.',
                '1.0.0',
                'research',
                'LOW',
                [ 'wp_ai_os_get_system_info', 'builders/list', 'forms/providers/list' ]
            )
        );
    }
}
