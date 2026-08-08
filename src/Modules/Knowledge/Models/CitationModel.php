<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Knowledge\Models;

/**
 * Class CitationModel
 * Citation metadata preserving source attribution for AI responses.
 */
class CitationModel
{
    public function __construct(
        private string $chunkId,
        private string $sourceId,
        private string $sourceType,
        private string $sourceTitle,
        private ?string $sourceUrl = null,
        private ?int $wpObjectId = null,
        private float $relevanceScore = 0.0,
        private string $lastUpdated = ''
    ) {
        if (empty($this->lastUpdated)) {
            $this->lastUpdated = gmdate('Y-m-d H:i:s');
        }
    }

    public function toArray(): array
    {
        return [
            'chunk_id' => $this->chunkId,
            'source_id' => $this->sourceId,
            'source_type' => $this->sourceType,
            'source_title' => $this->sourceTitle,
            'source_url' => $this->sourceUrl,
            'wp_object_id' => $this->wpObjectId,
            'relevance_score' => $this->relevanceScore,
            'last_updated' => $this->lastUpdated,
        ];
    }
}
