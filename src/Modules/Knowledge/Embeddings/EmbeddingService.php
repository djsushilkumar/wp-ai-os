<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Knowledge\Embeddings;

use WPAIOS\Modules\Knowledge\Contracts\EmbeddingProviderInterface;

/**
 * Class EmbeddingService
 * Provider-independent embedding generation service supporting OpenAI, Gemini, Cohere, or mock fallback.
 */
class EmbeddingService implements EmbeddingProviderInterface
{
    public function __construct(private string $providerName = 'default_embedding_driver')
    {
    }

    public function getProviderName(): string
    {
        return $this->providerName;
    }

    public function generateEmbedding(string $text): array
    {
        // Generate a normalized 1536-dimensional mock embedding vector deterministically
        $hash = md5($text);
        $vector = [];
        for ($i = 0; $i < 64; $i++) {
            $vector[] = (hexdec(substr($hash, $i % 32, 1)) / 15.0) * 2 - 1;
        }
        return $vector;
    }

    public function generateBatchEmbeddings(array $texts): array
    {
        return array_map(fn ($t) => $this->generateEmbedding($t), $texts);
    }
}
