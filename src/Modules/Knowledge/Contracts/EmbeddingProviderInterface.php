<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Knowledge\Contracts;

/**
 * Interface EmbeddingProviderInterface
 */
interface EmbeddingProviderInterface
{
    public function getProviderName(): string;

    public function generateEmbedding(string $text): array;

    /**
     * @param string[] $texts
     * @return array[]
     */
    public function generateBatchEmbeddings(array $texts): array;
}
