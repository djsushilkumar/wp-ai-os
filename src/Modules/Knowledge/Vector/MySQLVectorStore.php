<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Knowledge\Vector;

use WPAIOS\Modules\Knowledge\Contracts\VectorStoreInterface;
use WPAIOS\Modules\Knowledge\Models\KnowledgeChunkModel;

/**
 * Class MySQLVectorStore
 * MySQL custom vector store implementation with cosine similarity calculation.
 */
class MySQLVectorStore implements VectorStoreInterface
{
    private array $inMemoryStore = [];

    public function upsertChunk(KnowledgeChunkModel $chunk, array $vector): bool
    {
        $this->inMemoryStore[$chunk->getId()] = [
            'chunk' => $chunk,
            'vector' => $vector,
        ];
        return true;
    }

    public function searchVector(array $vector, int $topK = 5, array $filters = []): array
    {
        $results = [];
        foreach ($this->inMemoryStore as $item) {
            /** @var KnowledgeChunkModel $chunk */
            $chunk = $item['chunk'];
            $itemVector = $item['vector'];

            // Apply source_type filter
            if (!empty($filters['source_type']) && $chunk->getSourceType() !== $filters['source_type']) {
                continue;
            }

            $similarity = $this->cosineSimilarity($vector, $itemVector);
            $chunkCopy = clone $chunk;
            $chunkCopy->setRelevanceScore($similarity);
            $results[] = $chunkCopy;
        }

        usort($results, fn ($a, $b) => $b->getRelevanceScore() <=> $a->getRelevanceScore());
        return array_slice($results, 0, $topK);
    }

    public function deleteBySource(string $sourceId): bool
    {
        foreach ($this->inMemoryStore as $id => $item) {
            if ($item['chunk']->getSourceId() === $sourceId) {
                unset($this->inMemoryStore[$id]);
            }
        }
        return true;
    }

    public function clear(): bool
    {
        $this->inMemoryStore = [];
        return true;
    }

    private function cosineSimilarity(array $vecA, array $vecB): float
    {
        $dot = 0.0;
        $normA = 0.0;
        $normB = 0.0;
        $minLen = min(count($vecA), count($vecB));

        for ($i = 0; $i < $minLen; $i++) {
            $dot += $vecA[$i] * $vecB[$i];
            $normA += $vecA[$i] * $vecA[$i];
            $normB += $vecB[$i] * $vecB[$i];
        }

        if ($normA <= 0 || $normB <= 0) {
            return 0.0;
        }

        return $dot / (sqrt($normA) * sqrt($normB));
    }
}
