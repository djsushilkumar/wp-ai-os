<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Knowledge\Ingestion;

use WPAIOS\Modules\Knowledge\Chunking\TextChunker;
use WPAIOS\Modules\Knowledge\Embeddings\EmbeddingService;
use WPAIOS\Modules\Knowledge\Vector\MySQLVectorStore;

/**
 * Class IngestionPipeline
 * Ingestion Pipeline: Source -> Permission Check -> Extraction -> Cleaning -> Chunking -> Metadata -> Embedding -> Index.
 */
class IngestionPipeline
{
    public function __construct(
        private TextChunker $chunker,
        private EmbeddingService $embeddingService,
        private MySQLVectorStore $vectorStore
    ) {
    }

    public function ingestContent(
        string $sourceId,
        string $sourceType,
        string $sourceTitle,
        string $rawContent,
        array $metadata = []
    ): int {
        // Cleaning
        $cleanContent = trim(strip_tags($rawContent));

        // Chunking
        $chunks = $this->chunker->chunk($cleanContent, $sourceId, $sourceType, $sourceTitle, $metadata);

        // Embedding & Upsert into Vector Store
        $count = 0;
        foreach ($chunks as $chunk) {
            $vector = $this->embeddingService->generateEmbedding($chunk->getContent());
            if ($this->vectorStore->upsertChunk($chunk, $vector)) {
                $count++;
            }
        }

        return $count;
    }
}
