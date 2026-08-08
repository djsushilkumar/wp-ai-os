<?php

declare(strict_types=1);

namespace WPAIOS\Modules\AI\Contracts;

interface EmbeddingInterface
{
    /**
     * Generate text embeddings.
     *
     * @param string|array<string> $input
     * @return array<array<float>>
     */
    public function embed(string|array $input): array;
}
