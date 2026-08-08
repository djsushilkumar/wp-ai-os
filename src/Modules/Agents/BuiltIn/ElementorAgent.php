<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Agents\BuiltIn;

use WPAIOS\Modules\Agents\AbstractAgent;
use WPAIOS\Modules\Agents\Profiles\AgentProfile;

class ElementorAgent extends AbstractAgent
{
    public function __construct()
    {
        parent::__construct(
            new AgentProfile(
                'elementor',
                'Elementor Agent',
                'Manages Elementor Flexbox Container layouts and post revisions via Elementor abilities.',
                '1.0.0',
                'elementor_builder',
                'HIGH',
                [ 'wp_ai_os_elementor_create_page', 'wp_ai_os_elementor_update_page' ]
            )
        );
    }
}
