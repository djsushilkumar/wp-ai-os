<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Knowledge\Models;

/**
 * Class KnowledgeChunkModel
 * Value object representing a chunk of extracted knowledge with source metadata.
 */
class KnowledgeChunkModel
{
    public function __construct(
        private string $id,
        private string $sourceId,
        private string $sourceType,
        private string $sourceTitle,
        private string $content,
        private array $metadata = [],
        private ?string $sourceUrl = null,
        private ?int $wpObjectId = null,
        private float $relevanceScore = 0.0
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getSourceId(): string
    {
        return $this->sourceId;
    }

    public function getSourceType(): string
    {
        return $this->sourceType;
    }

    public function getSourceTitle(): string
    {
        return $this->sourceTitle;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getMetadata(): array
    {
        return $this->metadata;
    }

    public function getSourceUrl(): ?string
    {
        return $this->sourceUrl;
    }

    public function getWpObjectId(): ?int
    {
        return $this->wpObjectId;
    }

    public function getRelevanceScore(): float
    {
        return $this->relevanceScore;
    }

    public function setRelevanceScore(float $score): void
    {
        $this->relevanceScore = $score;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'source_id' => $this->sourceId,
            'source_type' => $this->sourceType,
            'source_title' => $this->sourceTitle,
            'content' => $this->content,
            'metadata' => $this->metadata,
            'source_url' => $this->sourceUrl,
            'wp_object_id' => $this->wpObjectId,
            'relevance_score' => $this->relevanceScore,
        ];
    }
}
