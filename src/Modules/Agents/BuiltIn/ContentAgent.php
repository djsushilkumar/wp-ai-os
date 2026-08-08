<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Agents\BuiltIn;

use WPAIOS\Modules\Agents\AbstractAgent;
use WPAIOS\Modules\Agents\Profiles\AgentProfile;

class ContentAgent extends AbstractAgent
{
    public function __construct()
    {
        parent::__construct(
            new AgentProfile(
                'content',
                'Content Agent',
                'Manages structured content generation and WordPress post/page updates.',
                '1.0.0',
                'copywriting',
                'MEDIUM',
                [ 'wp_ai_os_create_post', 'wp_ai_os_update_post', 'wp_ai_os_get_posts' ]
            )
        );
    }
}
