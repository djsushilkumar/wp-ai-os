<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Agents\BuiltIn;

use WPAIOS\Modules\Agents\AbstractAgent;
use WPAIOS\Modules\Agents\Profiles\AgentProfile;

class MediaAgent extends AbstractAgent
{
    public function __construct()
    {
        parent::__construct(
            new AgentProfile(
                'media',
                'Media Agent',
                'Manages WordPress Media Library uploads and attachment metadata via Media abilities.',
                '1.0.0',
                'media_management',
                'MEDIUM',
                [ 'wp_ai_os_media_upload', 'wp_ai_os_media_metadata' ]
            )
        );
    }
}
