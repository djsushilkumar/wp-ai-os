<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Agents\BuiltIn;

use WPAIOS\Modules\Agents\AbstractAgent;
use WPAIOS\Modules\Agents\Profiles\AgentProfile;

class WebsiteArchitectAgent extends AbstractAgent
{
    public function __construct()
    {
        parent::__construct(new AgentProfile(
            'website_architect',
            'Website Architect Agent',
            'Creates website sitemaps, page structure blueprints, and layout plans.',
            '1.0.0',
            'architecture',
            'MEDIUM',
            ['wp_ai_os_build_site', 'builder/pages/validate']
        ));
    }
}
