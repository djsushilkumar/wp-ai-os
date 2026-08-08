<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Agents\BuiltIn;

use WPAIOS\Modules\Agents\AbstractAgent;
use WPAIOS\Modules\Agents\Profiles\AgentProfile;

class SEOAgent extends AbstractAgent
{
    public function __construct()
    {
        parent::__construct(
            new AgentProfile(
                'seo',
                'SEO Agent',
                'Generates Schema.org JSON-LD and optimizes meta descriptions and title tags.',
                '1.0.0',
                'seo_optimization',
                'MEDIUM',
                [ 'wp_ai_os_seo_schema', 'wp_ai_os_seo_metadata' ]
            )
        );
    }
}
