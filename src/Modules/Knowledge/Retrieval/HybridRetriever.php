<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Knowledge\Retrieval;

use WPAIOS\Modules\Knowledge\Contracts\RetrieverInterface;
use WPAIOS\Modules\Knowledge\Embeddings\EmbeddingService;
use WPAIOS\Modules\Knowledge\Vector\MySQLVectorStore;

/**
 * Class HybridRetriever
 * Hybrid Search combining keyword relevance and semantic vector search.
 */
class HybridRetriever implements RetrieverInterface
{
    public function __construct(
        private EmbeddingService $embeddingService,
        private MySQLVectorStore $vectorStore
    ) {
    }

    public function search(string $query, int $topK = 5, array $filters = []): array
    {
        $vector = $this->embeddingService->generateEmbedding($query);
        return $this->vectorStore->searchVector($vector, $topK, $filters);
    }
}
