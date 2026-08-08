<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Agents\BuiltIn;

use WPAIOS\Modules\Agents\AbstractAgent;
use WPAIOS\Modules\Agents\Profiles\AgentProfile;

class DesignAgent extends AbstractAgent
{
    public function __construct()
    {
        parent::__construct(
            new AgentProfile(
                'design',
                'Design Agent',
                'Creates design kit tokens, color palettes, and typography specifications.',
                '1.0.0',
                'design',
                'MEDIUM',
                [ 'builders/capabilities', 'builder/pages/get' ]
            )
        );
    }
}
