<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Media;

use WPAIOS\Contracts\LoggerInterface;
use WPAIOS\Modules\Media\Services\MetadataManager;
use WPAIOS\Modules\Media\Services\UploadManager;

/**
 * MediaManager — central facade coordinating Enterprise Media Platform services.
 */
class MediaManager
{
    public function __construct(
        public readonly UploadManager $uploadManager,
        public readonly MetadataManager $metadataManager,
        public readonly LoggerInterface $logger
    ) {
    }

    public function boot(): void
    {
        $this->logger->info('[MediaManager] Enterprise Media Platform initialized.');
    }
}
