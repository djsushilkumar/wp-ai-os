<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Knowledge\Context;

use WPAIOS\Modules\Knowledge\Models\CitationModel;
use WPAIOS\Modules\Knowledge\Models\KnowledgeChunkModel;
use WPAIOS\Modules\Knowledge\Safety\PromptInjectionGuard;

/**
 * Class ContextBuilder
 * Assembles compact, token-budgeted RAG context with explicit source citations.
 */
class ContextBuilder
{
    public function __construct(private PromptInjectionGuard $guard)
    {
    }

    /**
     * @param KnowledgeChunkModel[] $chunks
     */
    public function buildContext(array $chunks, int $tokenBudget = 2000): array
    {
        $contextText = "=== RETRIEVED KNOWLEDGE CONTEXT (UNTRUSTED EXTERNAL DATA) ===\n\n";
        $citations = [];
        $currentLength = 0;

        foreach ($chunks as $chunk) {
            $safeChunk = $this->guard->sanitizeChunk($chunk);
            $formatted = sprintf(
                "[Source: %s | ID: %s]\n%s\n\n",
                $safeChunk->getSourceTitle(),
                $safeChunk->getSourceId(),
                $safeChunk->getContent()
            );

            if ($currentLength + strlen($formatted) > $tokenBudget * 4) {
                break; // Token budget threshold hit
            }

            $contextText .= $formatted;
            $currentLength += strlen($formatted);

            $citations[] = new CitationModel(
                $safeChunk->getId(),
                $safeChunk->getSourceId(),
                $safeChunk->getSourceType(),
                $safeChunk->getSourceTitle(),
                $safeChunk->getSourceUrl(),
                $safeChunk->getWpObjectId(),
                $safeChunk->getRelevanceScore()
            );
        }

        return [
            'context_text' => trim($contextText),
            'citations' => array_map(fn ($c) => $c->toArray(), $citations),
            'chunk_count' => count($citations),
        ];
    }
}
