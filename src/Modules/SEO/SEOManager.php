<?php

declare(strict_types=1);

namespace WPAIOS\Modules\SEO;

use WPAIOS\Contracts\LoggerInterface;
use WPAIOS\Modules\SEO\Adapters\FallbackSEOAdapter;
use WPAIOS\Modules\SEO\Services\SEOAnalyzer;

/**
 * SEOManager — central facade coordinating SEO adapters, schema builders, and passive analysis.
 */
class SEOManager
{
    public function __construct(
        public readonly FallbackSEOAdapter $fallbackAdapter,
        public readonly SEOAnalyzer $analyzer,
        public readonly LoggerInterface $logger
    ) {
    }

    public function boot(): void
    {
        $this->logger->info('[SEOManager] Enterprise SEO Engine initialized with native fallback driver.');
    }
}
